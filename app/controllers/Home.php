<?php

/*
 * The MIT License
 *
 * Copyright 2019 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace App\Controllers;

/**
 * Description of Home
 *
 * @author cjacobsen
 */

use App\Models\Database\AppDatabase;
use App\Models\Database\DistrictDatabase;
use App\Models\User\PermissionLevel;
use App\Models\User\PermissionHandler;

class Home extends Controller
{

    public function index()
    {
        $this->motd = AppDatabase::getMOTD();
        $this->applicationName = AppDatabase::getAppName();
//echo "<br/><br/><br/><br/><br/><br/>";
//var_dump($this->user);
        $testOUs = ['OU=Staff,OU=Admin Building,OU=SAM Test OU,DC=sandbox,DC=local',
            "OU=Admin Building,OU=SAM Test OU,DC=sandbox,DC=local",
            "OU=Staff,OU=Admin Building,OU=SAM Test OU,DC=sandbox,DC=local",
            "OU=School 2,OU=SAM Test OU,DC=sandbox,DC=local",
            "OU=Instructional Services,OU=Staff,OU=Admin Building,OU=SAM Test OU,DC=sandbox,DC=local",
            "OU=Grade 5,OU=Students,OU=School 2,OU=SAM Test OU,DC=sandbox,DC=local"];
//$testOUs = ["OU=School 2,OU=SAM Test OU,DC=sandbox,DC=local"];
        /*
          foreach ($testOUs as $ou) {
          echo "Permission Test For Group Read<br/>";
          echo $ou;
          var_dump(PermissionHandler::hasPermission($ou, PermissionLevel::GROUPS, 1));
          }

         */


        return $this->view('homepage');
    }

    public function indexPost()
    {
        $this->index();
    }

    public function show403()
    {


        $this->motd = AppDatabase::getMOTD();
        $this->applicationName = AppDatabase::getAppName();
        return $this->view('errors/403');
    }

}

?>

