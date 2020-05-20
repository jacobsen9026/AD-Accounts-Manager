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

use App\Controllers\Api\APIController;
use App\Controllers\Settings\District as DistrictController;
use System\Post;
use App\Models\View\PermissionMapPrinter;
use App\Models\Database\PermissionMapDatabase;
use System\Traits\DomainTools;

class District extends APIController
{
    use DomainTools;

//put your code here

    /**
     *
     * @var DistrictController;
     */
    private $controller;

    public function indexPost($districtID = 1)
    {

        $this->controller = new DistrictController($this->app);
        $this->controller->editPost(1);
        $this->district = \App\Models\Database\DistrictDatabase::getDistrict($districtID);
        $this->distictID = $this->district->getId();

        return $this->returnHTML($this->view('settings/district/show'));
    }

    /**
     * Processes Posted data for permission changes via API
     *
     * @param type $districtID
     *
     * @return type
     */
    public function permissionsPost($districtID = 1)
    {
        $this->district = \App\Models\Database\DistrictDatabase::getDistrict($districtID);
        $this->distictID = $this->district->getId();
        $this->controller = new DistrictController($this->app);
//$controller->permissionsPost($districtID);

        $action = Post::get("action");
        switch ($action) {


            case 'addPrivilege':
                $this->controller->addDistrictPrivilegeLevel();
                return $this->returnHTML($this->view('settings/district/permissions'));

                break;
            case 'deletePrivilege':
                $this->controller->deletePrivilegeLevel();
                return $this->returnHTML($this->view('settings/district/permissions'));

                break;
            case 'updatePrivilege':
                $this->controller->updatePrivilege(Post::get("privilegeID"));
                return $this->returnHTML($this->view('settings/district/permissions'));

                break;
            case 'modifyPermission':
                return $this->modifyPermission();

                break;
            case 'addOUPermission':
                return $this->addOUPermission();
                break;
            case 'removePermission':
                return $this->removePermission();

                break;
            case 'getOULevelPermissions':

                //return $this->returnHTML("berms test");
                $response = ($this->view('settings/district/permissions/ouLevelPermissions'));
                //var_export($response);
                //var_export($this->returnHTML($response));
                return ($this->returnHTML($response));
                break;
            case 'showOULevelPermissions':


                return ($this->returnHTML($this->showOUPermissions(Post::get('ou'))));
                break;
            case 'getDistrictLevelPermissions':
                $this->district = \App\Models\Database\DistrictDatabase::getDistrict($districtID);
                return $this->returnHTML($this->view('settings/district/permissions/districtLevelPermissions'));
                break;
            case 'addDistrictPermission':
                // $this->district = \App\Models\Database\DistrictDatabase::getDistrict($districtID);
                $this->controller->addOUPermission();
                return $this->returnHTML($this->view('settings/district/permissions/districtLevelPermissions'));
                break;
            case 'getManagePrivilegeLevels':

                return $this->returnHTML($this->view('settings/district/permissions/privilegeLevels'));
                break;
//exit;
            default:
                break;
        }
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

    /**
     *
     * @return type
     */
    private function modifyPermission()
    {
        $ou = Post::get('id');
        if ($ou != '' and $ou != null) {
            $permission = new \App\Models\User\Permission();
            $permission->importFromDatabase(PermissionMapDatabase::getPermissionById($ou));
            $dn = $permission->getOu();
            $this->controller->modifyPermission();
            $response = PermissionMapPrinter::printOUPermissions($dn);
            $response .= PermissionMapPrinter::printAddOUPermission($dn);

            $data = $this->returnHTML($response);
            $data["ouPermNav"] = $this->getOuNavigation($ou);
            return $data;
        }
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

    private function removePermission()
    {
        $id = Post::get('id');
        $permission = new \App\Models\User\Permission();
        $permission->importFromDatabase(PermissionMapDatabase::getPermissionById($id));
        $dn = $permission->getOu();
        $this->controller->removeOUPermission();
        $response = PermissionMapPrinter::printOUPermissions($dn);
        $response .= PermissionMapPrinter::printAddOUPermission($dn);

        return $this->returnHTML($response);
    }

}
