<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists('enablePHPErrors')) {

    function enablePHPErrors() {
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
    }

}
if (!function_exists('disablePHPErrors')) {

    function disablePHPErrors() {
        error_reporting(0);
        ini_set('display_errors', FALSE);
        ini_set('display_startup_errors', FALSE);
    }

}
?>
