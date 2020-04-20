<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\district;

/**
 * Description of CardPrinter
 *
 * @author cjacobsen
 */
use app\models\user\User;
use app\models\district\User as DistrictUser;
use app\models\user\Privilege;
use system\app\forms\FormButton;

abstract class CardPrinter {
    //put your code here

    /**
     *
     * @param DistrictUser $user
     * @param User $webUser
     * @return string
     */
    private static function buildUserCard($user, $webUser) {
        $output = '<script>
            $(function () {$(\'[data-toggle="tooltip"]\').tooltip()})
  </script>
            <div class="col">
      <!--   <i class="fas fa-user-circle"></i>   -->

  <div class="card-body">'
                . '<div class="row">';

        if (!$user->getEnabled()) {
            $output .= '<div class="col text-danger h3"><i data-toggle="tooltip" data-placement="top" title="Account is not enabled." class="fas fa-times-circle"></i>';

            if ($webUser->getPrivilege() >= Privilege::POWER) {
                $output .= self::buildEnableAccountButton($user->getAdUsername());
            }
            $output .= '</div>';
        } else {
            $output .= '<div class="col text-success h3"><i data-toggle="tooltip" data-placement="top" title="Account is enabled." class="fas fa-check-circle"></i>';

            if ($webUser->getPrivilege() >= Privilege::POWER) {
                $output .= self::buildDisableAccountButton($user->getAdUsername());
            }
            $output .= '</div>';
        }
        $output .= self::printUserPhoto($user);

        if (!$user->getLockedOut()) {
            $output .= '<div class="col text-success h3"><i data-toggle="tooltip" data-placement="top" title="Account is not locked out." class="fas fa-lock-open"></i>';

            $output .= '</div>';
        } else {
            $output .= '<div class="col text-danger h3"><i data-toggle="tooltip" data-placement="top" title="Account is locked out." class="fas fa-lock-closed"></i>';
            if ($webUser->getPrivilege() >= Privilege::POWER) {
                $output .= self::buildUnlockAccountButton($user->getAdUsername());
            }
            $output .= '</div>';
        }
        $output .= '</div>';
        $output .= '<div class="row"><div class="col">';
        $output .= '<h5 class="card-title text-center">' . $user->getAdCommonName() . '</h5>';

        $output .= '</div>';

        $output .= '</div>';
        if ($user instanceof Student) {
            $output .= self::printRow("Student ID", '<a href="https://genesis.genesisedu.com/branchburg/sis/view?module=studentdata&category=modifystudent&tab1=demographics&tab2=required&action=form&studentid=' . $user->getEmployeeID() . '">' . $user->getEmployeeID() . '</a>');
        } else {

            $output .= self::printRow("Empoyee ID", $user->getEmployeeID());
        }
        $output .= self::printRow("First Name", $user->getADFirstName());
        $output .= self::printRow("Middle Name", $user->getADMiddleName());
        $output .= self::printRow("Last Name", $user->getLastName());

        $output .= '<br/>';
        $output .= self::printRow("Home Phone", $user->getHomePhone());
        $output .= self::printRow("Street Address", $user->getStreet());
        $output .= self::printRow("City", $user->getCity());
        $output .= self::printRow("State", $user->getState());
        $output .= self::printRow("Zip Code", $user->getZip());

        $output .= '<br/>';

        $output .= self::printRow(null, $user->getAdDepartment());
        $output .= self::printRow(null, $user->getDescription());
        $output .= self::printRow(null, $user->getAdCompany());
        $output .= self::printRow(null, $user->getOffice());

        $output .= '<br/>';


        $output .= self::printRow("Username", $user->getAdUsername());
        $output .= self::printRow("Email Address", $user->getAdEmail());
        $output .= self::printRow("Groups", var_export($user->getAdGroups(), true));

        //$output .= '<div class="row"><div class="col h6">Username</div><div class="col">'.$user->getAdUsername().'</div></div>';
        //$output .= '<div class="row"><div class="col h6">Email Address</div><div class="col">'.$user->getAdEmail().'</div></div>';
        //$output .= '<div class="row"><div class="col h6">Groups</div><div class="col">'.var_export($user->getAdGroups(),true).'</div></div></div>';

        return $output;
    }

    private static function printRow($label, $value) {
        if ($label != null) {
            return '<div class="row"><div class="col h6">' . $label . '</div><div class="col">' . $value . '</div></div>';
        } else {

            return '<div class="row"><div class="col h6">' . $value . '</div></div>';
        }
    }

    public static function printCard($object, $user) {
        $class = get_class($object);
        //var_dump($class);
        switch ($class) {
            case Student::class:
                return self::buildUserCard($object, $user);
            case Staff::class:
                return self::buildUserCard($object, $user);
        }
    }

    private static function buildDisableAccountButton($username) {
        $form = new \system\app\forms\Form("/students/account-status-change", "disable_account", "post");
        $userInput = new \system\app\forms\FormText("username");
        //var_dump($username);

        $userInput->hidden()
                ->setName("username")
                ->setValue($username);

        $action = new \system\app\forms\FormText("action");
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
        $form->buildSubmitButton("Disable");
        return $form->print();
    }

    private static function buildEnableAccountButton($username) {
        $form = new \system\app\forms\Form("/students/account-status-change", "disable_account", "post");


        $userInput = new \system\app\forms\FormText("username");
        $userInput->hidden()
                ->setName("username")
                ->setValue($username);


        $action = new \system\app\forms\FormText("action");
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
        $form->buildSubmitButton("Enable");
        return $form->print();
    }

    private static function buildUnlockAccountButton($username) {
        $form = new \system\app\forms\Form("/students/account-status-change", "unlock_account", "post");
        $userInput = new \system\app\forms\FormText("username");
        //var_dump($username);
        $userInput->hidden()
                ->setName("username")
                ->setValue($username);
        $button = new FormButton("Unlock");
        $button->small()
                ->setTheme("success");
        $form->addElementToNewRow($userInput)
                ->addElementToNewRow($button);
        $form->buildSubmitButton("Unlock");
        return $form->print();
    }

    private static function printUserPhoto($user) {
        if (!is_null($user->getPhoto())) {
            $photo = '<div class="col-sm mb-2 mb-0-sm "><img class="card-img-top" style="width:100px;" src="data:image/jpeg;base64,' . base64_encode($user->getPhoto()) . '"/>'
                    . '</div>';
        }
        return $photo;
    }

}
