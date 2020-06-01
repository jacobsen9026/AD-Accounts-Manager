<?php


namespace App\Api\Ad;


/**
 * Class ADConnection
 *
 * @package App\Api\Ad
 */

use Adldap\AdldapException;
use Adldap\Auth\BindException;
use Adldap\Connections\Provider;
use Adldap\Connections\ProviderInterface;
use app\app\LDAPErrorHandler;
use App\Models\Database\DistrictDatabase;
use Adldap\Adldap;
use System\App\Error\AppErrorHandler;
use System\App\LDAPLogger;

class ADConnection extends Adldap
{
    /**
     * @var TestADConnection
     */
    private static $instance;
    /**
     * @var string
     */
    private static $lastError;

    /**
     * @var LDAPLogger
     */
    private $ldapLogger;


    /**
     * @var ProviderInterface
     */
    private $connection;

    public function __construct(array $configuration = [])
    {
        $this->ldapLogger = LDAPLogger::get();
        self::setLogger($this->ldapLogger);
        parent::__construct();
        if (empty($configuration)) {
            $fqdn = DistrictDatabase::getAD_FQDN(1);
            $configuration = [
                'hosts' => [$fqdn],
                'base_dn' => DistrictDatabase::getAD_BaseDN(1),
                'username' => DistrictDatabase::getADUsername(1),
                'password' => DistrictDatabase::getADPassword(1),
                'port' => 389
            ];

            if (DistrictDatabase::getAD_UseTLS()) {
                $this->ldapLogger->info('Connecting to ' . $fqdn . ' with TLS enabled');
                $configuration['use_tls'] = true;
            }

            try {
                new self($configuration);
            } catch (\Exception $exception) {

                self::$lastError = $exception->getMessage();
                $this->ldapLogger->error(get_class($exception));

                $this->ldapLogger->error($exception);
            }
        }


        $this->addProvider($configuration);
        try {
            new LDAPErrorHandler();
            $this->connection = $this->connect();
            new AppErrorHandler();
        } catch (BindException $exception) {
            self::$lastError = $exception->getMessage();
        } catch (\Exception $exception) {

            self::$lastError = $exception->getMessage();
            $this->ldapLogger->error(get_class($exception));
            $this->ldapLogger->error($exception);

        } catch (\Error $error) {
            self::$lastError = $error->getMessage();
            $this->ldapLogger->error($error);
        }


        self::$instance = $this;
    }

    /**
     * Gets an Active Directory connection using configured settings
     * If no connection has been made this will connect
     *
     * @return ProviderInterface
     */
    public static function getConnectionProvider(): ?ProviderInterface
    {
        return self::get()->connection;

    }

    /**
     * Gets an Active Directory connection using configured settings
     * If no connection has been made this will connect
     *
     * @return ADConnection
     */
    public static function get()
    {
        if (!isset(self::$instance)) {
            new self();

            return self::$instance;
        } else {
            return self::$instance;
        }

    }

    /**
     * @return bool
     */
    public static function isConnected(): bool
    {
        /**
         * If we haven't connected yet, attempt to connect.
         */
        if (self::$instance == null) {
            self::getConnectionProvider();
        }
        /**
         * Now check if connection is legit
         */
        if (self::$instance->connection instanceof Provider === false) {
            return false;
        }


        return true;
    }

    /**
     * Returns the last error message
     *
     * @return string
     */
    public static function getError()
    {
        return self::$lastError;
    }

    public
    static function isSecure()
    {
        if (self::$instance->connection->getConnection()->isUsingSSL() || self::$instance->connection->getConnection()->isUsingTLS()) {
            return true;
        }
        return false;
    }

    /**
     * @param $ldapUser
     * @param $password
     *
     * @return bool
     */
    public function verifyCredentials($ldapUser, $password)
    {
        try {
            if ($this->auth()->attempt($ldapUser, $password)) {
                // Passed.
                return true;
            } else {
                // Failed.
                return false;
            }
        } catch (Adldap\Auth\UsernameRequiredException $e) {
            $this->ldapLogger->warning($e);
            // The user didn't supply a username.
        } catch (Adldap\Auth\PasswordRequiredException $e) {
            // The user didn't supply a password.
            $this->ldapLogger->warning($e);

        }
        return false;
    }

    public function isInGroup($username, $groupname)
    {
        return ADUsers::isUserInGroup($username, $groupname);
    }

}