<?php


namespace App\Api\Ad;


use Adldap\Models\Group;
use App\Api\Ad\ADConnection;
use App\Models\Database\DistrictDatabase;
use System\App\AppException;
use System\App\LDAPLogger;
use System\SystemLogger;

class ADGroups extends ADApi
{

    /**
     *
     * @param $groupName This should be a unique samaccountname or DN
     */
    public static function getGroup($groupName, $baseDN = null)
    {
        if (is_null($baseDN) or $baseDN === '') {
            $baseDN = self::getOUFromDN(DistrictDatabase::getAD_BaseDN());
        }

        LDAPLogger::get()->info("Getting " . $groupName . " from Active Directory");
        if ($groupName instanceof Group) {
            return $groupName;
        }
        $conn = ADConnection::getConnectionProvider();
        $group = $conn
            ->search()
            ->groups()
            ->in($baseDN)
            ->where("samaccountname", '=', $groupName)
            ->limit(1)
            ->get()[0];


        LDAPLogger::get()->debug($group);
        if ($group == null || !$group->exists) {
            $group = $conn->search()->findByDn($groupName);
            LDAPLogger::get()->debug($group);
        }
        if ($group == null || !$group->exists) {
            throw new AppException('That group was not found.', AppException::GROUP_NOT_FOUND);
        }
        return $group;
    }
}