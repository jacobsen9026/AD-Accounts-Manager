<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

/**
 * Description of DatabaseInterface
 *
 * @author cjacobsen
 */
abstract class DatabasePost {
    //put your code here

    /**
     *
     * @param type $tableName
     * @param array $post
     */
    public static function setPost($tableName, $id, $post) {
        var_dump($tableName);
        foreach ($post as $label => $value) {
            $query = 'UPDATE ' . $tableName . ' SET ' . $label . ' = "' . $value . '" WHERE ID = ' . $id;
            \system\Database::get()->query($query);
        }
    }

}
