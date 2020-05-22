<?php

/*
 * The MIT License
 *
 * Copyright 2019 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace App\Api;

/**
 * Description of GAM
 *
 * @author cjacobsen
 */

use \Google_Client;

class GAM
{

//put your code here
    private $clientSecretFile;
    private $tokenPath;
    private $scopes;

    /** @var AppLogger The application logger */
    private $logger;

    /** @var \Google_Client The primary Google API client interface */
    private $googleClient;
    private $redirectUri;
    private $customerID;

    /** @var GAM|null */
    public static $instance;

    function __construct()
    {

        if (isset(self::$instance)) {
            return self::$instance;
        } else {
            self::$instance = $this;
            $this->logger = \System\App\AppLogger::get();
            $this->clientSecretFile = GAMPATH . DIRECTORY_SEPARATOR . 'client_secret.json';
            $this->tokenPath = GAMPATH . DIRECTORY_SEPARATOR . 'token.json';
            $this->customerID = "my_customer";
            $this->redirectUri = 'https://' . $_SERVER['HTTP_HOST'] . '/settings/districts';
            $this->scopes = ["https://www.googleapis.com/auth/admin.directory.domain",
                "https://www.googleapis.com/auth/admin.directory.customer",
                "https://www.googleapis.com/auth/admin.directory.user",
                "https://www.googleapis.com/auth/admin.directory.group",
                "https://www.googleapis.com/auth/admin.directory.orgunit",
                "https://www.googleapis.com/auth/admin.directory.resource.calendar"];
            if ($this->clientSecretExists()) {
                $this->prepareGoogleClient();
            }
        }
    }

    /**
     *
     * @return GAM
     */
    public static function get()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function prepareGoogleClient()
    {


        $oauth_credentials = GAMPATH . DIRECTORY_SEPARATOR . "client_secret.json";


        $this->googleClient = new \Google_Client();
        $this->googleClient->setAuthConfig($oauth_credentials);
        $this->googleClient->setRedirectUri($this->redirectUri);
// offline access will give you both an access and refresh token so that
// your app can refresh the access token without user interaction.
        $this->googleClient->setAccessType('offline');
        $this->googleClient->setPrompt('consent');
        $this->googleClient->addScope($this->scopes);
// Load previously authorized token from a file, if it exists.
// The file token.json stores the user's access and refresh tokens, and is
// created automatically when the authorization flow completes for the first
// time.

        if (file_exists($this->tokenPath)) {
            $accessToken = json_decode(file_get_contents($this->tokenPath), true);
            $this->googleClient->setAccessToken($accessToken);
        }

        if (isset($_GET['code'])) {
            $this->generateToken();
        }
// If there is no previous token or it's expired.
        if ($this->googleClient->isAccessTokenExpired()) {
            $this->getAccessToken();
        }

//var_dump($this->googleClient->getRefreshToken());
    }

    private function getAccessToken()
    {
// Refresh the token if possible, else fetch a new one.
        if ($this->googleClient->getRefreshToken()) {
            $this->googleClient->fetchAccessTokenWithRefreshToken($this->googleClient->getRefreshToken());
        } else {
// Request authorization from the user.

            $this->authUrl = $this->googleClient->createAuthUrl();
        }
    }

    private function generateToken()
    {
//var_dump($_GET["code"]);
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($_GET['code']);
        $this->googleClient->setAccessToken($token);
// Check to see if there was an error.
        if (array_key_exists('error', $token)) {
            throw new Exception(join(', ', $token));
        }
// store in the session also
        $_SESSION['upload_token'] = $token;

// Save the token to a file.
        if (!file_exists(dirname($this->tokenPath))) {
            mkdir(dirname($this->tokenPath), 0700, true);
        }
        file_put_contents($this->tokenPath, json_encode($this->googleClient->getAccessToken()));
// redirect back to settings
        header('Location: ' . filter_var($this->redirectUri, FILTER_SANITIZE_URL));
//exit;
    }

    public function isAuthorized()
    {
        if (!isset($this->googleClient)) {
            return false;
        }
        if (isset($this->authUrl)) {
            return false;
        } elseif ($this->googleClient->isAccessTokenExpired()) {
            return false;
        }
        return true;
    }

    public function getAuthUrl()
    {
        if (isset($this->authUrl)) {
            return $this->authUrl;
        } else {
            return $this->googleClient->createAuthUrl($this->scopes);
        }
        return "";
    }

    public function clientSecretExists()
    {
        if (file_exists($this->clientSecretFile)) {
            return true;
        } else {
            return false;
        }
    }

    public function getDomainNames()
    {
        $service = new \Google_Service_Directory($this->googleClient);
        $domains = $service->domains;
        $customer = $service->customers->get($this->customerID);
        $domainList = $domains->listDomains($customer->id);
        $domainNames = [];
        $domainNames[] = $domainList->current()->domainName;
        while ($domainList->next()) {
            $domainNames[] = ($domainList->current()->domainName);
        }
        return $domainNames;
    }

    public function getScopes()
    {
        return $this->scopes;
    }

    public function getUser($username)
    {
        $username = $username . "@branchburg.k12.nj.us";
        echo "<br><br><br>";
        //echo $username;
// Print the first 10 users in the domain.
        $optParams = [
            'customer' => 'my_customer',
            'maxResults' => 10,
            'email' => $username,
            'orderBy' => 'email',
        ];
        if ($this->googleClient != null) {
            $service = new \Google_Service_Directory($this->googleClient);
            $user = $service->users->get($username);

            //$users = $service->getUsers($username);
//var_dump($user);
            return $user;
        }
        return false;
    }

    public function getUserGroups($username)
    {
        //var_dump($username);
        $service = new \Google_Service_Directory($this->googleClient);
        $optParams = [
            'userKey' => $username,
        ];
        $groups = $service->groups->listGroups($optParams);
        return $groups["modelData"]["groups"];
    }

}
