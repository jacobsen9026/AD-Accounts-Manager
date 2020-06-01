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
 * Description of CardPrinter
 *
 * @author cjacobsen
 */

use App\Api\Ad\ADConnection;
use App\Models\District\DistrictGroup;
use App\Models\District\DistrictUser;
use App\Models\District\Group;
use App\Models\District\Student;
use App\Models\District\Staff;
use App\Models\User\User;
use App\App\App;
use System\App\AppException;
use System\App\AppLogger;
use System\App\Forms\Form;
use System\App\Forms\FormDropdownOption;
use System\App\Forms\FormElementGroup;
use System\App\Forms\FormHTML;
use System\App\Forms\FormMenuButton;
use System\App\Forms\FormText;
use System\App\Forms\FormButton;
use App\Models\User\PermissionLevel;
use App\Models\User\PermissionHandler;
use System\App\Forms\FormUpload;
use System\File;
use System\Parser;
use System\Post;
use System\Request;
use System\Traits\DomainTools;

abstract class CardPrinter extends ViewModel
{

    use DomainTools;

//put your code here


    /**
     *
     * @param type $object
     * @param User $webUser
     *
     * @return type
     */
    public static function printCard($object, User $webUser)
    {
        $class = get_class($object);
//var_dump($class);
        switch ($class) {
            case DistrictUser::class:
                return self::buildUserCard($object, $webUser);
            case DistrictGroup::class:
                return self::buildGroupCard($object, $webUser);

            default:
                //var_dump($object);
                AppLogger::get()->warning("Unknown object given of type: " . $class);
                break;
        }
    }

    /**
     *
     * @param DistrictUser $user
     * @param User $webUser
     *
     * @return string
     */
    private static function buildUserCard(DistrictUser $user, User $webUser)
    {


        $output = '<div class="col px-0">'
            . '<div class="card-body p-0 p-sm-3">'
            . '<div class="position-absolute right text-secondary"> '
            . self::printOptionsButton($user, $webUser)
            . '</div > ';

        $output .= '<a class="float-left mt-1 h4 clickable" style="z-index:3" href="' . Request::get()->getReferer() . '">';
        $output .= '<i class="text-primary text-decoration-none fas fa-arrow-left"></i >'
            . '</a > '
            . '<div class="row mx-auto w-100" > '; //Start Header Row

        if ($user->isActive()) {
            $output .= '<div class="col text-success h3" > '
                . '<i data-toggle = "tooltip" data-placement = "top" title = "Account is enabled." class="fas fa-check-circle" ></i > '
                . '</div > ';
        } else {
            $output .= '<div class="col text-danger h3" > '
                . '<i data-toggle = "tooltip" data-placement = "top" title = "Account is not enabled." class="fas fa-times-circle" ></i > '
                . '</div > ';
        }
        $output .= self::printUserHeader($user);

        /**
         * Show Locked Status
         */
        if (!$user->isLockedOut()) {
            $output .= '<div class="col text-success h3" > '
                . '<i data-toggle = "tooltip" data-placement = "top" title = "Account is not locked out." class="fas fa-lock-open" ></i > '
                . '</div > ';
        } else {
            $output .= '<div class="col text-danger h3" > '
                . '<i data-toggle = "tooltip" data-placement = "top" title = "Account is locked out." class="fas fa-lock" ></i > '
                . '</div > ';
        }
        $output .= '</div > ';
        /**
         * End Header
         *
         */


        //Begin User Body
        $output .= '<div class="row" ><div class="col" > ';
        $output .= '<h5 class="card-title text-center" > ' . $user->getFullName() . '</h5 > ';

        $output .= '</div > ';

        $output .= '</div > ';
        if ($user instanceof Student) {
            $output .= self::printRow("Student ID", $user->getEmployeeID());
        } else {

            $output .= self::printRow("Empoyee ID", $user->getEmployeeID());
        }
        $output .= self::printRow("First Name", $user->getFirstName());
        $output .= self::printRow("Middle Name", $user->getMiddleName());
        $output .= self::printRow("Last Name", $user->getLastName());

        $output .= '<br />';
        $output .= self::printRow("Home Phone", $user->getHomePhone());
        $output .= self::printRow("Street Address", $user->getStreetAddress());
        $output .= self::printRow("City", $user->getCity());
        $output .= self::printRow("State", $user->getState());
        $output .= self::printRow("Zip Code", $user->getPostalCode());

        $output .= '<br />';

        $output .= self::printRow(null, $user->getDepartment());
        $output .= self::printRow(null, $user->getDescription());
        $output .= self::printRow(null, $user->getCompany());
        $output .= self::printRow(null, $user->getOfficeName());

        $output .= '<br />';


        $output .= self::printRow("Username", $user->getUsername());
        $output .= self::printRow("Email Address", $user->getEmail());


        /**
         * Print Groups the user is a member of
         */


        $groups = '';


        /* @var $group \Adldap\Models\Group */
        foreach ($user->getGroups() as $group) {
            $groupName = $group->getName();
            $groups .= '<div class="row">
 <a href = "/groups/search/' . $groupName . '" class="col"> ' . $groupName . '</a ><br >
  <div class="col">'
                . self::buildRemoveFromGroupButton($user, new DistrictGroup($group))
                . '</div> '
                . '</div> ';
            //var_dump($group);
        }
        $groups .= self::printAddGroupMemberModalButton(null, $user);


        $output .= self::printRow("Groups", $groups);


        /**
         * Groups completed
         */

        return $output;
    }

    private static function printOptionsButton(DistrictUser $user, User $webUser): string
    {
        /**
         * Disable Button/Modal
         */
        $disableButton = new FormDropdownOption('Disable');
        $disableModal = new Modal();
        $disableModal->setId('disable_user_modal')
            ->setBody('Really disable this user?<br>' . self::buildDisableAccountButton($user))
            ->setTitle('Disable User')
            ->small();
        $disableButton->addModal($disableModal);


        /**
         * Enable Button/Modal
         */
        $enableButton = new FormDropdownOption('Enable');
        $enableModal = new Modal();
        $enableModal->setId('enable_user_modal')
            ->setBody('Really enable this user?<br>' . self::buildEnableAccountButton($user))
            ->setTitle('Enable User');
        $enableButton->addModal($enableModal);


        /**
         * Unlock Button/Modal
         */
        $unlockButton = new FormDropdownOption('Unlock');
        $unlockModal = new Modal();
        $unlockModal->setId('unlock_user_modal')
            ->setBody('Really unlock this user?<br>' . self::buildUnlockAccountButton($user))
            ->setTitle('Unlock User');
        $unlockButton->addModal($unlockModal);

        /**
         * Reset Password Button/Modal
         */
        $newPasswordButton = self::buildResetPasswordMenuOption($user);


        /**
         * Upload Photo Button
         */

        $action = new FormText('', '', 'action', 'uploadPhoto');
        $action->small();
        $action->hidden();
        $uploadPhoto = new FormUpload('', '', 'photo');
        $uploadPhoto->setBrowseButtonText('<i class="fas fa-sync-alt"></i>');
        $uploadPhoto->tiny()
            ->hidden();
        $uploadButton = new FormButton('Upload');
        $uploadPhotoForm = new Form('', 'uploadPhoto');
        $uploadPhotoForm->addElementToNewRow($uploadPhoto)
            ->addElementToCurrentRow($action);

        $uploadPhotoButton = new FormDropdownOption('Upload New Photo');
        $uploadPhotoButton->setId('upload_photo_button')
            ->setTooltip("Max size: " . Post::getMaxUploadSize());
        $clickUpload = '$("#' . $uploadPhoto->getId() . '").click()';
        $function = Javascript::on($uploadPhotoButton->getId(), $clickUpload);
        $uploadPhotoButton->setScript($function);
        //$newPasswordButton->addModal($newPasswordModal);
        //->addElementToCurrentRow($uploadButton);

        /**
         * Build the options button by loading in the parts the web user has permission to
         */

        $optionsButton = new FormMenuButton('<i class="h5 grow mb-0 fas fa-ellipsis-v"></i>');
        $optionsButton->tiny()
            ->removeInputClasses("btn-primary");
        $optionsButton->addMenuOptions($uploadPhotoButton);
        $optionsButton->addMenuOptions($newPasswordButton);
        if ($user->isLockedOut()) {
            $optionsButton->addMenuOptions($unlockButton);
        }
        /**
         * Load disable account button if web user has permission and user is disabled
         */
        if (PermissionHandler::hasPermission($user->getOU(), PermissionLevel::USERS, PermissionLevel::USER_DISABLE)) {
            if ($user->isDisabled()) {
                $optionsButton->addMenuOptions($enableButton);
            } else {
                $optionsButton->addMenuOptions($disableButton);
            }

        }


        $optionsButton->setSubLabel('Options');

        $output = '';
        if (PermissionHandler::hasPermission($user->getOU(), PermissionLevel::USERS, PermissionLevel::USER_UNLOCK)) {
            $output = $optionsButton->getElementHTML();
        }


        return $output . $uploadPhotoForm->print();
    }

    /**
     *
     * @param string $user
     * @param string $type Can be [students/staff] If omitted will be students.
     *
     * @return type
     */
    private static function buildDisableAccountButton(DistrictUser $user)
    {

        $form = new Form("/users/edit", "disable_account", "post");
        $userInput = new FormText("username");
//var_dump($username);

        $userInput->hidden()
            ->setName("username")
            ->setValue($user->getUsername());

        $action = new FormText("action");
        $action->hidden()
            ->setName("action")
            ->setValue("disable");


        $button = new FormButton("Disable");
        $button->tiny()
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
     *
     * @return type
     */
    private static function buildEnableAccountButton(DistrictUser $user)
    {
        $form = new Form("/users/edit", "disable_account", "post");


        $userInput = new FormText("username");
        $userInput->hidden()
            ->setName("username")
            ->setValue($user->getUsername());


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
     *
     * @return type
     */
    private static function buildUnlockAccountButton(DistrictUser $user)
    {
        $form = new Form("/users/edit", "unlock_account", "post");
        $userInput = new FormText("username");
//var_dump($username);
        $action = new FormText('', '', 'action', 'unlock');
        $action->hidden();
        $userInput->hidden()
            ->setName("username")
            ->setValue($user->getUsername());
        $button = new FormButton("Unlock");
        $button->setTheme("success");
        $form->addElementToNewRow($userInput)
            ->addElementToNewRow($action)
            ->addElementToNewRow($button);
        return $form->print();
    }

    private static function buildResetPasswordMenuOption(DistrictUser $user)
    {
        /**
         *
         * make reset password modal button
         */

        $action = new FormText('', '', 'action', 'resetPassword');
        $action->small();
        $action->hidden();
        $username = new FormText('', '', 'username', $user->getUsername());
        $username->small();
        $username->hidden();
        $newPassword = new FormText('', '', 'password');
        $newPassword->setPlaceholder("New password")
            ->large()
            ->isPassword();
        $submitButton = new FormButton('<i class="far fa-save"></i>');
        $submitButton->setId("setPassword_Button")
            ->addInputClasses("h-100")
            ->tiny();

        $changePasswordGroup = new FormElementGroup();
        $changePasswordGroup->addElementToGroup($newPassword)
            ->addElementToGroup($submitButton);

        $form = new Form('', 'setPassword');
        $form->addElementToCurrentRow($action)
            ->addElementToNewRow($username)
            ->addElementToNewRow($changePasswordGroup);


        $newPasswordModal = new Modal();
        $newPasswordModal->setTitle("Reset Password")
            ->setBody($form->print())
            ->setBody(Parser::get()->view('users/modals/resetPassword', ["username" => $user->getUsername()]))
            ->setId('newPassword_Modal');
        $newPasswordButton = new FormDropdownOption('Reset Password');
        $newPasswordButton->addModal($newPasswordModal);


        return $newPasswordButton;
    }

    /**
     *
     *
     * @param type $user
     *
     * @return string
     */
    private
    static function printUserHeader(DistrictUser $user)
    {
        /**
         * make photo and update
         */
        $output = '<div class="col-sm mb-2 mb-0-sm ">';
        //var_dump($user->activeDirectory);
        if (!is_null($user->activeDirectory->getThumbnail())) {
            $output .= '<img class="userPortrait card-img-top mb-3  col-md-7 px-0 dark-shadow" src="data:image/jpeg;base64, ' . base64_encode($user->activeDirectory->getThumbnail()) . '"/>';
        }


        if (PermissionHandler::hasPermission($user->getOU(), PermissionLevel::USERS, PermissionLevel::USER_CHANGE)) {


            /**
             *
             * make reset password modal button
             */
            /**
             * $action = new FormText('', '', 'action', 'resetPassword');
             * $action->small();
             * $action->hidden();
             * $username = new FormText('', '', 'username', $user->getUsername());
             * $username->small();
             * $username->hidden();
             * $newPassword = new FormText('', '', 'password');
             * $newPassword->setPlaceholder("New password")
             * ->large()
             * ->isPassword();
             * $submitButton = new FormButton('<i class="far fa-save"></i>');
             * $submitButton->setId("setPassword_Button")
             * ->addInputClasses("h-100")
             * ->tiny();
             *
             * $changePasswordGroup = new FormElementGroup();
             * $changePasswordGroup->addElementToGroup($newPassword)
             * ->addElementToGroup($submitButton);
             *
             * $form = new Form('', 'setPassword');
             * $form->addElementToCurrentRow($action)
             * ->addElementToNewRow($username)
             * ->addElementToNewRow($changePasswordGroup);
             * $newPasswordModal = new Modal();
             * $newPasswordModal->setTitle("Reset Password")
             * ->setBody($form->print())
             * ->setId('newPassword_Modal');
             * $newPasswordButton = new FormDropdownOption('Reset Password');
             * $newPasswordButton->addModal($newPasswordModal);
             *
             * if (ADConnection::isSecure()) {
             * $output .= $form->print();
             * }
             * */
        }
        $output .= '</div>';
        return $output;
    }

    /**
     *
     * @param type $label
     * @param type $value
     *
     * @return type
     */
    private static function printRow($label, $value)
    {
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
     * @param DistrictUser $userToRemove
     * @param Group $group
     *
     * @return type
     */
    protected static function buildRemoveFromGroupButton(DistrictUser $userToRemove, DistrictGroup $group)
    {
        $userDN = $userToRemove->activeDirectory->getDistinguishedName();
        $form = new Form("/groups/edit", "remove_member", "post");
        $userInput = new FormText("username");
//var_dump($username);
//var_dump($username);

        $userInput->hidden()
            ->setName("username")
            ->setValue($userToRemove->activeDirectory->getAccountName());
        $groupInput = new FormText("group");
//var_dump($username);

        $groupInput->hidden()
            ->setName("group")
            ->setValue($group->activeDirectory->getName());

        $action = new FormText("action");
        $action->hidden()
            ->setName("action")
            ->setValue("removeMember");


        $button = new FormButton('<i class="fas fa-trash-alt"></i>');
        $button->tiny()
            ->setTheme("danger")
            ->hideLabels()
            ->setTooltip("Remove from group");

        $form->addElementToNewRow($userInput)
            ->addElementToNewRow($groupInput)
            ->addElementToNewRow($action)
            ->addElementToNewRow($button);
        return $form->print();
    }

    /**
     *
     * @param Group $group
     *
     * @return type
     */
    private static function printAddGroupMemberModalButton(DistrictGroup $group = null, DistrictUser $user = null)
    {
        $modalForm = new Form('/groups/edit', 'addMemberToGroup');

        $modal = new Modal();

        $action = new FormText('', '', 'action', 'addMember');
        $action->hidden();

        if ($group === null) {
            $groupName = new FormText('Add to group', '', 'group');
            $groupName->setId('groupToAddMemberTo')
                ->autoCompleteGroupName();

            $userToAdd = new FormText('Add user', 'Can also serarch by first or last name.', 'usernameToAdd', $user->getUsername());
            $userToAdd->autoCompleteDomainUsername()
                ->hidden();

            $modal->setTitle("Find Group");
        } elseif ($user === null) {
            $groupName = new FormText('', '', 'group', $group->activeDirectory->getDistinguishedName());
            $groupName->hidden();

            $userToAdd = new FormText('Add user', 'Can also serarch by first or last name.', 'usernameToAdd');
            $userToAdd->autoCompleteDomainUsername();

            $modal->setTitle("Find User");
        } else {
            throw new AppException("No user or group was supplied to the add group members modal");
        }

        $submitButton = new FormButton("Add");

        $modalForm->addElementToCurrentRow($groupName)
            ->addElementToCurrentRow($action)
            ->addElementToCurrentRow($userToAdd)
            ->addElementToNewRow($submitButton);


        $modal->setId('add_user_to_group')
            ->setBody($modalForm->print());


        $modalButton = new FormButton('<i class="fas fa-plus"></i>', 'tiny');
        $modalButton->addModal($modal)
            ->setTheme('success')
            ->setTooltip("Add to new group");
        return $modalButton->print();
        return $modalForm->print();
    }

    /**
     *
     * @param DistrictGroup $group
     * @param User $webUser
     *
     * @return string
     * @throws AppException
     */
    private static function buildGroupCard(DistrictGroup $group, User $webUser)
    {

        $app = \App\App\App::get();


        $output = '<div class="col">
  <div class="card-body">';


        $output .= '<div class="row"><div class="col">';
        $output .= '<h5 class="position-relative d-inline card-title text-center p-5- m-5" style="left:0.5em">' . $group->activeDirectory->getName() . '</h5>';
        $output .= self::printDeleteGroupButton($group);
        $output .= '<a class="float-left mt-1 h4 clickable" style="z-index:3" href="' . Request::get()->getReferer() . '">';
        $output .= '<i class="text-primary text-decoration-none fas fa-arrow-left"></i >'
            . '</a > ';
        $output .= '</div>';

        $output .= '</div>';


        $output .= self::printRow("Email", $group->getEmail());
        $output .= self::printGroupMembers("Members", $group);
        $output .= self::printAddGroupMemberModalButton($group);
        $output .= '</div>';

        $output .= '</div>';
        return $output;
    }

    private
    static function printDeleteGroupButton(DistrictGroup $group)
    {
        $groupName = $group->activeDirectory->getName();
        $deleteButton = new FormButton('<i class="h4 mb-0 fas fa-times"></i>');
        $deleteButton->tiny()
            ->setTheme("white")
            ->removeInputClasses("w-100")
            ->addInputClasses("position-absolute right-10 text-danger")
            ->addElementClasses("top right pr-5 d-inline h-100")
            ->setTooltip("Delete " . $groupName);

        $modalForm = new Form("/groups/delete", "GroupDelete");
        $action = new FormText('', '', 'action', 'deleteGroup');
        $action->hidden();
        $button = new FormButton("Delete");
        $button->medium()
            ->setTheme('danger');
        $text = new FormHTML();
        $text->setHtml('<h5>Are you absolutely sure you want to delete this group? There is no undo to this action.<br><br> Be sure you are deleting the right group.</h5>');
        $text->full();
        $name = new FormText('', '', 'groupDN', $group->activeDirectory->getDistinguishedName());
        $name->medium()
            ->hidden();
        $modalForm->addElementToNewRow($name)
            ->addElementToNewRow($action)
            ->addElementToNewRow($text)
            ->addElementToNewRow($button);


        $deleteModal = new \App\Models\View\Modal();
        $deleteModal->setBody($modalForm->print())
            ->setId('deleteGroup')
            ->setTheme('danger ')
            ->setTitle("Delete " . $groupName);
        $deleteButton->addModal($deleteModal);
        return $deleteButton->print();
    }

    /**
     *
     * @param type $label
     * @param Group $group
     *
     * @return type
     */
    private static function printGroupMembers(string $label, DistrictGroup $group)
    {
        if ($label != null) {
            $groupMembers = $group->getMembers();
            if (is_array($groupMembers)) {
                $output = self::printRow($label, null);
//'<div class="row"><div class="col h6">' . $label . '</div></div>';
                /* @var $user DistrictUser */
                foreach ($groupMembers as $user) {

                    $form = new Form(null, "removeGroupMember");

                    $output .= '<div class="row">'
                        . '<div class="col"><a href="/users/search/' . $user->getUsername() . '">' . $user->getUsername() . '</a></div>'
                        . '<div class="col">' . $user->getFullName() . '</div>'
                        . '<div class="col">' . self::buildRemoveFromGroupButton($user, $group) . '</div>'
                        . '</div>';
                }
                return $output;
            } else {
                /* @todo Check if this is necessary */
                return '<div class="row"><div class="col h6">' . $label . '</div><div class="col">' . $groupMembers . '</div></div>';
            }
        }
    }

    /**
     *
     * @param DistrictUser $user
     * @param User $webUser
     *
     * @return string
     */
    private static function buildDisabledStatus(DistrictUser $user, User $webUser)
    {
        $output = '<div class="col text-danger h3"><i data-toggle="tooltip" data-placement="top" title="Account is not enabled." class="fas fa-times-circle"></i>';

        //if ($webUser->getPrivilege() >= Privilege::POWER) {
        //var_dump($user->getOu());
        // if (PermissionHandler::hasPermission(self::getOUFromDN($user->activeDirectory->getDistinguishedName()), PermissionLevel::USERS, PermissionLevel::USER_DISABLE)) {
        //     $output .= self::buildEnableAccountButton($user, self::getType());
        // }
        $output .= '</div>';
        return $output;
    }

    /**
     *
     * @param DistrictUser $user
     * @param User $webUser
     *
     * @return string
     */
    private
    static function buildEnabledStatus(DistrictUser $user, User $webUser)
    {
        $output = '<div class="col text-success h3"><i data-toggle="tooltip" data-placement="top" title="Account is enabled." class="fas fa-check-circle"></i>';
        $output .= '</div>';
        return $output;
    }

    /**
     *
     * @return type
     */
    private
    static function getType()
    {
        return strtolower(App::get()->route->getControler());
    }

}
