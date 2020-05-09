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

namespace app\models\view;

/**
 * Description of PrivilegeLevelPrinter
 *
 * @author cjacobsen
 */
use app\models\database\PrivilegeLevelDatabase;
use app\models\database\PermissionMapDatabase;
use app\models\user\PermissionLevel;
use system\app\forms\Form;
use system\app\forms\FormText;
use system\app\forms\FormDropdown;
use system\app\forms\FormButton;
use system\app\forms\FormDropdownOption;
use app\models\user\Permission;

abstract class PermissionMapPrinter extends ViewModel {

    private static $privilegeLevels;

    public static function printDistrictPermissions($districtID) {

        $privLevels = PrivilegeLevelDatabase::get();

        //var_dump($privLevels);
        $district = \app\models\database\DistrictDatabase::getDistrict($districtID);
        $perms = PermissionMapDatabase::getPermissionsByOU($district->getAdBaseDN());

        if ($perms !== false) {
            $output = '';
            foreach ($perms as $perm) {
                $permission = new Permission();
                $permission->importFromDatabase($perm);
                $output .= self::printModifyPermission($permission);

                $form = new Form('/settings/districts/edit/' . $districtID);
            }
            return $output;
        }
    }

    private static function printOUPermissions($ou) {
        $permissions = PermissionMapDatabase::getPermissionsByOU($ou);
        //var_dump($permissions);
        // echo"<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>";
        //var_dump($permissions);
        $output = '';
        if ($permissions != false) {
            foreach ($permissions as $permission) {
                $perm = new Permission();
                $perm->importFromDatabase($permission);
                $output .= PermissionMapPrinter::printModifyPermission($perm);
            }
        }
        return $output;
    }

    public static function printPrivilegeLevels($districtID) {

        $levels = self::getPrivilegeLevels();
        //var_dump($levels);
        $output = '';
        if ($levels !== false) {
            foreach ($levels as $level) {
                $form = new Form('/settings/district/permissions/' . $districtID);

                $action = new FormText('', '', 'action', 'updatePrivilege');
                $action->hidden();

                $id = new FormText('', '', 'privilegeID', $level->getId());
                $id->hidden();
                $groupName = new FormText('', 'Group Name', 'groupName', $level->getAdGroup());
                $groupName->autoCompleteDomainGroupName();
                $updateButton = new FormButton('Update');

                $updateButton->setTheme('success');



                $modalForm = new Form();
                $deleteAction = new FormText('/settings/district/permissions/' . $districtID, '', 'action', 'deletePrivilege');
                $deleteAction->hidden();
                $reallyDeleteButton = new FormButton("Confirm");
                $reallyDeleteButton->setTheme('danger');
                //->addClientRequest('/settings/district/permissions/' . $districtID, ["action" => "remove", "privilegeID" => $level->getId()]);
                $modalForm->addElementToNewRow($deleteAction)
                        ->addElementToNewRow($reallyDeleteButton)
                        ->addElementToNewRow($id);



                $deleteButton = new FormButton('Delete');
                $deleteButton->setTheme('danger')
                        ->setId('Delete_' . $level->getId())
                        ->addModal("Delete " . $level->getAdGroup(), "Are you sure you really want to delete this privilege level?<br/><br/>This will remove all mapped permissions. There is no undo.<br/>" . $modalForm->print(), 'danger');
                $form->addElementToNewRow($groupName)
                        ->addElementToCurrentRow($action)
                        ->addElementToCurrentRow($id)
                        ->addElementToCurrentRow($updateButton)
                        ->addElementToCurrentRow($deleteButton);

                $output .= $form->print();
            }
            return $output;
        }
        return "Nothing created yet";
    }

    public static function printAddPrivilegeLevelForm($districtID) {
        $form = new Form('/settings/district/permissions/' . $districtID, 'addPrivilegeLevel');
        $newGroupName = new FormText('', 'LDAP group name', 'ldapGroupName');
        $newGroupName->autoCompleteDomainGroupName();
        $action = new FormText('', '', 'action', 'addPrivilege');
        $action->hidden();
        $addButton = new FormButton('Add');
        $addButton->addAJAXRequest('/api/settings/district/permissions/' . $districtID, 'managePrivilegeLevels', $form);
        $form->addElementToNewRow($newGroupName)
                ->addElementToCurrentRow($addButton)
                ->addElementToCurrentRow($action);

        return $form->print();
    }

    public static function printRemovePrivilegeLevelForm($districtID) {
        $form = new Form('/settings/district/permissions/' . $districtID);
        $action = new FormText(null, null, 'action', 'remove');
        $action->hidden();
        $groups = self::buildPrivilegeLevelDropdown();
        $removeButton = new FormButton('Delete');
        $removeButton->setTheme('danger');
        $form->addElementToNewRow($groups)
                ->addElementToCurrentRow($action)
                ->addElementToNewRow($removeButton);
        return $form->print();
    }

    public static function printAddDistrictPermission($districtID) {
        $district = \app\models\database\DistrictDatabase::getDistrict($districtID);
        $ou = new FormText('', '', 'ou', $district->getAdBaseDN());
        $ou->hidden();
        return self::buildAddPermissionForm('/settings/district/permissions/' . $districtID, 'addDistrictPermission')->addElementToNewRow($ou)->print();
    }

    private static function buildAddPermissionForm($destinationURL, $action) {
        $form = new Form($destinationURL);
        $privilegeID = self::buildPrivilegeLevelDropdown();
        $action = new FormText('', '', 'action', $action);
        $action->hidden();
        $addButton = new FormButton('Add', 'small');
        $form->addElementToNewRow($privilegeID)
                ->addElementToCurrentRow($action)
                ->addElementToCurrentRow(self::buildUserPermissionDropdown())
                ->addElementToCurrentRow(self::buildGroupPermissionDropdown())
                ->addElementToCurrentRow($addButton);

        return $form;
    }

    public static function printOUPermissionTree($ou) {
        $ad = \app\api\AD::get();
        $tree = $ad->getAllSubOUs($ou);
        return self::buildPermissionTree($tree);
    }

    private static function buildPermissionTree($tree) {
        //var_dump($tree);
        $output = '';

        foreach ($tree as $name => $branch) {
            if (is_array($branch)) {
                //$output .= $name . " V<br/>";
                //$output .= self::buildPermissionTree($branch);
                $output .= self::printBranchOU($name, $branch);
            } else {

                $output .= self::printLeafOU($branch);
                //$output .= $branch . "<br/>";
            }
        }
        return $output;
    }

    private static function printBranchOU($dn, $childrenArray) {
        $output = '<div class=" row border my-3 p-3 rounded">'
                . '<div class="col-11">'
                . '<div class="row ">'
                . '<div class="col text-light bg-secondary rounded-top">' . self::leftOU($dn) . ' (' . $dn . ')</div>'
                . '</div>'
                . '<div class="row">'
                . '<div class="col shadow-sm bg-light p-0 mb-3 ">'
                . self::printOUPermissions($dn)
                . self::printAddOUPermission($dn)
                . '</div>'
                . '</div>'
                . '<div class="row"> '
                . '<div id="' . self::cleanOU($dn) . '" class="collapse container mx-auto my-0 ">'
                . self::buildPermissionTree($childrenArray)
                . '</div>'
                . '</div>'
                . '</div>'
                . '<div class="col-1 h-100">'
                . '<button class="btn btn-secondary" data-text-alt="-" type="button" data-toggle="collapse" data-target="#' . self::cleanOU($dn) . '" aria-expanded="false">+</button>'
                . '</div>'
                . '</div>';
        //var_dump($output);
        return $output;
    }

    private static function printLeafOU($dn) {
        $output = '<div class = "col">'
                . '<div class = "row">'
                . '<div class = "col text-light bg-secondary rounded-top">' . self::leftOU($dn) . ' ( ' . $dn . ' ) </div>'
                . '</div>'
                . ' <div class = "row">'
                . ' <div class = "col shadow-sm bg-light p-0 mb-3">'
                . self::printOUPermissions($dn)
                . self::printAddOUPermission($dn)
                . ' </div>'
                . ' </div>'
                . ' </div>';
        return $output;
    }

    private static function cleanOU($ou) {
        $search = [' ', 'OU=', ',', 'DC='];
        return str_replace($search, '', $ou);
    }

    private static function leftOU($dn) {
        return explode(',', str_replace('OU=', '', $dn))[0];
    }

    private static function printAddOUPermission($ou) {
        $ouText = new FormText('', '', 'ou', $ou);

        $ouText->hidden();
        $existingPrivileges = PermissionMapDatabase::getPrivilegeIDsByOU($ou);
        //var_dump($ou);
        //var_dump($existingPrivileges);
        $classes = "form-text text-dark mt-0";
        $form = new Form();
        if ($privilegeID = self::buildPrivilegeLevelDropdown(null, $existingPrivileges)) {
            $privilegeID->setSubLabelClasses($classes);
            $action = new FormText('', '', 'action', 'addOUPermission');
            $action->hidden();
            $addButton = new FormButton('Add', 'small');
            $addButton->setTheme('secondary');
            $form->addElementToNewRow($privilegeID)
                    ->addElementToCurrentRow($action)
                    ->addElementToCurrentRow($ouText)
                    ->addElementToCurrentRow(self::buildUserPermissionDropdown()->setSubLabelClasses($classes))
                    ->addElementToCurrentRow(self::buildGroupPermissionDropdown()->setSubLabelClasses($classes))
                    ->addElementToCurrentRow($addButton);

            return "<div class='bg-light rounded-lg px-3 pt-1'>" . $form->print() . "</div>";
        }
    }

    public static function printModifyPermission(Permission $permission) {

        $form = new Form();
        $privilegeLevel = self::buildPrivilegeLevelDropdown($permission->getPrivilegeID());
        $action = new FormText('', '', 'action', 'modifyPermission');
        $action->hidden();
        $permissionID = new FormText('', '', 'id', $permission->getId());
        $permissionID->hidden();
        $saveButton = new FormButton('Save', 'small');




        $modalForm = new Form('/settings/district/permissions');

        $id = new FormText('', '', 'id', $permission->getId());
        $id->hidden();
        $removeAction = new FormText('', '', 'action', 'removePermission');
        $removeAction->hidden();
        $reallyDeleteButton = new FormButton("Confirm");
        $reallyDeleteButton->setTheme('warning');



        $modalForm->addElementToNewRow($removeAction)
                ->addElementToNewRow($reallyDeleteButton)
                ->addElementToNewRow($id);

        $ouPath = '';
        $path = explode("OU=", str_replace(',', '', $permission->getOu()));
        foreach (array_reverse($path) as $part) {
            if ($part != '' and strpos($part, 'DC=') == false) {
                $ouPath .= '/' . $part;
            }
        }
        $removeButton = new FormButton('Remove');
        $removeButton->setTheme('warning')
                ->setId('Remove_Permission_' . $permission->getId())
                ->addModal("Remove " . $permission->getGroupName() . " From " . $ouPath, "Are you sure you really want to remove this permission mapping?<br/>This will remove the permissions this group has at " . $permission->getOu() . " and lower OU's unless there is a defintion lower.<br/>There is no undo.<br/>" . $modalForm->print(), 'warning');
        //var_dump($permission);

        $form->addElementToNewRow($privilegeLevel->disable())
                ->addElementToCurrentRow($action)
                ->addElementToCurrentRow($permissionID)
                ->addElementToCurrentRow(self::buildUserPermissionDropdown($permission->getUserPermissionLevel()))
                ->addElementToCurrentRow(self::buildGroupPermissionDropdown($permission->getGroupPermissionLevel()))
                ->addElementToCurrentRow($saveButton)
                ->addElementToCurrentRow($removeButton);

        return '<div class = "px-3">' . $form->print() . '</div>';
    }

    /**
     *
     * @return FormDropdown
     */
    private static function buildPrivilegeLevelDropdown($selectedPrivilege = null, $privilegeIDsToOmit = null) {
        $levels = self::getPrivilegeLevels();

        $dropDown = new FormDropdown('', 'Privilege Level', "privilegeID");
        foreach ($levels as $level) {
            //var_dump($privilegeIDsToOmit);
            //var_d ump($level->getId());
            //var_dump(in_array($level->getId(), $privilegeIDsToOmit));
            $option = new FormDropdownOption($level->getAdGroup(), $level->getId());
            if ($level->getId() != null and $level->getId() == $selectedPrivilege) {
                $option->selected();
            }
            if ($privilegeIDsToOmit == null or ($privilegeIDsToOmit != null and!in_array($level->getId(), $privilegeIDsToOmit))) {
                $dropDown->addOption($option);
            }
        }
        if ($dropDown->getOptions() != null) {
            return $dropDown;
        }
        return false;
    }

    /**
     *
     * @return FormDropdown
     */
    private static function buildUserPermissionDropdown($selectedType = 0) {
        return self::generatePermissionTypeDropdown(PermissionLevel::getUserTypes(), '', 'User', "userPermissionType", $selectedType);
    }

    /**
     *
     * @return FormDropdown
     */
    private static function buildGroupPermissionDropdown($selectedType = 0) {
        return self::generatePermissionTypeDropdown(PermissionLevel::getGroupTypes(), '', 'Groups', "groupPermissionType", $selectedType);
    }

    /**
     *
     * @return FormDropdown
     */
    private static function buildStaffUserPermissionDropdown($selectedType = 0) {
        return self::generatePermissionTypeDropdown(PermissionLevel::getUserTypes(), '', 'Staff Users', "staffUserPermissionType", $selectedType);
    }

    /**
     *
     * @return FormDropdown
     */
    private static function buildStaffGroupPermissionDropdown($selectedType = 0) {
        return self::generatePermissionTypeDropdown(PermissionLevel::getGroupTypes(), '', 'Staff Groups

            ', "staffGroupPermissionType", $selectedType);
    }

    /**
     * Takes an array of PermissionTypes and returns a FormDropdown of it
     * @param type $types
     * @param type $label FormDropdown label
     * @param type $sublabel FormDropdown sub label
     * @param type $name FormDropdown name for post
     * @return FormDropdown
     */
    private static function generatePermissionTypeDropdown($types, $label, $sublabel, $name, $selectedType) {
        $dropDown = new FormDropdown($label, $sublabel, $name);
        foreach ($types as $type) {
            $option = new FormDropdownOption($type->getName(), $type->getId());
            $dropDown->addOption($option);
        }
        foreach ($dropDown->getOptions() as $option) {
            if ($option->getValue() == $selectedType) {
                $option->selected();
            }
        }
        return $dropDown;
    }

    public static function getPrivilegeLevels() {
        if (self::$privilegeLevels == null) {
            $levels = PrivilegeLevelDatabase::get();
            self::$privilegeLevels = $levels;
            return $levels;
        } else {
            return self::$privilegeLevels;
        }
    }

}
