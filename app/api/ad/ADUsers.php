<?php


namespace App\Api\Ad;


use App\Models\Database\DomainDatabase;
use App\Models\User\PermissionHandler;
use App\Models\User\PermissionLevel;
use App\Models\User\User;
use System\App\AppException;
use System\App\LDAPLogger;

class ADUsers extends ADApi
{

    public static function listUsers(string $searchTerm)
    {
        if (str_contains($searchTerm, " ")) {
            $users = ADConnection::getConnectionProvider()->search()
                ->users()
                ->select('samaccountname', 'dn')
                ->whereContains("sn", explode(" ", $searchTerm)[1])
                ->whereContains("givenname", explode(" ", $searchTerm)[0])
                ->get();

        } else {
            $users = ADConnection::getConnectionProvider()->search()
                ->users()
                ->select('samaccountname', 'dn')
                ->orWhereContains("sn", $searchTerm)
                ->orWhereContains("givenname", $searchTerm)
                ->orWhereContains("samaccountname", $searchTerm)
                ->orWhereContains("givenname", $searchTerm)
                ->get();
        }
        /* @var $user \Adldap\Models\User */
        foreach ($users as $user) {
            if (PermissionHandler::hasPermission($user->getDistinguishedName(), PermissionLevel::USERS, PermissionLevel::USER_READ)) {
                $usernames[] = $user->getAccountName();
            }
        }

        if (empty($usernames)) {
            return [];
        }

        return $usernames;
    }

    public static function getDomainScopUser(string $username)
    {
        return self::getUser($username);
    }


    /**
     * @param $serchTerm
     * @param $baseDN
     *
     * @return User
     * @throws AppException
     */

    private static function getUser($serchTerm, $baseDN = null, $searchAgainst = 'samaccountname')
    {

        if (is_null($baseDN) or $baseDN === '') {
            $baseDN = DomainDatabase::getAD_BaseDN();
        }
        LDAPLogger::get()->info("Getting " . $serchTerm . " from Active Directory in " . $baseDN);
        $adUser = ADConnection::getConnectionProvider()
            ->search()
            ->users()
            ->in($baseDN)
            ->where($searchAgainst, '=', $serchTerm)
            ->limit(1)
            ->get();
        //var_export($adUser);
        LDAPLogger::get()->debug($adUser);
        $adUser = $adUser[0];
        if ($adUser == null) {
            throw new AppException('That user was not found.', AppException::USER_NOT_FOUND);
        }


        return $adUser;
    }

    /**
     * @param string $username
     *
     * @return User
     * @throws AppException
     */
    public static function getApplicationScopeUser(string $username)
    {
        LDAPLogger::get()->info('Searching for ' . $username . ' domain wide.');

        $searchDN = self::FQDNtoDN(DomainDatabase::getAD_FQDN());
        return self::getUser($username, $searchDN);
    }

    public static function isUserInGroup(string $username, string $groupname)
    {
        //var_dump($groupname);


        $groupDN = ADConnection::getConnectionProvider()
            ->search()
            ->groups()
            ->in(self::FQDNtoDN(DomainDatabase::getAD_FQDN()))
            ->find($groupname);
        //var_dump($groupDN);
        $matchedUser = ADConnection::getConnectionProvider()
            ->search()
            ->rawFilter('(&(objectClass=user)(memberOf:1.2.840.113556.1.4.1941:=' . $groupDN . ')(sAMAccountName=' . $username . '))')
            ->in(self::FQDNtoDN(DomainDatabase::getAD_FQDN()))
            ->get()[0];
        //var_dump($matchedUser);
        if ($matchedUser->exists) {
            LDAPLogger::get()->debug($username . ' is in group ' . $groupname);
            return true;
        }
        return false;
    }

    public static function getUserByDN($distinguishedName)
    {
        try {
            return self::getUser($distinguishedName, null, 'distinguishedname');
        } catch (AppException $e) {
            throw new AppException($e->getMessage(), $e->getCode());
        }

    }
}