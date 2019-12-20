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
        $query->where(Schema::DISTRICT_ID, $appID);

        return $query->run();
    }

    public static function getADSettings($districtID, $type) {
        $query = new Query(Schema::ACTIVEDIRECTORY);
        $query->where(Schema::ACTIVEDIRECTORY_DISTRICT_ID, $districtID)
                ->where(Schema::ACTIVEDIRECTORY_TYPE, $type);

        return $query->run()[0];
    }

    public static function getGASettings($districtID, $type) {
        $query = new Query(Schema::GOOGLEAPPS);
        $query->where(Schema::GOOGLEAPPS_DISTRICT_ID, $districtID);

        return $query->run()[0];
    }

    public static function getDistrict($districtID) {

        return Database::get()->query('SELECT * FROM ' . self::TABLE_NAME . ' WHERE ' . Schema::DISTRICT_ID[Schema::COLUMN] . ' = ' . $districtID)[0];
    }

    public static function deleteDistrict($districtID) {
        return Database::get()->query('DELETE FROM ' . self::TABLE_NAME . ' WHERE ' . Schema::DISTRICT_ID[Schema::COLUMN] . ' = ' . $districtID);
    }

    public static function getSchools($districtID) {
        return Database::get()->query('SELECT * FROM ' . School::TABLE_NAME . ' WHERE ' . Schema::SCHOOL_DISTRICT_ID[Schema::COLUMN] . ' = ' . $districtID);
    }

}
