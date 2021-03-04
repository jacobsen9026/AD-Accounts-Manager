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
 */

namespace App\Controllers\Settings;

/**
 * Description of District
 *
 * @author cjacobsen
 */

use App\App\App;
use App\Models\Audit\Action\Settings\ChangeSettingAuditAction;
use App\Models\Database\DomainDatabase;
use App\Models\User\Permission;
use App\Models\User\PrivilegeLevel;
use System\Post;
use App\Models\Database\PermissionMapDatabase;
use App\Models\Database\PrivilegeLevelDatabase;
use System\App\AppException;

class Domain extends SettingsController
{

    function __construct(App $app)
    {

        parent::__construct($app);

        $this->layout = 'default_blank';
    }

//put your code here
    public function index()
    {
        return $this->redirect('/settings/domain/edit');

    }

    public function edit($districtID = 1)
    {

        return $this->show($districtID);

    }

    public function show($districtID = 1)
    {
        $this->districtID = $districtID;
        $this->domain = DomainDatabase::getDomain($districtID);
        return $this->view('settings/domain/show');
    }

    public function permissions($districtID = 1)
    {
        $this->domain = DomainDatabase::getDomain($districtID);
        return $this->view('settings/domain/permissions');
    }

    public function permissionsPost($districtID = 1)
    {
        $post = Post::getAll();
        switch (Post::get('action')) {
            case 'updateDistrict':

                $this->updateDistrict($post);
                break;
            case 'deletePrivilege':
                $this->deletePrivilegeLevel();
                break;

            case 'addDistrictPermission':
                $this->addDistrictPermission($districtID);

                break;
            case 'addOUPermission':
                $this->addOUPermission();

                break;
            case 'modifyPermission':
                $this->modifyPermission();

                break;
            case 'updatePrivilege':
                $id = Post::get("privilegeID");
                $this->updatePrivilege($id);
                break;
            case 'addPrivilege':
                $this->addDistrictPrivilegeLevel();
                break;
            case 'removePermission':
                $this->removeOUPermission();
                break;
            default:
                break;
        }

        $this->redirect('/settings/domain/permissions');
    }

    private function updateDistrict($post = null)
    {
//var_dump($post);
        $name = Post::get('name');
        $abbr = Post::get('abbr');
        $netBios = Post::get('adNetBIOS');
        $fqdn = Post::get('adFQDN');
        $baseDN = Post::get('adBaseDN');
        $adPassword = Post::get('adPassword');
        $adUsername = Post::get('adUsername');
        $useTLS = Post::get('useTLS');
        //$this->audit(new ChangeSettingAuditAction("District Name", $this->domain->getName(), $name));
        //$this->audit(new ChangeSettingAuditAction("District Name", $this->domain->getAbbr(), $abbr));
        $this->audit(new ChangeSettingAuditAction("District Name", $this->domain->getAdNetBIOS(), $netBios));
        $this->audit(new ChangeSettingAuditAction("District Name", $this->domain->getAdFQDN(), $fqdn));
        $this->audit(new ChangeSettingAuditAction("District Name", $this->domain->getAdBaseDN(), $baseDN));
        $this->audit(new ChangeSettingAuditAction("District Name", $this->domain->getAdPassword(), $adPassword));
        $this->audit(new ChangeSettingAuditAction("District Name", $this->domain->getAdUsername(), $adUsername));
        $this->audit(new ChangeSettingAuditAction("District Name", $this->domain->getUseTLS(), $useTLS));


        $this->logger->info("Updating domain");
        //$this->domain->setName($name);
        //$this->domain->setAbbr($abbr)
        $this->domain->setAdFQDN($fqdn)
            ->setAdNetBIOS($netBios)
            ->setAdBaseDN($baseDN)
            ->setAdPassword($adPassword)
            ->setAdUsername($adUsername)
            ->setUseTLS($useTLS);


        $this->domain->saveToDB();

        return true;
    }

    public function deletePrivilegeLevel()
    {
        $id = Post::get('privilegeID');
        $this->logger->info('Removing Privilege ' . $id);

        $this->audit(new ChangeSettingAuditAction("Delete Priviledge Level", $id, null));
        return PrivilegeLevelDatabase::removePrivilegeLevel($id);
    }

    private function addDistrictPermission($districtID = 1)
    {


        $this->audit(new ChangeSettingAuditAction("Add Directory Permission", null, null));
        $this->addOUPermission();


//return true;
    }

    public function addOUPermission()
    {
        $ou = Post::get('ou');
        if ($ou != '' and $ou != null) {
            $permission = new Permission();
//var_dump(Post::get());
            $permission->setPrivilegeID(Post::get("privilegeID"))
                ->setOu($ou)
                ->setUserPermissionLevel(Post::get("userPermissionType"))
                ->setGroupPermissionLevel(Post::get("groupPermissionType"));
            PermissionMapDatabase::addPermission($permission);
        } else {
            throw new AppException('Permission is missing OU', AppException::PERMISSION_MISSING_OU);
        }

//return true;
    }

    public function modifyPermission()
    {
        $id = Post::get('id');
//var_dump(Post::get());
        if ($id != '' and $id != null) {
            $permission = new Permission();
            $permission->loadFromDatabaseResponse(PermissionMapDatabase::getPermissionById($id));
//var_dump(Post::get());
            $permission->setPrivilegeID(Post::get("privilegeID"))
                ->setId($id)
                ->setUserPermissionLevel(Post::get("userPermissionType"))
                ->setGroupPermissionLevel(Post::get("groupPermissionType"));
            $permission->updateInDatabase();
        } else {
            throw new AppException('Permission is missing ID', AppException::PERMISSION_MISSING_ID);
        }

//return true;
    }

    public function updatePrivilege($privilegeID)
    {
        $privilege = new PrivilegeLevel();
        $privilege->importFromDatabase(PrivilegeLevelDatabase::get($privilegeID));

        $privilege->setAdGroup(Post::get('groupName'))
            ->setSuperAdmin(Post::get('superAdmin'));
        $privilege->saveToDatabase();
    }

    public function addDistrictPrivilegeLevel($districtID = 1)
    {
        if (Post::get("ldapGroupName")) {
            $groupName = Post::get("ldapGroupName");
            $this->audit(new ChangeSettingAuditAction("Add Privilege Level", null, $groupName));
            return PrivilegeLevelDatabase::addPrivilegeLevel(Post::get("ldapGroupName"), $districtID);
        }
    }

    public function removeOUPermission()
    {
        $id = Post::get('id');
        $permission = new Permission();
        $permission->loadFromDBByID($id);
        if ($permission->remove()) {
            return true;
        }
        return false;


    }

    public function createPost()
    {
        $post = Post::getAll();
        DomainDatabase::createDomain($post['name']);
        $this->redirect('/settings/domain/edit');
    }

    public function editPost($districtID = 1)
    {
        $this->domain = DomainDatabase::getDomain($districtID);
        $post = Post::getAll();
        //$this->preProcessDistrictID($districtID);
        switch (Post::get('action')) {
            case 'updateDistrict':
                $this->updateDistrict($post);
                return "Settings Saved";

            case 'remove':
                $id = Post::get('privilegeID');
                $this->logger->info('Removing Privilege ' . $id);
                PrivilegeLevelDatabase::removePrivilegeLevel($id);
                break;
            case 'addPermission':
                $this->addDistrictPermission($districtID);

                break;
            case 'updatePrivilege':
                $id = Post::get("privilegeID");
                $this->updatePrivilege($id);
                break;
            default:
                if (Post::get("ldapGroupName")) {
                    PrivilegeLevelDatabase::addPrivilegeLevel(Post::get("ldapGroupName"), $districtID);
                }
                break;
        }

//        $this->redirect('/settings/domain/edit/');
    }


}
