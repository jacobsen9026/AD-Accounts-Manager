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

namespace app\models\database;

/**
 * Description of PrivilegeLevelDatabase
 *
 * @author cjacobsen
 */
use app\models\user\PrivilegeLevel;
use app\models\user\Permission;
use system\app\forms\FormDropdown;
use system\app\forms\FormDropdownOption;
use app\models\user\PermissionLevel;
use system\app\AppException;
use system\Encryption;

abstract class PermissionMapDatabase extends DatabaseModel {

    const TABLE_NAME = 'PermissionMap';

    /**
     *
     * @param string $groupName
     * @return PrivilegeLevel
     */
    public static function getPermission(PrivilegeLevel $privilegeLevel, string $referenceColumn, int $referenceID) {
        $query = new Query(self::TABLE_NAME, Query::SELECT);
        $query->where('Privilege_ID', $privilegeLevel->getId())
                ->where($referenceColumn, $referenceID)
                ->leftJoin(PrivilegeLevelDatabase::TABLE_NAME, 'Privilege_ID', 'ID');
        $result = $query->run()[0];
        var_dump($result);
        echo self::buildPrivilegeLevelDropdown()->getHTML();
    }

    /**
     *
     * @param string $groupName
     * @return PrivilegeLevel
     */
    public static function getPermissionsByOU(string $ou) {
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
     * @return PrivilegeLevel
     */
    public static function getPermissionByID(int $id) {
        $query = new Query(self::TABLE_NAME, Query::SELECT);
        $query->where(self::TABLE_NAME . '.' . 'ID', $id)
                ->leftJoin(PrivilegeLevelDatabase::TABLE_NAME, 'Privilege_ID', 'ID');
        $result = $query->run();
        //var_dump($result);
        return $result[0];
    }

    /**
     *
     * @param string $groupName
     * @return PrivilegeLevel
     */
    public static function getPrivilegeIDsByOU(string $ou) {
        $query = new Query(self::TABLE_NAME, Query::SELECT, 'Privilege_ID');
        $query->where(self::TABLE_NAME . '.' . 'OU', $ou);

        $result = array_flatten($query->run());
        if (!is_array($result)) {
            $result = array($result);
        }
        //var_dump($result);
        return $result;
    }

    /**
     *
     * @param type $id
     */
    public static function removePermissionByID($id) {
        $query = new Query(self::TABLE_NAME, Query::DELETE);
        $query->where('ID', $id);
        return $query->run();
    }

    /**
     *
     * @param PrivilegeLevel $level
     * @todo Move to PrivilegeLevelDatabase class
     */
    public static function addPrivilegeLevel(string $adGroupName) {
        $query = new Query(self::TABLE_NAME, Query::INSERT);
        $query->insert('AD_Group_Name', $adGroupName);

        return $query->run();
    }

    public static function addPermission(Permission$permission) {
        $query = new Query(self::TABLE_NAME, Query::INSERT);

        $query->insert('OU', $permission->getOU())
                ->insert('Ref_ID', $permission->getRefID())
                ->insert('Privilege_ID', $permission->getPrivilegeID())
                ->insert('User_Perm', $permission->getUserPermissionLevel())
                ->insert('Group_Perm', $permission->getGroupPermissionLevel());

        try {
            $response = $query->run();
        } catch (\PDOException $ex) {
            //var_dump($ex);
            if ($ex->getCode() == 23000) {
                \system\app\AppLogger::get()->error($ex);
                throw new AppException("Permission already exists", 1, $ex);
            } else
                throw $ex;
        }
    }

    public static function modifyPermission(Permission$permission) {
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
                \system\app\AppLogger::get()->error($ex);
                throw new AppException("Permission already exists", 1, $ex);
            } else
                throw $ex;
        }
    }

    public static function get() {
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
     * @return array
     */
    public static function getRelevantPermissions(array $privilegeLevelIDs, string $ou) {
        if (!empty($privilegeLevelIDs)) {
            $tree = array();

            $parts = explode(',', $ou);

            for ($y = 0; $y < count($parts); $y++) {
                $branch = '';
                for ($x = $y; $x < count($parts); $x++) {
                    $branch .= $parts[$x];
                    if ($x != count($parts) - 1) {
                        $branch .= ',';
                    }
                }
                if (substr($branch, 0, 2) != "DC") {
                    $tree[] = $branch;
                }
            }

            //var_dump($tree);
            $query = new Query(self::TABLE_NAME, Query::SELECT);
            //return;

            $query->orWhere('Privilege_ID', $privilegeLevelIDs)
                    ->orWhere('OU', $tree)
                    ->sort(Query::DESC, self::TABLE_NAME . '.OU');
            $permissions = array();
            if ($rawPermissions = ($query->run())) {
                //var_dump($rawPermissions);

                foreach ($rawPermissions as $rawPermission) {
                    //  var_dump($rawPermission);
                    $permission = new Permission();
                    $permission->importFromDatabase($rawPermission);
                    $permissions[] = $permission;
                    //var_dump("imported");
                }
            }
            //var_dump($permissions);
            return $permissions;
        }
        return array();
    }

}
