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
 * Description of CardPrinter
 *
 * @author cjacobsen
 */
use app\models\district\Group;
use app\models\district\Student;
use app\models\district\Staff;
use app\models\user\User;
use app\models\district\DistrictUser;
use app\models\user\Privilege;
use system\app\App;
use system\app\forms\Form;
use system\app\forms\FormText;
use system\app\forms\FormButton;
use app\models\user\PermissionLevel;
use app\models\user\PermissionHandler;

abstract class CardPrinter extends ViewModel {
//put your code here

    /**
     *
     * @param DistrictUser $user
     * @param User $webUser
     * @return string
     */
    private static function buildUserCard(DistrictUser $user, User $webUser) {
        $app = \system\app\App::get();
        $script = '<script>
            $(function () {$(\'[data-toggle="tooltip"]\').tooltip()})
  </script>';

        $output = $script . '
            <div class="col">
  <div class="card-body">'
                . '<div class="row">';

        if ($user->getEnabled()) {

            $output .= self::buildEnabledStatus($user, $webUser);
        } else {
            $output .= self::buildDisabledStatus($user, $webUser);
        }
        $output .= self::printUserPhoto($user);

        if (!$user->getLockedOut()) {
            $output .= '<div class="col text-success h3"><i data-toggle="tooltip" data-placement="top" title="Account is not locked out." class="fas fa-lock-open"></i>';

            $output .= '</div>';
        } else {
            $output .= '<div class="col text-danger h3"><i data-toggle="tooltip" data-placement="top" title="Account is locked out." class="fas fa-lock-closed"></i>';
            if ($webUser->getPrivilege() >= Privilege::POWER) {
                $output .= self::buildUnlockAccountButton($user->username);
            }
            $output .= '</div>';
        }
        $output .= '</div>';
        $output .= '<div class="row"><div class="col">';
        $output .= '<h5 class="card-title text-center">' . $user->adContainerName . '</h5>';

        $output .= '</div>';

        $output .= '</div>';
        if ($user instanceof Student) {
            $output .= self::printRow("Student ID", $user->employeeID);
        } else {

            $output .= self::printRow("Empoyee ID", $user->employeeID);
        }
        $output .= self::printRow("First Name", $user->firstName);
        $output .= self::printRow("Middle Name", $user->middleName);
        $output .= self::printRow("Last Name", $user->lastName);

        $output .= '<br/>';
        $output .= self::printRow("Home Phone", $user->homePhone);
        $output .= self::printRow("Street Address", $user->street);
        $output .= self::printRow("City", $user->city);
        $output .= self::printRow("State", $user->state);
        $output .= self::printRow("Zip Code", $user->zip);

        $output .= '<br/>';

        $output .= self::printRow(null, $user->adDepartment);
        $output .= self::printRow(null, $user->description);
        $output .= self::printRow(null, $user->adCompany);
        $output .= self::printRow(null, $user->office);

        $output .= '<br/>';


        $output .= self::printRow("Username", $user->username);
        $output .= self::printRow("Email Address", $user->email);
        $output .= self::printRow("Groups", var_export($user->groups, true));

//$output .= '<div class="row"><div class="col h6">Username</div><div class="col">'.$user->getAdUsername().'</div></div>';
//$output .= '<div class="row"><div class="col h6">Email Address</div><div class="col">'.$user->getAdEmail().'</div></div>';
//$output .= '<div class="row"><div class="col h6">Groups</div><div class="col">'.var_export($user->getAdGroups(),true).'</div></div></div>';

        return $output;
    }

    private static function buildGroupCard(Group $group, User $webUser) {
        $app = \system\app\App::get();
        $script = '<script>
            $(function () {$(\'[data-toggle="tooltip"]\').tooltip()})
  </script>';

        $output = $script . '
            <div class="col">
  <div class="card-body">';



        $output .= '<div class="row"><div class="col">';
        $output .= '<h5 class="card-title text-center">' . $group->getName() . '</h5>';

        $output .= '</div>';

        $output .= '</div>';


        $output .= self::printRow("Email", $group->getEmail());
        $output .= self::printGroupMembers("Members", $group);
        $output .= '</div>';

        $output .= '</div>';
//$output .= '<div class="row"><div class="col h6">Username</div><div class="col">'.$user->getAdUsername().'</div></div>';
//$output .= '<div class="row"><div class="col h6">Email Address</div><div class="col">'.$user->getAdEmail().'</div></div>';
//$output .= '<div class="row"><div class="col h6">Groups</div><div class="col">'.var_export($user->getAdGroups(),true).'</div></div></div>';

        return $output;
    }

    /**
     *
     * @param type $label
     * @param type $value
     * @return type
     */
    private static function printRow($label, $value) {
        if ($label != null) {
            if (is_array($value)) {
                $output = self::printRow($label, null);
                //'<div class="row"><div class="col h6">' . $label . '</div></div>';
                foreach ($value as $singleValue) {
                    $output .= '<div class="row"><div class="col">' . $singleValue . '</div></div>';
                }
                return $output;
            } else {
                return '<div class="row"><div class="col h6">' . $label . '</div><div class="col">' . $value . '</div></div>';
            }
        } else {
            return '<div class="row"><div class="col h6">' . $value . '</div></div>';
        }
    }

    /**
     *
     * @param type $label
     * @param Group $group
     * @return type
     */
    private static function printGroupMembers($label, Group $group) {
        if ($label != null) {
            $groupMembers = $group->getMembers();
            if (is_array($groupMembers)) {
                $output = self::printRow($label, null);
                //'<div class="row"><div class="col h6">' . $label . '</div></div>';
                foreach ($groupMembers as $user) {
                    $form = new Form(null, "removeGroupMember");

                    $output .= '<div class="row">'
                            . '<div class="col">' . $user->getUsername() . '</div>'
                            . '<div class="col">' . $user->getFullName() . '</div>'
                            . '<div class="col">' . self::buildRemoveFromGroupButton($user, $group) . '</div>'
                            . '</div>';
                }
                return $output;
            } else {
                return '<div class="row"><div class="col h6">' . $label . '</div><div class="col">' . $groupMembers . '</div></div>';
            }
        } else {
            return '<div class="row"><div class="col h6">' . $groupMembers . '</div></div>';
        }
    }

    /**
     *
     * @param type $object
     * @param User $user
     * @return type
     */
    public static function printCard($object, User $user) {
        $class = get_class($object);
        //var_dump($class);
        switch ($class) {
            case Student::class:
                return self::buildUserCard($object, $user);
            case Staff::class:
                return self::buildUserCard($object, $user);
            case Group::class:
                return self::buildGroupCard($object, $user);

            default:
                //var_dump($object);
                AppLogger::get()->warning("Unknown object given of type: " . $class);
                break;
        }
    }

    /**
     *
     * @param DistrictUser $userToRemove
     * @param Group $group
     * @return type
     */
    protected static function buildRemoveFromGroupButton(DistrictUser $userToRemove, Group $group) {
        $userDN = $userToRemove->getDistinguishedName();
        $form = new Form("/groups/edit", "remove_member", "post");
        $userInput = new FormText("username");
//var_dump($username);

        $userInput->hidden()
                ->setName("username")
                ->setValue($userToRemove->getUsername());
        $groupInput = new FormText("group");
//var_dump($username);

        $groupInput->hidden()
                ->setName("group")
                ->setValue($group->getName());

        $action = new FormText("action");
        $action->hidden()
                ->setName("action")
                ->setValue("removeMember");


        $button = new FormButton("Remove");
        $button->full()
                ->setTheme("danger")
                ->hideLabels();

        $form->addElementToNewRow($userInput)
                ->addElementToNewRow($groupInput)
                ->addElementToNewRow($action)
                ->addElementToNewRow($button);
        return $form->print();
    }

    /**
     *
     * @param string $username
     * @param string $type Can be [students/staff] If omitted will be students.
     * @return type
     */
    private static function buildDisableAccountButton(string $username, string $type = "students") {

        $form = new Form("/$type/edit", "disable_account", "post");
        $userInput = new FormText("username");
//var_dump($username);

        $userInput->hidden()
                ->setName("username")
                ->setValue($username);

        $action = new FormText("action");
        $action->hidden()
                ->setName("action")
                ->setValue("disable");


        $button = new FormButton("Disable");
        $button->full()
                ->setTheme("danger")
                ->hideLabels();

        $form->addElementToNewRow($userInput)
                ->addElementToNewRow($action)
                ->addElementToNewRow($button);
        return $form->print();
    }

    /**
     *
     * @param type $username
     * @param type $type
     * @return type
     */
    private static function buildEnableAccountButton($username, $type = "students") {
        $form = new Form("/$type/edit", "disable_account", "post");


        $userInput = new FormText("username");
        $userInput->hidden()
                ->setName("username")
                ->setValue($username);


        $action = new FormText("action");
        $action->hidden()
                ->setName("action")
                ->setValue("enable");


        $button = new FormButton("Enable");
        $button->full()
                ->hideLabels()
                ->setTheme("success");


        $form->addElementToNewRow($userInput)
                ->addElementToNewRow($action)
                ->addElementToNewRow($button);
        return $form->print();
    }

    /**
     *
     * @param type $username
     * @param type $type
     * @return type
     */
    private static function buildUnlockAccountButton($username, $type = "students") {
        $form = new Form("/$type/edit", "unlock_account", "post");
        $userInput = new FormText("username");
//var_dump($username);
        $userInput->hidden()
                ->setName("username")
                ->setValue($username);
        $button = new FormButton("Unlock");
        $button->small()
                ->setTheme("success");
        $form->addElementToNewRow($userInput)
                ->addElementToNewRow($button);
        return $form->print();
    }

    /**
     *
     * @param type $user
     * @return string
     */
    private static function printUserPhoto($user) {
        $photo = '<div class="col-sm mb-2 mb-0-sm ">';
        if (!is_null($user->getPhoto())) {
            $photo .= '<img class="card-img-top" style="width:100px;" src="data:image/jpeg;base64,' . base64_encode($user->getPhoto()) . '"/>';
        }
        $photo .= '</div>';
        return $photo;
    }

    private static function buildDisabledStatus(DistrictUser $user, User $webUser) {
        $output = '<div class="col text-danger h3"><i data-toggle="tooltip" data-placement="top" title="Account is not enabled." class="fas fa-times-circle"></i>';

        //if ($webUser->getPrivilege() >= Privilege::POWER) {
        //var_dump($user->getOu());
        if (PermissionHandler::hasPermission($user->getOu(), PermissionLevel::USERS, PermissionLevel::USER_DISABLE)) {
            $output .= self::buildEnableAccountButton($user->getUsername(), self::getType());
        }
        $output .= '</div>';
        return $output;
    }

    private static function buildEnabledStatus(DistrictUser $user, User $webUser) {
        $output = '<div class="col text-success h3"><i data-toggle="tooltip" data-placement="top" title="Account is enabled." class="fas fa-check-circle"></i>';

        //if ($webUser->getPrivilege() >= Privilege::POWER) {
        if (PermissionHandler::hasPermission($user->getOu(), PermissionLevel::USERS, PermissionLevel::USER_DISABLE)) {
            $output .= self::buildDisableAccountButton($user->getUsername(), self::getType());
        }
        $output .= '</div>';
        return $output;
    }

    private static function getType() {
        return strtolower(App::get()->route->getControler());
    }

}
