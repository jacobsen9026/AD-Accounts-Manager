<?php


namespace App\Api\Ad;


use App\Models\User\PermissionHandler;
use App\Models\User\PermissionLevel;
use System\App\AppException;
use System\App\LDAPLogger;
use System\Traits\DomainTools;

class ADUsers extends ADApi
{
    use DomainTools;

    public static function listUsers(string $searchTerm)
    {
        $andFilter = ["objectClass" => "user"];
        $users = ADConnection::get()->search()
            ->users()
            ->select('samaccountname', 'dn')
            ->orWhereContains("sn", $searchTerm)
            ->orWhereContains("givenname", $searchTerm)
            ->orWhereContains("samaccountname", $searchTerm)
            ->where($andFilter)
            ->get();
        $usernames = [];
        /* @var $user \Adldap\Models\User */
        foreach ($users as $user) {
            if (PermissionHandler::hasPermission(self::getOUFromDN($user->getDistinguishedName()), PermissionLevel::USERS, PermissionLevel::USER_READ)) {
                $usernames[] = $user->getAccountName();
            }
        }
        return $usernames;
    }

    public static function getUser($username)
    {
        LDAPLogger::get()->info("Getting " . $username . " from Active Directory");
        $adUser = ADConnection::get()
            ->search()
            ->users()
            ->find($username);
        if ($adUser == null) {
            throw new AppException('That user was not found.', AppException::USER_NOT_FOUND);
        }
        return $adUser;
    }
}