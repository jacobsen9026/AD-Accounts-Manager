<?php

namespace Tests\Unit;

class BaseTest extends \Codeception\Test\Unit
{

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            if (constant('ROOTPATH') === null) {
                define('ROOTPATH', getcwd());
            }
        } catch (\Exception $ex) {

        }
        require_once("system/Config.php");
    }


}