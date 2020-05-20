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
 * Description of District
 *
 * @author cjacobsen
 */
use System\Database;
use app\database\Schema;
use App\Models\District\School;
use App\Models\District\District;
use System\Encryption;

class DistrictDatabase extends DatabaseModel {

    use \System\Traits\DomainTools;

    const TABLE_NAME = "District";

    public static function createDistrict($name) {
        \System\App\AppLogger::get()->debug("Creating new district named: " . $name);
        return Database::get()->query('INSERT INTO ' . self::TABLE_NAME . ' (Name) VALUES ("' . $name . '")');
    }

    /**
     *
     * @param type $districtID
     * @param type $type
     * @return array
     * @deprecated
     *
     */
    public static function getADSettings($districtID, $type = 'Staff') {
        $query = new Query(Schema::ACTIVEDIRECTORY);
        $query->where(Schema::ACTIVEDIRECTORY_DISTRICT_ID, $districtID)
                ->where(Schema::ACTIVEDIRECTORY_TYPE, $type);

        return $query->run()[0];
    }

    /**
     *
     * @param type $districtID
     * @param type $type
     * @return type
     * @deprecated
     */
    public static function getGASettings($districtID, $type) {
        $query = new Query(Schema::GOOGLEAPPS);
        $query->where(Schema::GOOGLEAPPS_DISTRICT_ID, $districtID)
                ->where(Schema::GOOGLEAPPS_TYPE, $type);

        return $query->run()[0];
    }

    /**
     *
     * @param type $districtID
     * @return District
     */
    public static function getDistrict($districtID = 1) {
        $result = self::getDatabaseValue('*', $districtID)[0];

        $district = new District();
        return $district->importFromDatabase($result);
    }

    /**
     *  Gets all districts in the database
     * @param type $districtID
     * @return District
     */
    public static function getDistricts() {
        $result = DistrictDatabase::get();
        $districts = array();
        if (!empty($result)) {
            foreach ($result as $rawDistrict) {
                $district = new District();
                $districts[] = $district->importFromDatabase($rawDistrict);
            }
        }
        return $districts;
    }

    public static function deleteDistrict($districtID) {
        return Database::get()->query('DELETE FROM ' . self::TABLE_NAME . ' WHERE ' . 'ID'[Schema::COLUMN] . ' = ' . $districtID);
    }

    public static function getAD_FQDN($districtID) {
        return self::getDatabaseValue('AD_FQDN', $districtID);
    }

    public static function getAD_BaseDN($districtID) {
        $result = self::getDatabaseValue('AD_BaseDN', $districtID);
        if ($result == "false") {
            $result = self::FQDNtoDN(self::getAD_FQDN($districtID));
        }
        return $result;
    }

    public static function getADUsername($districtID) {

        return self::getDatabaseValue('AD_Username', $districtID);
    }

    public static function getADPassword($districtID) {
        return Encryption::decrypt(self::getDatabaseValue('AD_Password', $districtID));
    }

    /**
     *
     * @param type $districtID
     * @return array \App\Models\District\School
     */
    public static function getSchools($districtID) {
        $districtOU = self::getAD_BaseDN($districtID);

//var_dump($ous);
        foreach (self::getSubOUs($district) as $ou) {
            \System\App\AppLogger::get()->debug($ou);
            if (is_array($ou)) {
                $school = new School();
                $school->importFromAD($ou);
                $schools[] = $school;
            }
//echo $ou["ou"][0] . "<br>" . $ou["distinguishedname"][0] . "<br>";
        }
        return $schools;
    }

    public static function setAD_FQDN($districtID, $fqdn) {
        return self::updateDatabaseValue('AD_FQDN', $fqdn);
    }

    public static function setAbbreviation($districtID, $abbr) {
        return self::updateDatabaseValue('Abbreviation', $abbr);
    }

    public static function setADBaseDN($districtID, $baseDN) {
        return self::updateDatabaseValue('AD_BaseDN', $baseDN);
    }

    public static function setADNetBIOS($districtID, $netBIOS) {
        return self::updateDatabaseValue('AD_NetBIOS', $netBIOS);
    }

    public static function setADUsername($districtID, $username) {

        return self::updateDatabaseValue('AD_Username', $username);
    }

    public static function setADPassword($districtID, $password) {


        if (Encryption::decrypt($password) === false) {
            $result = self::updateDatabaseValue('AD_Password', Encryption::encrypt($password));
        } else {
            $result = self::updateDatabaseValue('AD_Password', $password);
        }
        return $result;
    }

    public static function setADStudentGroup($districtID, $studentGroup) {

        $query = new Query(self::TABLE_NAME, Query::UPDATE, 'AD_Student_Group');
        $query->where('ID', $districtID);
        $query->set('AD_Student_Group', $studentGroup);
        return $query->run();
    }

    public static function setName($districtID, $name) {
        return self::updateDatabaseValue('Name', $name);
    }

    public static function setADStaffGroup($districtID, $staffGroup) {

        $query = new Query(self::TABLE_NAME, Query::UPDATE, 'AD_Staff_Group');
        $query->where('ID', $districtID);
        $query->set('AD_Staff_Group', $staffGroup);
        return $query->run();
    }

    public static function saveSettings(array $postData) {
        return false;
    }

    public static function getUseSSL() {
        return true;
    }

}
