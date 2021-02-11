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

use App\Api\AD;
use App\Models\Database\DomainDatabase;
use App\Models\District\DomainGroup;
use App\Models\District\DomainUser;
use App\Models\User\User;
use App\App\App;
use System\App\Forms\Form;
use System\App\AppException;
use System\App\AppLogger;
use App\Forms\FormText;
use System\App\Forms\FormDropdownOption;
use System\App\Forms\FormElementGroup;
use System\App\Forms\FormHTML;
use System\App\Forms\FormMenuButton;
use System\App\Forms\FormButton;
use App\Models\User\PermissionLevel;
use App\Models\User\PermissionHandler;
use System\App\Forms\FormUpload;
use System\Lang;
use System\Parser;
use System\Post;
use System\Traits\DomainTools;

abstract class CardPrinter extends ViewModel
{

    use DomainTools;

//put your code here


    /**
     *
     * @param DomainUser|DomainGroup $object
     * @param User $webUser
     *
     * @return string
     */
    public static function printCard($object)
    {
        $class = get_class($object);
//var_dump($class);
        switch ($class) {
            case DomainUser::class:
                return self::buildUserCard($object);
            case DomainGroup::class:
                return self::buildGroupCard($object);

            default:
                //var_dump($object);
                AppLogger::get()->warning("Unknown object given of type: " . $class);
                break;
        }
        return '';
    }

    /**
     *
     * @param DomainUser $user
     * @param User $webUser
     *
     * @return string
     */
    private static function buildUserCard(DomainUser $user)
    {


        $output = '<div class="col px-0">'
            . '<div class="card-body p-0 p-sm-3">';

        $output .= '<div class="position-absolute right top-10 text-secondary text-right" style="width:2em;"> '
            . self::printOptionsButton($user)
            . '</div > ';


        $output .= '<div class="row mx-auto w-100" > '; //Start Header Row

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


        $output .= self::printRow("Empoyee ID", $user->getEmployeeID());

        $output .= self::printRow(Lang::get("First Name"), $user->getFirstName());
        if ($user->getMiddleName() != '') {
            $output .= self::printRow(Lang::get("Middle Name"), $user->getMiddleName());
        }
        $output .= self::printRow(Lang::get("Last Name"), $user->getLastName());

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


        $output .= self::printRow(Lang::get("OU"), self::prettifyOU($user->getOU()));
        $output .= self::printRow(Lang::get("Username"), $user->getUsername());
        $output .= self::printRow(Lang::get("Email Address"), $user->getEmail());


        /**
         * Print Groups the user is a member of
         */


        $groups = '';


        /* @var $group \Adldap\Models\Group */
        foreach ($user->getGroups() as $group) {
            $groupName = $group->getAccountName();
            $groups .= '<div class="row">
 <a href = "/groups/search/' . $groupName . '" class="col-10"> ' . $groupName . '</a ><br >
  <div class="col-2">'
                . self::buildRemoveFromGroupButton($user, new DomainGroup($group))
                . '</div> '
                . '</div> ';
            //var_dump($group);
        }
        $groups .= self::printAddGroupMemberModalButton(null, $user);


        $output .= self::printRow(Lang::get("Groups"), '');
        $output .= $groups;

        /**
         * Groups completed
         */

        return $output;
    }

    private static function printOptionsButton(DomainUser $user): string
    {
        /**
         * Move Button/Modal
         */


        $ad = AD::get();
        $tree = $ad->getAllSubOUs(DomainDatabase::getAD_BaseDN(1));
        $body = self::printUserOUTree($user->getDistinguishedName(), $tree) . '<br>Current location highlighted.';
        $body .= App::get()->view('scripts/moveToNewOU', ['object' => $user]);

        $moveButton = new FormDropdownOption('Move');
        $moveModal = new Modal();
        $moveModal->setId('move_user_modal')
            ->setBody($body)
            ->setTitle('Move User');
        $moveButton->addModal($moveModal);

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
        $optionsButton->addMenuOptions($uploadPhotoButton)
            ->addMenuOptions($newPasswordButton)
            ->addMenuOptions($moveButton);

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
        if (PermissionHandler::hasPermission($user->getOU(), PermissionLevel::USERS, PermissionLevel::USER_CHANGE)) {
            $output = $optionsButton->getElementHTML();
        }


        return $output . $uploadPhotoForm->print();
    }

    private static function printUserOUTree($objectDN, $ouTree)
    {
        $output = '';
        foreach ($ouTree as $name => $branch) {
            if (is_array($branch)) {
                $output .= self::printBranchOU($objectDN, $name, $branch);
            } else {

                $output .= self::printLeafOU($objectDN, $branch);
            }
        }
        return $output;
    }

    private static function printBranchOU($objectDN, $name, array $branch)
    {
        $displayName = self::leftOU($name);
        $disable = false;
        if (!PermissionHandler::hasPermission($name, PermissionLevel::USERS, PermissionLevel::USER_CHANGE)) {
            $disable = true;
        }

        $output = '<div class="container-fluid pr-0 text-left">'
            . '<a  class="d-inline-block clickable grow" data-toggle="collapse" role="button" data-target="#' . self::cleanOU($name) . '" data-text-alt="<i class=\'fas fa-caret-down\'></i>"  style="width:1em!important;">'
            . '<i class="fas fa-caret-right"></i>'
            . '</a>'
            . '<p class="d-inline-block clickable highlight ouLocationButton w-75 text-left pl-2 mb-0"  ';

        if (!$disable) {

            $output .= 'data-target-ou="' . $name . '"';

        }
        $output .= '>';
        if ($disable) {
            $output .= "<text class='text-danger'>";
        }


        $output .= $displayName;

        if ($disable) {
            $output .= "</text>";
        }

        $output .= '</p>';

        AppLogger::get()->info($name);
        AppLogger::get()->info($objectDN);
        AppLogger::get()->info(strpos($objectDN, $name));
        if (strpos($objectDN, $name) > 0) {
            $output .= '<div id="' . self::cleanOU($name) . '" class="collapse show container-fluid mx-auto my-0 pr-0">';
        } else {
            $output .= '<div id="' . self::cleanOU($name) . '" class="collapse container-fluid mx-auto my-0 pr-0">';

        }
        $output .= self::printUserOUTree($objectDN, $branch)
            . '</div>';
        $output .= '</div>';


        //var_dump($output);
        return $output;
    }

    private static function printLeafOU($objectDN, $leaf)
    {
        $displayName = self::leftOU($leaf);
        $disable = false;
        if (!PermissionHandler::hasPermission($leaf, PermissionLevel::USERS, PermissionLevel::USER_CHANGE)) {
            $disable = true;
        }

        $showOUButtonId = self::getHTML_ID_FromOU($leaf) . '_Show_OU_Button';

        $output = '<div class="row clickable text-left ml-4 w-100">';
        AppLogger::get()->info($leaf);
        AppLogger::get()->info($objectDN);
        if (strpos($objectDN, $leaf) === 0 || strpos($objectDN, $leaf) > 0) {
            $classes = " bg-primary text-light ouLocationButton ";
        } else {
            $classes = " ouLocationButton ";
        }

        $output .= '<div id="' . $showOUButtonId . '" class=" col-10 highlight ' . $classes . ' pl-2 pr-0 mb-0" onclick="" ';

        if (!$disable) {
            $output .= 'data-target-ou="' . $leaf . '"';
        }
        $output .= '>';

        if ($disable) {
            $output .= "<text class='text-danger'>";
        }

        $output .= $displayName;

        if ($disable) {
            $output .= "</text>";
        }
        $output .= '</div>';


        $output .= '</div>';
        return $output;
    }

    /**
     *
     * @param string $user
     *
     * @return string
     */
    private
    static function buildDisableAccountButton(DomainUser $user)
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
     * @param string $username
     * @param string $type
     *
     * @return string
     */
    private
    static function buildEnableAccountButton(DomainUser $user)
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
     * @param string $username
     * @param string $type
     *
     * @return string
     */
    private
    static function buildUnlockAccountButton(DomainUser $user)
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

    private
    static function buildResetPasswordMenuOption(DomainUser $user)
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
     * @param DomainUser $user
     *
     * @return string
     */
    private
    static function printUserHeader(DomainUser $user)
    {
        /**
         * make photo and update
         */
        $output = '<div class="col-sm mb-2 mb-0-sm ">';
        //var_dump($user->activeDirectory);
        if (!is_null($user->activeDirectory->getThumbnail())) {
            $output .= '<img class="userPortrait card-img-top mb-3  col-md-7 px-0 dark-shadow" src="data:image/jpeg;base64, ' . base64_encode($user->activeDirectory->getThumbnail()) . '"/>';
        }
        $output .= '</div>';
        return $output;
    }

    /**
     *
     * @param string $label
     * @param string $value
     *
     * @return string
     */
    private
    static function printRow($label, $value)
    {
        if ($label != null) {
            if (is_array($value)) {
                $output = self::printRow($label, null);
//'<div class="row"><div class="col h6">' . $label . '</div></div>';
                foreach ($value as $singleValue) {
                    $output .= '<div class="row pb-1"><div class="col-sm">' . $singleValue . '</div></div>';
                }
                return $output;
            } else {
                return '<div class="row pb-1"><div class="col-sm h6 mb-0">' . $label . '</div><div class="col-sm">' . $value . '</div></div>';
            }
        } else {
            return '<div class="row pb-1"><div class="col-sm h6 mb-0">' . $value . '</div></div>';
        }
    }

    /**
     *
     * @param DomainUser|DomainGroup $objectToRemove
     * @param DomainGroup $group
     *
     * @return string
     */
    protected
    static function buildRemoveFromGroupButton($objectToRemove, DomainGroup $group)
    {

        $form = new Form("/groups/edit", "remove_member", "post");
        $form->addClass('mb-0');
        $objectToRemoveInput = new FormText("distinguishedName");

        $objectToRemoveInput->hidden()
            ->setName("distinguishedName");
        if ($objectToRemove instanceof DomainUser) {
            $objectToRemoveInput->setValue($objectToRemove->getDistinguishedName());
        } else if ($objectToRemove instanceof DomainGroup) {
            $objectToRemoveInput->setValue($objectToRemove->getDistinguishedName());
        }
        $groupInput = new FormText("group");
        $groupInput->hidden()
            ->setName("group")
            ->setValue($group->activeDirectory->getAccountName());

        $action = new FormText("action");
        $action->hidden()
            ->setName("action")
            ->setValue("removeMember");


        $button = new FormButton('<i class="fas fa-trash-alt"></i>');
        $button->tiny()
            ->setTheme("danger")
            ->hideLabels()
            ->setTooltip("Remove from group");

        $form->addElementToNewRow($objectToRemoveInput)
            ->addElementToNewRow($groupInput)
            ->addElementToNewRow($action)
            ->addElementToNewRow($button);
        return $form->print();
    }

    /**
     *
     * @param DomainGroup $group
     *
     * @return string
     */
    private
    static function printAddGroupMemberModalButton(DomainGroup $group = null, DomainUser $user = null)
    {
        $modalForm = new Form('/groups/edit', 'addMemberToGroup');

        $modal = new Modal();

        $action = new FormText('', '', 'action', 'addMember');
        $action->hidden();

        if ($group === null) {
            $groupName = new FormText('Add to group', '', 'group');
            $groupName->setId('groupToAddMemberTo')
                ->autoCompleteGroupName();

            $userToAdd = new FormText('Add user', 'Can also search by first or last name.', 'username', $user->getUsername());
            $userToAdd->autoCompleteUsername()
                ->hidden();

            $modal->setTitle("Find Group");
        } elseif ($user === null) {
            $groupName = new FormText('', '', 'group', $group->activeDirectory->getDistinguishedName());
            $groupName->hidden();

            $userToAdd = new FormText('Add user or group', Lang::getHelp('Can also search by first or last name.'), 'usernameToAdd');
            $userToAdd->autoCompleteUsernameOrGroupName();

            $modal->setTitle("Find User or Group");
        } else {
            throw new AppException(Lang::getError("No user or group was supplied to the add group members modal"));
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
            ->setTooltip(Lang::getHelp("Add user or group to group"));
        return $modalButton->print();
    }

    /**
     *
     * @param DomainGroup $group
     * @param User $webUser
     *
     * @return string
     * @throws AppException
     */
    private
    static function buildGroupCard(DomainGroup $group)
    {


        $output = '<div class="col">
  <div class="card-body">';


        $output .= '<div class="row"><div class="col">';

        $output .= '<h5 class="position-relative d-inline card-title text-center p-5- m-5" style="left:0.5em">' . $group->activeDirectory->getName() . '</h5>';
        $output .= self::printGroupParents($group);
        if (PermissionHandler::hasPermission($group->getOU(), PermissionLevel::GROUPS, PermissionLevel::GROUP_DELETE)) {
            $output .= self::printDeleteGroupButton($group);
        }
        //$output .= '<a class="float-left mt-1 h4 clickable" style="z-index:3" href="' . Request::get()->getReferer() . '">';
        //$output .= '<i class="text-primary text-decoration-none fas fa-arrow-left"></i >'
        //    . '</a > ';
        $output .= '</div>';

        $output .= '</div>';

        if ($group->getEmail() != '') {
            $output .= self::printRow("Email", $group->getEmail());
        }

        $output .= self::printGroupMembers("", $group);
        $output .= self::printAddGroupMemberModalButton($group);
        $output .= '</div>';

        $output .= '</div>';
        return $output;
    }

    private
    static function printGroupParents(DomainGroup $group)
    {
        $parents = $group->getParents();
        if (!empty($parents)) {


            $output = '<div class="position-fixed w-100"><div class="row"><div class="clickable" data-toggle="collapse" data-target="#parentGroupsCollapse" data-text-alt="<i class=\'fas fa-caret-down\'></i>"><i class="fas fa-caret-right"></i></div>'
                . '<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 h6 ">Parent Groups</div></div>';
            $output .= '<div class="collapse" id="parentGroupsCollapse">';
            foreach ($parents as $parent) {

                $output .= '<div class="row"><div class="col-xl-2 col-lg-3 col-md-4 col-sm-6"><a href="/groups/search/' . $parent . '">' . $parent . '</a></div></div>';


            }
            $output .= '</div></div>';
            return $output;
        }
        return '';
    }

    private
    static function printDeleteGroupButton(DomainGroup $group)
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


        $deleteModal = new Modal();
        $deleteModal->setBody($modalForm->print())
            ->setId('deleteGroup')
            ->setTheme('danger ')
            ->setTitle("Delete " . $groupName);
        $deleteButton->addModal($deleteModal);
        return $deleteButton->print();
    }

    /**
     *
     * @param string $label
     * @param DomainGroup $group
     *
     * @return string
     */
    private
    static function printGroupMembers(string $label, DomainGroup $group)
    {
        if ($label !== null) {
            $groupMembers = $group->getMembers();
            $groupChildren = $group->getChildren();


            $output = self::printRow($label, null);
            $output .= "<br><br>";
            if (is_array($groupChildren) && !empty($groupChildren)) {
                $output .= '<div class="row"><div class="col h6">' . Lang::get("Groups") . '</div></div>';
                $output .= '<div class="row"><div class="col h6">' . Lang::get("Groups") . '</div></div>';
                /** @var DomainGroup $child */
                foreach ($groupChildren as $child) {
                    $output .= '<div class="row">'
                        . '<div class="col"><a href="/groups/search/' . $child->activeDirectory->getAccountName() . '">' . $child->activeDirectory->getName() . '</a></div>'
                        . '<div class="col">' . $child->activeDirectory->getDescription() . '</div>'
                        . '<div class="col">' . self::buildRemoveFromGroupButton($child, $group) . '</div>'
                        . '</div>';

                }
            }
            if (is_array($groupMembers) && !empty(($groupMembers))) {
                $output .= '<div class="row"><div class="col h6">' . Lang::get("Users") . '</div></div>';
                /* @var $user DomainUser */
                foreach ($groupMembers as $user) {
                    $output .= '<div class="row">'
                        . '<div class="col"><a href="/users/search/' . $user->getUsername() . '">' . $user->getUsername() . '</a></div>'
                        . '<div class="col">' . $user->getFullName() . '</div>'
                        . '<div class="col">' . self::buildRemoveFromGroupButton($user, $group) . '</div>'
                        . '</div>';
                }

            }
            return $output;
        }
        return '';
    }


}
