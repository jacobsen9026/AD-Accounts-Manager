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

namespace App\Models\View;

/**
 * Description of PrivilegeLevelPrinter
 *
 * @author cjacobsen
 */

use App\Api\AD;
use App\Models\Database\DistrictDatabase;
use App\Models\Database\PrivilegeLevelDatabase;
use App\Models\Database\PermissionMapDatabase;
use App\Models\User\PermissionLevel;
use System\App\AppLogger;
use System\App\Forms\Form;
use System\App\Forms\FormText;
use System\App\Forms\FormDropdown;
use System\App\Forms\FormButton;
use System\App\Forms\FormDropdownOption;
use App\Models\User\Permission;
use System\App\Forms\FormSlider;
use App\Models\District\District;
use System\Database;

abstract class PermissionMapPrinter extends ViewModel
{

    use \System\Traits\DomainTools;

    /**
     * @var array
     */
    private static $privilegeLevels;

    /**
     *
     * @param type $districtID
     *
     * @return type
     * @deprecated
     */


    /**
     *
     * @param type $districtID
     *
     * @return string
     */
    public static function printPrivilegeLevels($districtID): string
    {

        $levels = self::getPrivilegeLevels();
        //var_dump($levels);
        $output = '';
        if ($levels !== false) {
            foreach ($levels as $level) {
                $form = new Form('/settings/district/permissions/' . $districtID, 'modifyPrivilegeLevel_' . $level->getId());

                $action = new FormText('', '', 'action', 'updatePrivilege');
                $action->hidden();

                $id = new FormText('', '', 'privilegeID', $level->getId());
                $id->hidden();
                $groupName = new FormText('', 'Group Name', 'groupName', $level->getAdGroup());
                $groupName->autoCompleteDomainGroupName();

                $superUserSlider = new FormSlider('', 'Super Admin', 'superAdmin', $level->getSuperAdmin());
                $superUserSlider->addOption("Yes", 1, $level->getSuperAdmin())
                    ->addOption("No", 0, !$level->getSuperAdmin())
                    ->setId('superUser_' . $level->getId());
                $updateButton = new FormButton('Update');
                $updateButton->setId('Update_Privilege_Level_' . $level->getId())
                    ->addAJAXRequest('/api/settings/district/permissions', 'permissionSettingsContainer', $form);

                $updateButton->setTheme('success');
                $deleteButtonID = 'Delete_' . $level->getId();
                $modalID = $deleteButtonID . '_Modal';
                $modalForm = new Form('/settings/district/permissions/' . $districtID, 'Delete_Privile_Level_' . $level->getId());
                $deleteAction = new FormText('/settings/district/permissions/' . $districtID, '', 'action', 'deletePrivilege');
                $deleteAction->hidden();
                $reallyDeleteButton = new FormButton("Confirm");

                $reallyDeleteButton->setId('Delete_Privilege_Level_' . $level->getId())
                    ->setTheme('danger');
                $function = ' $("#' . $modalID . '").modal("hide");';
                $onClick = Javascript::on($reallyDeleteButton->getId(), $function);
                $reallyDeleteButton->setScript($onClick)
                    // ->setType('button');
                    ->addAjaxRequest('/api/settings/district/permissions/' . $districtID, 'permissionSettingsContainer', $modalForm);

                $modalForm->addElementToNewRow($deleteAction)
                    ->addElementToNewRow($reallyDeleteButton)
                    ->addElementToNewRow($id);


                $deleteButton = new FormButton('Delete');
                $deleteButton->setTheme('danger')
                    ->setId($deleteButtonID)
                    ->buildModal("Delete " . $level->getAdGroup(), "Are you sure you really want to delete this privilege level?<br/><br/>This will remove all mapped permissions. There is no undo.<br/>" . $modalForm->print(), 'danger');
                //var_dump($deleteButton);
                $form->addElementToNewRow($groupName)
                    ->addElementToCurrentRow($superUserSlider)
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

    /**
     *
     * @param string $ou
     *
     * @return string
     */
    public static function printOUPermissions($ou): string
    {
        $permissions = PermissionMapDatabase::getPermissionsByOU($ou);
        //var_dump($permissions);
        // echo"<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>";
        //var_dump($permissions);
        $output = '<h6 class="mx-auto border py-2 rounded-lg bg-white text-muted">' . $ou . '</h6>';
        if ($permissions != false) {
            foreach ($permissions as $permission) {
                $perm = new Permission();
                $perm->importFromDatabase($permission);
                $output .= self::printModifyPermission($perm, $ou);

            }
        }
        $output .= self::printAddOUPermission($ou);
        return $output;
    }

    /**
     * Prints a form for adding a privilege level
     *
     * @param type $districtID
     *
     * @return string
     */
    public static function printAddPrivilegeLevelForm($districtID): string
    {
        $form = new Form('/settings/district/permissions/' . $districtID, 'addPrivilegeLevel');
        $newGroupName = new FormText('', 'LDAP group name', 'ldapGroupName');
        $newGroupName->autoCompleteDomainGroupName();
        $action = new FormText('', '', 'action', 'addPrivilege');
        $action->hidden();
        $addButton = new FormButton('Add');
        $addButton->addAJAXRequest('/api/settings/district/permissions/' . $districtID, 'permissionSettingsContainer', $form, true);
        $form->addElementToNewRow($newGroupName)
            ->addElementToCurrentRow($addButton)
            ->addElementToCurrentRow($action);

        return $form->print();
    }


    /**
     *
     * @param string $ou
     *
     * @return string
     */
    public static function cleanOU($ou): string
    {
        $search = [' ', 'OU=', ',', 'DC='];
        return str_replace($search, '', $ou);
    }

    /**
     *
     * @param string $ou
     *
     * @return string
     */
    public static function printAddOUPermission($ou): string
    {

        $ouText = new FormText('', '', 'ou', $ou);

        $ouText->hidden();
        $existingPrivileges = PermissionMapDatabase::getPrivilegeIDsByOU($ou);
        //var_dump($ou);
        //var_dump($existingPrivileges);
        $classes = "form-text text-dark mt-0";
        $ouID = self::getHTML_ID_FromOU($ou);
        $form = new Form('', 'Add_OU_Perm_' . $ouID);
        if ($privilegeID = self::buildPrivilegeLevelDropdown(null, $existingPrivileges)) {
            $privilegeID->setSubLabelClasses($classes);
            $action = new FormText('', '', 'action', 'addOUPermission');
            $action->hidden();
            $addButton = new FormButton('Add', 'small');
            $addButton->setTheme('secondary')
                ->setID("Add_" . $ouID)
                ->addAJAXRequest('/api/settings/district/permissions', 'ouPermissionsContainer', $form, true);
            $form->addElementToNewRow($privilegeID)
                ->addElementToCurrentRow($action)
                ->addElementToCurrentRow($ouText)
                ->addElementToCurrentRow(self::buildUserPermissionDropdown(false)->setSubLabelClasses($classes))
                ->addElementToCurrentRow(self::buildGroupPermissionDropdown(false)->setSubLabelClasses($classes))
                ->addElementToCurrentRow($addButton);

            return "<div class='bg-light rounded-lg px-3 pt-1'>" . $form->print() . "</div>";
        }
        return "";
    }

    /**
     * Removes spaces, commas, and ='s from the OU
     * to make safe for use as an HTML id
     *
     * @param string $ou
     *
     * @return string
     */
    public static function getHTML_ID_FromOU($ou): string
    {
        $remove = [" ", ",", "="];
        return str_replace($remove, "_", $ou);
        //var_dump($ou);
    }

    /**
     *
     * @param Permission $permission
     * @param string $ou
     *
     * @return string
     */
    public static function printModifyPermission(Permission $permission, string $ou = null): string
    {
        $ou = self::getHTML_ID_FromOU($ou);
        $ouPath = '';
        $path = explode("OU=", str_replace(',', '', $permission->getOu()));
        foreach (array_reverse($path) as $part) {
            if ($part != '' && strpos($part, 'DC=') == false) {
                $ouPath .= '/' . $part;
            }
        }

        $form = new Form('', 'Modify_Permissions_' . $permission->getId() . '_' . $ou);
        $privilegeLevel = self::buildPrivilegeLevelDropdown($permission->getPrivilegeID());
        $privilegeLevel->hidden();
        $action = new FormText('', '', 'action', 'modifyPermission');
        $action->hidden();
        $permissionID = new FormText('', '', 'id', $permission->getId());
        $permissionID->hidden();

        $removeButton = new FormButton('Remove');
        $removeButton->setTheme('warning')
            ->setId('Remove_Permission_' . $permission->getId())
            ->setSize("auto");


        $modalForm = new Form('', "Remove_Permission_" . $permission->getId() . "_" . $ou);

        $id = new FormText('', '', 'id', $permission->getId());
        $id->hidden();
        $removeAction = new FormText('', '', 'action', 'removePermission');
        $removeAction->hidden();
        $reallyDeleteButton = new FormButton("Confirm");
        $reallyDeleteButton->setTheme('warning')
            ->setId("Really_Delete_Permission_" . $permission->getId() . "_" . $ou)
            ->addAJAXRequest('/api/settings/district/permissions', 'ouPermissionsContainer', $modalForm, true);
        $reallyDeleteButton->setScript(Javascript::on($reallyDeleteButton->getId(), 'console.log("hide modal"); $("#' . $removeButton->getId() . 'Modal").modal("toggle")'));


        $modalForm->addElementToNewRow($removeAction)->addElementToNewRow($reallyDeleteButton)->addElementToNewRow($id);


        $removeButton->builcModal("Remove " . $permission->getGroupName() . " From " . $ouPath, "Are you sure you really want to remove this permission mapping?<br/>This will remove the permissions this group has at " . $permission->getOu() . " and lower OU's unless there is a defintion lower.<br/>There is no undo.<br/>" . $modalForm->print(), 'warning');


        //var_dump($permission);

        $saveButton = new FormButton('Save', 'small');
        $saveButton->setId("Save_Button_" . $permission->getId() . '_' . $ou);
        $saveButton->addAJAXRequest('/api/settings/district/permissions', $ou, $form, true);

        $form->addElementToNewRow($privilegeLevel->disable())
            ->addElementToCurrentRow($action)
            ->addElementToCurrentRow($permissionID)
            ->addElementToNewRow(self::buildUserPermissionDropdown((bool)$permission->getUserPermissionLevel()))
            ->addElementToCurrentRow(self::buildGroupPermissionDropdown((bool)$permission->getGroupPermissionLevel()))
            ->addElementToNewRow($saveButton);
        //->addElementToCurrentRow($removeButton);
        $output = '<div class="card rounded shadow-sm mb-5">'
            . '<h5 class="container-fluid p-2 mb-2 bg-primary text-light">'
            . $permission->getGroupName()
            . '</h5>'
            . $form->print()
            . "</div>";
        return $output;

    }

    /**
     *
     * @return FormDropdown
     */
    private static function buildPrivilegeLevelDropdown($selectedPrivilege = null, $privilegeIDsToOmit = null)
    {
        $levels = self::getPrivilegeLevels();

        $dropDown = new FormDropdown('', 'Privilege Level', "privilegeID");
        $dropDown->setSize("auto");
        foreach ($levels as $level) {
            //var_dump($privilegeIDsToOmit);
            //var_d ump($level->getId());
            //var_dump(in_array($level->getId(), $privilegeIDsToOmit));
            $option = new FormDropdownOption($level->getAdGroup(), $level->getId());
            if ($level->getId() !== null && $level->getId() == $selectedPrivilege) {
                $option->selected();
            }

            if ($privilegeIDsToOmit === null || ($privilegeIDsToOmit !== null && !in_array($level->getId(), $privilegeIDsToOmit))) {
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
     * @param int $selectedType
     *
     * @return FormDropdown
     */
    private
    static function buildUserPermissionDropdown($selectedType = 0): FormDropdown
    {
        return self::generatePermissionTypeDropdown(PermissionLevel::getUserTypes(), '', 'user', "userPermissionType", $selectedType);
    }

    /**
     *
     * @param int $selectedType
     *
     * @return FormDropdown
     */
    private
    static function buildGroupPermissionDropdown($selectedType = 0): FormDropdown
    {
        return self::generatePermissionTypeDropdown(PermissionLevel::getGroupTypes(), '', 'Groups', "groupPermissionType", $selectedType);
    }

    /**
     * @param $tree
     *
     * @return string
     */
    public
    static function printOUNavigationTree($tree): string
    {
        $output = '';
        foreach ($tree as $name => $branch) {
            if (is_array($branch)) {
                $output .= self::printBranchOU($name, $branch);
            } else {

                $output .= self::printLeafOU($branch);
            }
        }
        return $output;

    }


    /**
     * Takes an array of PermissionTypes and returns a FormDropdown of it
     *
     * @param array $types
     * @param string $label    FormDropdown label
     * @param string $subLabel FormDropdown sub label
     * @param string $name     FormDropdown name for post
     *
     * @param int $selectedType
     *
     * @return FormDropdown
     */
    private
    static function generatePermissionTypeDropdown(array $types, string $label, string $subLabel, string $name, int $selectedType): FormDropdown
    {
        $dropDown = new FormDropdown($label, $subLabel, $name);
        foreach ($types as $type) {
            $option = new FormDropdownOption($type->getName(), $type->getId());
            if ($option->getValue() == $selectedType) {
                $option->selected();
            }
            $dropDown->addOption($option)
                ->setSize("auto");
        }
        return $dropDown;
    }

    /**
     *
     * @return type
     */
    public
    static function getPrivilegeLevels()
    {
        if (self::$privilegeLevels == null) {
            $levels = PrivilegeLevelDatabase::get();
            self::$privilegeLevels = $levels;
            return $levels;
        } else {
            return self::$privilegeLevels;
        }
    }

    /**
     * @param $dn
     * @param $childrenArray
     *
     * @return string
     */
    private
    static function printBranchOU($dn, $childrenArray)
    {

        // $ou = self::getHTML_ID_FromOU($dn);
        $subOuPermissionCount = PermissionMapDatabase::getSubOUPermissionsCount($dn);
        //var_dump($subOuPermissionCount);
        $textColorClass = "";
        if ($subOuPermissionCount > 0) {
            $textColorClass = "text-success";
        }
        $output = '<div class="container-fluid pr-0 text-left">'
            . '<a  class="d-inline-block clickable grow" data-toggle="collapse" role="button" data-target="#' . self::cleanOU($dn) . '" data-text-alt="<i class=\'fas fa-caret-down\'></i>"  style="width:1em!important;">'
            . '<i class="fas fa-caret-right"></i>'
            . '</a>'
            . '<p class="d-inline-block clickable highlight ouPermissionsButton ' . $textColorClass . ' w-75 text-left pl-2 mb-0"  data-target-ou="' . $dn . '">'
            . self::leftOU($dn)
            . '</p>';
        $permissionCount = PermissionMapDatabase::getOUPermissionsCount($dn);
        $ouCountID = self::getHTML_ID_FromOU($dn) . "_Permission_Count";
        if ($permissionCount > 0) {
            $output .= '<div data-count-ou="' . $dn . '" class="col-2 permissionCountBadge"><p>'
                . PermissionMapDatabase::getOUPermissionsCount($dn)
                . '</p>'
                . '</div>';
        } else {
            $output .= '<div data-count-ou="' . $dn . '" class="col-2t permissionCountBadge hidden"><p>'
                . '</p>'
                . '</div>';
        }


        $output .= '<div id="' . self::cleanOU($dn) . '" class="collapse container-fluid mx-auto my-0 pr-0">'
            . self::printOUNavigationTree($childrenArray)
            . '</div>';
        $output .= '</div>';


        //var_dump($output);
        return $output;
    }

    /**
     *
     * @param type $dn
     *
     * @return string
     */
    public static function printLeafOU($dn)
    {
        $showOUButtonId = self::getHTML_ID_FromOU($dn) . '_Show_OU_Button';
        $output = '<div class="row clickable text-left ml-4 w-100">'
            . '<div id="' . $showOUButtonId . '" class=" col-10 highlight ouPermissionsButton pl-2 pr-0 mb-0" onclick="" data-target-ou="' . $dn . '">'

            . self::leftOU($dn);
        $permissionCount = PermissionMapDatabase::getOUPermissionsCount($dn);

        $output .= '</div>';
        $ouCountID = self::getHTML_ID_FromOU($dn) . "_Permission_Count";
        if ($permissionCount > 0) {
            $output .= '<div data-count-ou="' . $dn . '"  class="col-2 permissionCountBadge"><p>'
                . PermissionMapDatabase::getOUPermissionsCount($dn)
                . '</p>'
                . '</div>';
        } else {
            $output .= '<div data-count-ou="' . $dn . '" class="col-2  permissionCountBadge hidden"><p>'
                . '</p>'
                . '</div>';
        }
        $output .= '</div>';
        return $output;
    }

}
