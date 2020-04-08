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

class District {

    const TABLE_NAME = "District";

//put your code here




    public static function createDistrict($name) {
        \system\app\AppLogger::get()->debug("Creating new district named: " . $name);
        return Database::get()->query('INSERT INTO ' . self::TABLE_NAME . ' (Name,App_ID) VALUES ("' . $name . '","' . \system\app\App::getID() . '")');
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

    public static function getDistrict($districtID) {
        $query = new Query(self::TABLE_NAME);
        $query->where(Schema::DISTRICT_ID, $districtID);

        return $query->run()[0];
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

    public static function getSchools($districtID) {
        $query = new Query(School::TABLE_NAME);
        $query->where(Schema::SCHOOL_DISTRICT_ID, $districtID);

        return $query->run();
        return Database::get()->query('SELECT * FROM ' . School::TABLE_NAME . ' WHERE ' . Schema::SCHOOL_DISTRICT_ID[Schema::COLUMN] . ' = ' . $districtID);
    }

}
