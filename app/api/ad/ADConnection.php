<?php


namespace App\Api\Ad;


/**
 * Class ADConnection
 *
 * @package App\Api\Ad
 */

use Adldap\Adldap\Auth\PasswordRequiredException;
use Adldap\Adldap\Auth\UsernameRequiredException;
use Adldap\AdldapException;
use Adldap\Connections\Provider;
use Adldap\Connections\ProviderInterface;
use App\Models\Database\DomainDatabase;
use Adldap\Adldap;
use Error;
use Exception;
use System\App\AppException;
use System\App\Error\AppErrorHandler;
use System\App\LDAPLogger;

class ADConnection extends Adldap
{
    /**
     * @var ADConnection
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
            $fqdn = DomainDatabase::getAD_FQDN(1);
            $configuration = [
                'hosts' => [$fqdn],
                'base_dn' => DomainDatabase::getAD_BaseDN(1),
                'username' => DomainDatabase::getADUsername(1),
                'password' => DomainDatabase::getADPassword(1),
                'port' => 389
            ];
            $this->ldapLogger->debug($configuration);
            if (DomainDatabase::getAD_UseTLS()) {
                $this->ldapLogger->info('Connecting to ' . $fqdn . ' with TLS enabled');
                $configuration['use_tls'] = true;
            }

            try {
                new self($configuration);
            } catch (Exception $exception) {

                self::$lastError = $exception->getMessage();
                $this->ldapLogger->error(get_class($exception));

                $this->ldapLogger->error($exception);
            }
        }

        try {
            $this->addProvider($configuration);

            set_error_handler([$this, 'handleError']);

            if ($configuration["username"] !== '' && $configuration["password"] !== '' && $configuration["base_dn"] !== '') {
                $this->connection = $this->connect();
            } else {
                throw new AppException("Missing username or password");
            }
            new AppErrorHandler();
        } catch (AdldapException $exception) {
            $this->ldapLogger->warning($exception);

        } catch (Exception $exception) {

            self::$lastError = $exception->getMessage();
            $this->ldapLogger->error(get_class($exception));
            $this->ldapLogger->error($exception);

        } catch (Error $error) {
            self::$lastError = $error->getMessage();
            $this->ldapLogger->error($error);
        }


        self::$instance = $this;
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
            LDAPLogger::get()->info("AD not connected");
            return false;
        }
        /**
         * Now check if the connection is connected
         */
        LDAPLogger::get()->debug(self::$instance->connection->getConnection()->getLastError());
        if (!self::$instance->connection->getConnection()->getLastError()) {
            LDAPLogger::get()->info("AD not connected");

            return false;
        }

        LDAPLogger::get()->info("AD connected");

        return true;
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
        }

        return self::$instance;

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
        return self::$instance->connection->getConnection()->isUsingSSL() || self::$instance->connection->getConnection()->isUsingTLS();
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
            }

            // Failed.
            return false;
        } catch (UsernameRequiredException $e) {
            $this->ldapLogger->warning($e);
            // The user didn't supply a username.
        } catch (PasswordRequiredException $e) {
            // The user didn't supply a password.
            $this->ldapLogger->warning($e);

        }
        return false;
    }

    public function isInGroup($username, $groupname)
    {
        return ADUsers::isUserInGroup($username, $groupname);
    }

    public function handleError($code, $description, $file = null, $line = null, $context = null)
    {
        LDAPLogger::get()->error($code);
        LDAPLogger::get()->error($description);
        $description = str_replace("ldap_bind(): ", '', $description);
        self::$lastError = $description;
        throw new AppException($description);

    }

}