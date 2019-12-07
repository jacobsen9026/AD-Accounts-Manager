<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace system\app;

/**
 * Description of Config
 *
 * @author cjacobsen
 */
use system\Parser;

class CoreConfig extends Parser {

    //put your code here




    function __construct(array $keyValuePairs = null) {
        if ($keyValuePairs != null) {
            foreach ($keyValuePairs as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    public function getSettings() {
        return get_object_vars($this);
    }

}
?>

