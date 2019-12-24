<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\district;

/**
 * Description of Table
 *
 * @author cjacobsen
 */
use app\database\Schema;

trait Table {

//put your code here
    private static function getSchemaColumn($schema) {
        $schemaClass = new \ReflectionClass('app\database\Schema');
        //\system\app\AppLogger::get()->debug($schema);

        $constant = $schemaClass->getConstant($schema);
        //\system\app\AppLogger::get()->debug($constant);
        $column = $constant[Schema::COLUMN];
        //\system\app\AppLogger::get()->debug($column);
        return $column;
    }

}
