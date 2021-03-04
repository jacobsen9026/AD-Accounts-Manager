<?php

/*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace App\Models\Database;

/**
 * Description of PrivilegeLevelDatabase
 *
 * @author cjacobsen
 */

use App\Models\User\PrivilegeLevel;
use App\Models\User\Permission;
use System\App\AppException;
use System\App\AppLogger;
use System\App\LDAPLogger;
use System\App\UserLogger;
use System\Traits\DomainTools;

abstract class PermissionMapDatabase extends DatabaseModel
{
    use DomainTools;

    const TABLE_NAME = 'PermissionMap';

    /**
     *
     * @param string $groupName
     *
     * @return PrivilegeLevel
     */
    public static function getPermission(PrivilegeLevel $privilegeLevel, string $referenceColumn, int $referenceID)
    {
        $query = new Query(self::TABLE_NAME, Query::SELECT);
        $query->where('Privilege_ID', $privilegeLevel->getId())
            ->where($referenceColumn, $referenceID)
            ->leftJoin(PrivilegeLevelDatabase::TABLE_NAME, 'Privilege_ID', 'ID');
        $result = $query->run()[0];
        echo self::buildPrivilegeLevelDropdown()->print();
    }

    /**
     *
     * @param string $groupName
     *
     * @return PrivilegeLevel
     */
    public static function getPermissionsByOU(string $ou)
    {
        $query = new Query(self::TABLE_NAME, Query::SELECT);
        $query->where(self::TABLE_NAME . '.' . 'OU', $ou)
            ->leftJoin(PrivilegeLevelDatabase::TABLE_NAME, 'Privilege_ID', 'ID');
        $result = $query->run();
        //var_dump($result);
        return $result;
    }

    /**
     *
     * @param string $groupName
     *
     * @return Permission
     */
    public static function getPermissionByID(int $id)
    {
        $query = new Query(self::TABLE_NAME, Query::SELECT);
        $query->where(self::TABLE_NAME . '.' . 'ID', $id)
            ->leftJoin(PrivilegeLevelDatabase::TABLE_NAME, 'Privilege_ID', 'ID');
        $result = $query->run();
        //var_dump($result[0]);
        LDAPLogger::get()->debug($result);
        return $result[0];
    }

    /**
     *
     * @param string $groupName
     *
     * @return PrivilegeLevel
     */
    public static function getPrivilegeIDsByOU(string $ou)
    {
        $query = new Query(self::TABLE_NAME, Query::SELECT, 'Privilege_ID');
        $query->where(self::TABLE_NAME . '.' . 'OU', $ou);

        $result = array_flatten($query->run());
        if (!is_array($result)) {
            $result = [$result];
        }
        //var_dump($result);
        return $result;
    }

    /**
     *
     * @param type $id
     */
    public static function removePermissionByID($id)
    {
        $query = new Query(self::TABLE_NAME, Query::DELETE);
        $query->where('ID', $id);
        return $query->run();
    }

    /**
     *
     * @param PrivilegeLevel $level
     *
     * @todo Move to PrivilegeLevelDatabase class
     */
    public static function addPrivilegeLevel(string $adGroupName)
    {
        $query = new Query(self::TABLE_NAME, Query::INSERT);
        $query->insert('AD_Group_Name', $adGroupName);

        return $query->run();
    }

    public static function addPermission(Permission $permission)
    {
        $query = new Query(self::TABLE_NAME, Query::INSERT);

        $query->insert('OU', $permission->getOU())
            ->insert('Ref_ID', $permission->getRefID())
            ->insert('Privilege_ID', $permission->getPrivilegeID())
            ->insert('User_Perm', $permission->getUserPermissionLevel())
            ->insert('Group_Perm', $permission->getGroupPermissionLevel());

        try {
            $response = $query->run();
        } catch (\PDOException $ex) {
            if ($ex->getCode() == 23000) {
                AppLogger::get()->error($ex);
                throw new AppException("Permission already exists", 1, $ex);
            } else
                throw $ex;
        }
    }

    public static function modifyPermission(Permission $permission)
    {
        $query = new Query(self::TABLE_NAME, Query::UPDATE);
        $query->where('ID', $permission->getId())
            ->set('User_Perm', $permission->getUserPermissionLevel())
            ->set('Group_Perm', $permission->getGroupPermissionLevel());
        //var_dump($query);
        try {
            $response = $query->run();
        } catch (\PDOException $ex) {
            //var_dump($ex);
            if ($ex->getCode() == 23000) {
                AppLogger::get()->error($ex);
                throw new AppException("Permission already exists", 1, $ex);
            } else
                throw $ex;
        }
    }

    public static function get()
    {
        return parent::get();

    }

    public static function getAllPermissionMappings()
    {
        $dbTable = parent::get();
        foreach ($dbTable as $row) {
            $level = new PrivilegeLevel();
            $level->setId($row['ID'])
                ->setAdGroup($row['AD_Group_Name'])
                ->setSuperAdmin($row['Super_Admin']);
            $levels[] = $level;
        }
        return $levels;
    }

    /**
     *
     * @param array<int>|null $privilegeLevelID
     *
     * @return array
     */
    public static function getRelevantPermissions(array $privilegeLevelIDs, string $ou)
    {
        if (!empty($privilegeLevelIDs)) {
            $tree = self::getOUTree($ou);

            //var_dump($tree);
            $query = new Query(self::TABLE_NAME, Query::SELECT);
            //return;

            $query->orWhere('Privilege_ID', $privilegeLevelIDs)
                ->orWhere('OU', $tree)
                ->sort(Query::DESC, self::TABLE_NAME . '.OU');
            $permissions = [];
            if ($rawPermissions = ($query->run())) {
                //var_dump($rawPermissions);

                foreach ($rawPermissions as $rawPermission) {
                    //  var_dump($rawPermission);
                    $permission = new Permission();
                    $permission->loadFromDatabaseResponse($rawPermission);
                    $permissions[] = $permission;
                    //var_dump("imported");
                }
            }
            //var_dump($permissions);
            return $permissions;
        }
        return [];
    }

    /**
     *
     * @param string $permissionType
     * @param array $privilegeLevels An array of PrivilegeLevels
     */
    public static function hasPermissionType(string $permissionType, array $privilegeLevels, int $permissionLevel = 1)
    {
        UserLogger::get()->info('Checking if user has any defined permissions for ' . $permissionType);
        $ids = [];
        foreach ($privilegeLevels as $level) {
            $ids[] = $level->getId();
        }

        $query = new Query(self::TABLE_NAME, Query::SELECT);
//return;

        $query->orWhere('Privilege_ID', $ids)
            //->orWhere($permissionType, [1, 2, 3, 4])
            ->Where($permissionType, $permissionLevel, '>=')
            ->sort(Query::DESC, self::TABLE_NAME . '.OU');
        $result = $query->run();
        if (is_array($result)) {
            if (count($result) > 0) {
                return true;
            }
        }
        return false;
    }

    public
    static function getSubOUPermissionsCount(string $ou)
    {
        return self::getOUPermissionsCount("," . $ou, "LIKE");
    }

    public
    static function getOUPermissionsCount(string $ou, $operator = '=')
    {
        if (strtoupper($operator) === 'LIKE') {
            $ou = "%" . $ou . "%";
        }

        $query = new Query(self::TABLE_NAME, Query::COUNT, 'ID');

        $query->where('OU', $ou, $operator);
        return $query->run();
    }
}
