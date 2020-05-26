<?php


namespace App\Api\Ad;


/**
 * Class ADConnection
 *
 * @package App\Api\Ad
 */

use Adldap\Connections\Provider;
use App\Models\Database\DistrictDatabase;
use Adldap\Adldap;
use System\App\LDAPLogger;

class ADConnection extends Adldap
{
    /**
     * @var TestADConnection
     */
    private static $instance;

    /**
     * @var LDAPLogger
     */
    private $ldapLogger;


    /**
     * @var \Adldap\Connections\ProviderInterface
     */
    private $connection;

    public function __construct(array $configuration = [])
    {
        $this->ldapLogger = LDAPLogger::get();
        parent::__construct();
        if (empty($configuration)) {
            $fqdn = DistrictDatabase::getAD_FQDN(1);
            $configuration = [
                'hosts' => [$fqdn],
                'base_dn' => DistrictDatabase::getAD_BaseDN(1),
                'username' => DistrictDatabase::getADUsername(1),
                'password' => DistrictDatabase::getADPassword(1),
            ];

            if (DistrictDatabase::getAD_UseTLS()) {
                $this->ldapLogger->info('Connecting to ' . $fqdn . ' with TLS enabled');
                $configuration['use_tls'] = true;
            }

            try {
                new self($configuration);
            } catch (\Exception $exception) {
                $this->ldapLogger->error(self::$ad);
            }
        }


        $this->addProvider($configuration);
        $this->connection = $this->connect();


        self::$instance = $this;
    }

    /**
     * Gets an Active Directory connection using configured settings
     * If no connection has been made this will connect
     *
     * @return ADConnection|\Adldap\Connections\ProviderInterface
     */
    public static function get()
    {
        if (!isset(self::$instance)) {
            new self();

            return self::$instance->connection;
        } else {
            return self::$instance->connection;
        }

    }

    /**
     * @return bool
     */
    public static function isConnected(): bool
    {
        if (self::$instance == null) {
            self::get();
        }
        if (self::$instance->connection instanceof Provider === false) {
            return false;
        }


        return true;
    }

    public static function isSecure()
    {
        if (self::$instance->connection->getConnection()->isUsingSSL() || self::$instance->connection->getConnection()->isUsingTLS()) {
            return true;
        }
        return false;
    }


}