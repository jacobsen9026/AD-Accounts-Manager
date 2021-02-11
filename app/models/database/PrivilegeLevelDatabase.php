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

use App\Api\Ad\ADGroups;
use App\Models\District\DomainGroup;
use App\Models\User\PrivilegeLevel;
use System\App\AppException;
use System\App\AppLogger;

abstract class PrivilegeLevelDatabase extends DatabaseModel
{

    const TABLE_NAME = 'PrivilegeLevel';


    /**
     *
     * @param type $id
     */
    public static function removePrivilegeLevel($id)
    {
        $query = new Query(self::TABLE_NAME, Query::DELETE);
        $query->where('ID', $id);
        return $query->run();
    }

    /**
     *
     * @param PrivilegeLevel $level
     */
    public static function addPrivilegeLevel(string $adGroupName, int $districtID)
    {
        $query = new Query(self::TABLE_NAME, Query::INSERT);
        $query->insert('AD_Group_Name', $adGroupName)
            ->set('District_ID', $districtID);

        return $query->run();
    }

    /**
     *
     * @param int $id
     * @param string $adGroupName
     * @param bool $superAdmin
     */
    public static function updatePrivilegeLevel(int $id, string $adGroupName, $superAdmin)
    {
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
    public static function get($id = null)
    {
        if ($id == null) {
            $dbTable = parent::get();
            $logger = AppLogger::get();
            ksort($dbTable);
            $logger->debug($dbTable);
            if ($dbTable != false) {
                foreach ($dbTable as $row) {
                    $level = new PrivilegeLevel();
                    try {
                        $level->setId($row['ID'])
                            ->setAdGroup($row['AD_Group_Name'])
                            ->setSuperAdmin($row['Super_Admin']);
                        $levels[] = $level;
                    } catch (AppException $e) {
                        $logger->error($e);
                    }
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
