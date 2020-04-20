<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\district;

/**
 * Description of District
 *
 * @author cjacobsen
 */
use system\Database;
use app\database\Schema;
use app\models\Query;

class DistrictDatabase {

    const TABLE_NAME = "District";

//put your code here

    /**
     * Breaks up a FQDN into a DistiguishedName
     *
     * EG: contoso.com -> dc=contoso,dc=com
     *
     * @param type $fqdn
     * @return string
     */
    public static function parseBaseDNFromFQDN($fqdn) {
        $baseDN = '';
        $afterFirst = false;
        foreach (explode(".", $fqdn) as $part) {
            if ($afterFirst) {
                $baseDN .= ',';
            }
            $baseDN .= 'DC=' . $part;
            $afterFirst = true;
        }
        return $baseDN;
    }

    public static function createDistrict($name) {
        \system\app\AppLogger::get()->debug("Creating new district named: " . $name);
        return Database::get()->query('INSERT INTO ' . self::TABLE_NAME . ' (Name) VALUES ("' . $name . '")');
    }

    public static function getDistricts() {
        $appID = \system\app\App::get()->getID();
        $query = new Query(self::TABLE_NAME);
        return $query->run();
    }

    /**
     *
     * @param type $districtID
     * @param type $type
     * @return array
     */
    public static function getADSettings($districtID, $type = 'Staff') {
        $query = new Query(Schema::ACTIVEDIRECTORY);
        $query->where(Schema::ACTIVEDIRECTORY_DISTRICT_ID, $districtID)
                ->where(Schema::ACTIVEDIRECTORY_TYPE, $type);

        return $query->run()[0];
    }

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
        $query = new Query(self::TABLE_NAME);
        $query->where(Schema::DISTRICT_ID, $districtID);

        $result = $query->run()[0];
        $district = new District();
        return $district->importFromAD($result);
        //return Database::get()->query('SELECT * FROM ' . self::TABLE_NAME . ' WHERE ' . Schema::DISTRICT_ID[Schema::COLUMN] . ' = ' . $districtID)[0];
    }

    public static function deleteDistrict($districtID) {
        return Database::get()->query('DELETE FROM ' . self::TABLE_NAME . ' WHERE ' . Schema::DISTRICT_ID[Schema::COLUMN] . ' = ' . $districtID);
    }

    public static function getAD_FQDN($districtID) {
        $query = new Query(self::TABLE_NAME, Query::SELECT, Schema::DISTRICT_AD_FQDN[Schema::COLUMN]);
        $query->where(Schema::DISTRICT_ID, $districtID);
        return $query->run();
    }

    public static function getAD_BaseDN($districtID) {
        $query = new Query(self::TABLE_NAME, Query::SELECT, Schema::DISTRICT_AD_BASEDN[Schema::COLUMN]);
        $query->where(Schema::DISTRICT_ID, $districtID);
        $result = $query->run();
        if ($result == "false") {
            return self::parseBaseDNFromFQDN(self::getAD_FQDN($districtID));
        }
        return $result;
    }

    public static function getADUsername($districtID) {
        $query = new Query(self::TABLE_NAME, Query::SELECT, Schema::DISTRICT_AD_USERNAME[Schema::COLUMN]);
        $query->where(Schema::DISTRICT_ID, $districtID);
        return $query->run();
    }

    public static function getADPassword($districtID) {
        $query = new Query(self::TABLE_NAME, Query::SELECT, Schema::DISTRICT_AD_PASSWORD[Schema::COLUMN]);
        $query->where(Schema::DISTRICT_ID, $districtID);
        return $query->run();
    }

    /**
     *
     * @param type $districtID
     * @return type
     * @deprecated since version number
     */
    public static function getSchools($districtID) {
        $query = new Query(SchoolDatabase::TABLE_NAME);
        $query->where(Schema::SCHOOL_DISTRICT_ID, $districtID);

        return $query->run();
        return Database::get()->query('SELECT * FROM ' . SchoolDatabase::TABLE_NAME . ' WHERE ' . Schema::SCHOOL_DISTRICT_ID[Schema::COLUMN] . ' = ' . $districtID);
    }

    /**
     *
     * @param type $districtID
     * @return array \app\models\district\School
     */
    public static function getSchools2($districtID) {
        $districtOU = self::getAD_BaseDN($districtID);
        $ad = \app\api\AD::get();
        $ous = $ad->getSubOUs($districtOU);
        //var_dump($ous);
        foreach ($ous as $ou) {
            \system\app\AppLogger::get()->debug($ou);
            if (is_array($ou)) {
                $school = new School();
                $school->importFromAD($ou);
                $schools[] = $school;
            }
            //echo $ou["ou"][0] . "<br>" . $ou["distinguishedname"][0] . "<br>";
        }
        return $schools;
    }

}
