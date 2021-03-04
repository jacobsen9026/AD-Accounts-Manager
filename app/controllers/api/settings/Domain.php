<?php

/*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
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
 *//*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
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

namespace App\Controllers\Api\settings;

/**
 * Description of Application
 *
 * @author cjacobsen
 */

use App\Api\AD;
use App\App\App;
use App\Controllers\Api\APIController;
use App\Controllers\Settings\Domain as DomainController;
use App\Models\Database\DomainDatabase;
use App\Models\User\Permission;
use System\Post;
use App\Models\View\PermissionMapPrinter;
use App\Models\Database\PermissionMapDatabase;
use System\Traits\DomainTools;

class Domain extends APIController
{
    use DomainTools;

//put your code here

    /**
     *
     * @var DomainController;
     */
    private $controller;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->domain = DomainDatabase::getDomain();
        $this->domainID = $this->domain->getId();

        $this->controller = new DomainController($this->app);
    }

    public function indexPost($domainID = 1)
    {

        $this->controller->editPost(1);
        // $this->domain = DomainDatabase::getDomain($domainID);
        // $this->domainID = $this->domain->getId();

        return $this->returnHTML($this->view('settings/domain/show') . $this->settingsSavedToast());
    }

    /**
     * Processes Posted data for permission changes via API
     *
     * @param int $districtID
     *
     * @return array
     */
    public function permissionsPost($districtID = 1)
    {
//$controller->permissionsPost($districtID);

        $action = Post::get("action");
        switch ($action) {


            case 'addPrivilege':
                $this->controller->addDistrictPrivilegeLevel();
                return $this->returnHTML($this->view('settings/domain/permissions') . $this->settingsSavedToast());


            case 'deletePrivilege':
                $this->controller->deletePrivilegeLevel();
                $output = $this->returnHTML($this->view('settings/domain/permissions') . $this->settingsSavedToast());
                //var_dump($output);
                return $output;


            case 'updatePrivilege':
                $this->controller->updatePrivilege(Post::get("privilegeID"));
                return $this->returnHTML($this->view('settings/domain/permissions') . $this->settingsSavedToast());


            case 'modifyPermission':
                return $this->modifyPermission();


            case 'addOUPermission':
                return $this->addOUPermission();

            case 'removePermission':
                return $this->removePermission();


            case 'getOULevelPermissions':

                $response = ($this->view('settings/domain/permissions/ouLevelPermissions'));

                return ($this->returnHTML($response));

            case 'showOULevelPermissions':


                return ($this->returnHTML($this->showOUPermissions(Post::get('ou'))));

            case 'getApplicationLevelPermissions':
                $this->domain = DomainDatabase::getDomain($districtID);
                return $this->returnHTML($this->view('settings/domain/permissions/applicationLevelPermissions'));

            case 'addDistrictPermission':
                // $this->domain = \App\Models\Database\DistrictDatabase::getDistrict($districtID);
                $this->controller->addOUPermission();
                return $this->returnHTML($this->view('settings/domain/permissions/applicationLevelPermissions') . $this->settingsSavedToast());

            case 'getManagePrivilegeLevels':

                return $this->returnHTML($this->view('settings/domain/permissions/privilegeLevels'));

            default:
                break;
        }
    }

    /**
     *
     * @return array
     */
    private function modifyPermission()
    {
        $ou = Post::get('id');
        if ($ou != '' and $ou != null) {
            $permission = new Permission();
            $permission->loadFromDatabaseResponse(PermissionMapDatabase::getPermissionById($ou));
            $dn = $permission->getOu();
            $this->controller->modifyPermission();
            $response = PermissionMapPrinter::printOUPermissions($dn);
            //$response .= PermissionMapPrinter::printAddOUPermission($dn);

            $data = $this->returnHTML($response . $this->settingsSavedToast());
            $data["ouPermNav"] = $this->getOuNavigation($ou);
            return $data;
        }
    }

    private function getOuNavigation($ou)
    {
        $tree = $this->getOuTree($ou);

        $data = [];
        $count = PermissionMapDatabase::getOUPermissionsCount($ou);
        $subCount = PermissionMapDatabase::getSubOUPermissionsCount($ou);
        $data["ou"] = $ou;
        $data["count"] = $count;
        $data["subCount"] = $subCount;
        $return[] = $data;
        $data = [];
        foreach ($tree as $trunk) {
            $data["ou"] = $trunk;
            $data["subCount"] = "1";
            if ($trunk !== $ou) {
                $return[] = $data;
            }
        }
        return $return;
    }

    private function addOUPermission()
    {
        $ou = Post::get("ou");
        $this->controller->addOUPermission();
//var_dump("xhr");
        $data = $this->returnHTML($this->showOUPermissions($ou));
        $data["ouPermNav"] = $this->getOuNavigation($ou);
        return $data;
    }

    /**
     * @param $ou
     *
     * @return string
     */
    private function showOUPermissions($ou)
    {
        $response = PermissionMapPrinter::printOUPermissions($ou);
        return $response;
    }

    private function removePermission()
    {
        $id = Post::get('id');
        $permission = new Permission();
        $permission->loadFromDatabaseResponse(PermissionMapDatabase::getPermissionById($id));
        $dn = $permission->getOu();
        $this->controller->removeOUPermission();
        $response = PermissionMapPrinter::printOUPermissions($dn);
        //$response .= PermissionMapPrinter::printAddOUPermission($dn);

        return $this->returnHTML($response);
    }

    public function testADPermissionsPost($districtID = 1)
    {
        $csrfCheck = Post::get();
        if ($this->user->superAdmin) {
            $testResult = false;
            if ($this->user->superAdmin) {
                $ad = new AD($districtID);
                $testResult = $ad->createTestUser();
            }
            if ($testResult == 'true') {
                return $this->returnHTML('<h1><i class="fas fa-check-circle text-success"></i></h1>');
            } else {
                return $this->returnHTML('<h1><i class="fas fa-times-circle text-danger"></i></h1>' . $testResult);
            }
        }
    }

}
