<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

/**
 * Description of Model
 *
 * @author cjacobsen
 */
class Model {
    //put your code here

    /**
     *
     * @param type $schema
     * @return string
     */
    public static function getTableFromSchema($schema) {
        return $schema['table'];
    }

    /**
     *
     * @param type $schema
     * @return string
     */
    public static function getColumnFromSchema($schema) {
        return $schema['column'];
    }

}
