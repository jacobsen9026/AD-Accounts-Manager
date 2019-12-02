<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!function_exists("view")) {

    function view($view) {
        $path = VIEWPATH . DIRECTORY_SEPARATOR . $view . ".php";
        //echo $path;
        if (file_exists($path)) {
            return file_get_contents($path);
        }
    }

}
?>
