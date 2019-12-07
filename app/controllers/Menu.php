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

/**
 * Description of Menu
 *
 * @author cjacobsen
 */

namespace app\controllers;

use app\controllers\menu\TopMenuItem;
use app\controllers\menu\SubMenuItem;
use system\app\AppLogger;
use system\Parser;
use app\models\user\Privilege;
use app\App;

class Menu extends Parser {

//put your code here
    public $items;
    public $layout;
    public $config;

    /** @var AppLogger|null The app logger */
    public $logger;
    public $userPrivs;

    function __construct($userPrivs, $layout = 'default') {
        $app = App::get();
        $this->config = $app->config;
        $this->layout = $layout;
        $this->logger = AppLogger::get();
        $this->userPrivs = $userPrivs;
        $this->logger->info('Menu Creation Started UserPrivilege:' . $userPrivs);
        /*
         * Create top level items
         */
        if ($this->userPrivs > Privilege::UNAUTHENTICATED) {
            $this->items[] = $this->buildStudentMenu();
            if ($this->userPrivs > Privilege::POWER) {
                $this->logger->debug("Building Parent and Staff Menus");
                $this->items[] = $this->buildParentMenu();
                $this->items[] = $this->buildStaffMenu();
                // Test of privilege
                if ($this->userPrivs > \app\models\user\Privilege::TECH - 1) {
                    $this->items[] = $this->buildTechMenu();
                }
            }
        }
    }

    private function buildStudentMenu() {
        /*
         * Build Student Menu
         */

        $students = new TopMenuItem('Students');
        $students->addSubItem(new SubMenuItem('Account Status', '/' . strtolower($students->displayText) . '/account-status'));
        if ($this->userPrivs > Privilege::BASIC) {
            $students->addSubItem(new SubMenuItem('Account Change', '/' . strtolower($students->displayText) . '/account-change'));
            $students->addSubItem(new SubMenuItem('Google Classroom', '/' . strtolower($students->displayText) . '/google-classroom'));
            $students->addSubItem(new SubMenuItem('Google Groups', '/' . strtolower($students->displayText) . '/google-groups'));
            $students->addSubItem(new SubMenuItem('H-Drive', '/' . strtolower($students->displayText) . '/home-drive'));
            $students->addSubItem(new SubMenuItem('New Password', '/' . strtolower($students->displayText) . '/reset-password'));
        }
        if ($this->userPrivs > Privilege::ADMIN) {
            $students->addSubItem(new SubMenuItem('Create Acccounts', '/' . strtolower($students->displayText) . '/create-accounts'));
        }
        return $students;
    }

    private function buildStaffMenu() {
        /*
         * Build Staff Menu
         */
        $staff = new TopMenuItem('Staff');
        if ($staff) {
            $staff->addSubItem(new SubMenuItem('Account Status', '/' . strtolower($staff->displayText) . '/account-status'));
            $staff->addSubItem(new SubMenuItem('Account Change', '/' . strtolower($staff->displayText) . '/account-change'));
            $staff->addSubItem(new SubMenuItem('Google Groups', '/' . strtolower($staff->displayText) . '/google-groups'));
            $staff->addSubItem(new SubMenuItem('New Password', '/' . strtolower($staff->displayText) . '/reset-password'));
            $staff->addSubItem(new SubMenuItem('Create Acccounts', '/' . strtolower($staff->displayText) . '/create-accounts'));
            $staff->addSubItem(new SubMenuItem('Send Welcome Email', '/' . strtolower($staff->displayText) . '/welcome-email'));
        }
        return $staff;
    }

    private function buildParentMenu() {
        /*
         * Build Parent Menu
         */
        $parents = new TopMenuItem('Parents');
        if ($parents) {
            $parents->addSubItem(new SubMenuItem('Google Groups Check', '/' . strtolower($parents->displayText) . '/get-groups'));
            $parents->addSubItem(new SubMenuItem('Manage Google Groups', '/' . strtolower($parents->displayText) . '/set-groups'));
        }
        return $parents;
    }

    private function buildTechMenu() {
        /*
         * Build Tech Menu
         */
        $tech = new TopMenuItem('Tech');
        if ($tech) {
            $tech->addSubItem(new SubMenuItem('Google', '/' . strtolower($tech->displayText) . '/google-accounts'));
            $tech->addSubItem(new SubMenuItem('Google Drive', '/' . strtolower($tech->displayText) . '/google-drive'));
            $tech->addSubItem(new SubMenuItem('Computers', '/' . strtolower($tech->displayText) . '/workstation-manage'));
        }
        return $tech;
    }

    public function getMenu() {
        $this->app = App::get();
        //$this->logger->debug($this->items);
        return $this->view('/layouts/' . $this->layout . '_navbar');
    }

}
