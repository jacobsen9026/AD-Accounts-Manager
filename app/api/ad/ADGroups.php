<?php


namespace App\Api\Ad;


use App\Api\Ad\ADConnection;
use System\App\LDAPLogger;

class ADGroups extends ADApi
{
    /**
     *
     * @param $groupName This should be a unique samaccountname
     */
    public static function getGroup($groupName)
    {
        LDAPLogger::get()->info("Getting " . $groupName . " from Active Directory");
        return ADConnection::get()
            ->search()
            ->groups()
            ->find($groupName);
    }
}