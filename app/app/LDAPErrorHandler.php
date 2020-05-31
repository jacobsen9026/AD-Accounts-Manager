<?php


namespace app\app;


use Adldap\AdldapException;
use System\App\AppException;
use System\App\AppLogger;
use System\App\Error\AppErrorHandler;

class LDAPErrorHandler
{
    public static $instance;

    function __construct()
    {

        set_error_handler([$this, 'handleError']);
        if (isset(self::$instance)) {
            return;
        } else {
            self::$instance = $this;
        }
    }

    /**
     *
     * @return AppErrorHandler
     */
    public static function get()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    //put your code here

    public function handleError($code, $description, $file = null, $line = null, $context = null)
    {
        AppLogger::get()->error($description);

        if ($code === 2) {

            throw new AdldapException('Could not enable TLS');
            return true;
        }
        throw new AppException($description, $code);
        return true;
    }

}