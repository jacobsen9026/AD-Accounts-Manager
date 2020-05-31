<?php


namespace App\Api\Ad;


use Adldap\Models\Group;
use App\Api\Ad\ADConnection;
use System\App\LDAPLogger;
use System\SystemLogger;

class ADGroups extends ADApi
{
    /**
     *
     * @param $groupName This should be a unique samaccountname or DN
     */
    public static function getGroup($groupName)
    {
        SystemLogger::get()->info("Getting " . $groupName . " from Active Directory");
        if ($groupName instanceof Group) {
            return $groupName;
        }
        $conn = ADConnection::getConnectionProvider();
        $group = $conn
            ->search()
            ->groups()
            ->find($groupName);

        SystemLogger::get()->debug($group);
        if ($group == null || !$group->exists) {
            //$group = $conn->search()->groups()->where('distinguishedname', '=', $groupName)->get();
            $group = $conn->search()->findByDn($groupName);
            LDAPLogger::get()->debug($group);
        }

        return $group;
    }
}