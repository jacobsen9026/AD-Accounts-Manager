<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\district;

/**
 * Description of GradeDefinition
 *
 * @author cjacobsen
 */
class GradeDefinition {

    //put your code here

    const TABLE_NAME = 'GradeDefinition';

    public static function getDistrictID($schoolID) {
        return(\system\Database::get()->query('SELECT ' . Schema::SCHOOL_DISTRICT_ID[Schema::COLUMN] . ' From ' . self::TABLE_NAME . ' Where ID=' . $schoolID)[0][Schema::SCHOOL_DISTRICT_ID]);
    }

}
