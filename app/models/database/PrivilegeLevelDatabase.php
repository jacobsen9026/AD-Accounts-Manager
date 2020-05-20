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

abstract class PrivilegeLevelDatabase extends DatabaseModel {

    const TABLE_NAME = 'PrivilegeLevel';

    /**
     *
     * @param string $groupName
     * @return PrivilegeLevel
     */
    public static function getPrivilegeLevel(string $groupName) {
        $query = new Query(self::TABLE_NAME, Query::SELECT);
        $query->where('AD_Group_Name', $groupName);
        $result = $query->run()[0];
        $level = new PrivilegeLevel();
        $level->importFromDatabase($result);
        return $level;
    }

    /**
     *
     * @param type $id
     */
    public static function removePrivilegeLevel($id) {
        $query = new Query(self::TABLE_NAME, Query::DELETE);
        $query->where('ID', $id);
        return $query->run();
    }

    /**
     *
     * @param PrivilegeLevel $level
     */
    public static function addPrivilegeLevel(string $adGroupName, int $districtID) {
        $query = new Query(self::TABLE_NAME, Query::INSERT);
        $query->insert('AD_Group_Name', $adGroupName)
                ->set('District_ID', $districtID);

        return $query->run();
    }

    /**
     *
     * @param int $id
     * @param int $districtID
     * @param string $adGroupName
     * @param bool $superAdmin
     */
    public static function updatePrivilegeLevel(int $id, string $adGroupName, $superAdmin) {
        $query = new Query(self::TABLE_NAME, Query::UPDATE);
        $query->where('ID', $id)
                ->set('AD_Group_Name', $adGroupName)
                ->set('Super_Admin', $superAdmin);
        return $query->run();
    }

    /**
     *
     * @return array <PrivilegeLevel>
     */
    public static function get($id = null) {
        if ($id == null) {
            $dbTable = parent::get();
            if ($dbTable != false) {
                foreach ($dbTable as $row) {
                    $level = new PrivilegeLevel();
                    $level->setId($row['ID'])
                            ->setAdGroup($row['AD_Group_Name'])
                            ->setSuperAdmin($row['Super_Admin']);
                    $levels[] = $level;
                }
                return $levels;
            }
            return false;
        } else {
            $query = new Query(self::TABLE_NAME, Query::SELECT);
            $query->where('ID', $id);
            return $query->run()[0];
        }
    }

}
