<?php


namespace App\App;


use App\Models\Database\AuthDatabase;
use App\Models\User\User;
use System\App\Session as CoreSession;

class Session extends CoreSession
{
    /** @var User|null */
    const TIMEOUT = 'timeout';
    const USER = 'user';

    public static function getUser()
    {
        if (isset($_SESSION[self::USER]) and $_SESSION[self::USER] != '') {
            if (self::getTimeoutStatus()) {
                return new User();
            } else {
                return unserialize($_SESSION[self::USER]);
            }
        }
        return new User();
    }


    public static function setUser($user)
    {
        if ($_SESSION[self::USER] = serialize($user)) {
            return true;
        }
        return false;
    }

    /**
     * Update the user timeout with the value set in the application settings
     */
    public static function updateTimeout()
    {

        $nextTimeout = AuthDatabase::getSessionTimeout() + time();
        $_SESSION[self::TIMEOUT] = $nextTimeout;
    }

    public static function getTimeoutStatus()
    {
        if (time() > $_SESSION[self::TIMEOUT]) {
            return true;
        }
        return false;
    }


}