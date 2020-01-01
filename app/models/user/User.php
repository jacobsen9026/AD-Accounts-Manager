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

namespace app\models\user;

/**
 * Description of User
 *
 * @author cjacobsen
 */
use system\app\auth\CoreUser;

class User extends CoreUser {

    const THEME = "theme";
    const FULL_NAME = "fullName";

    public $theme = 'default';
    public $fullName;
    public static $instance;

    /**
     *
     * @param type $username
     * @return type
     */
    function __construct($username = null) {
        //set_error_handler(array($this, 'handleError'));
        //set_exception_handler(array($this, 'handleException'));
        if (isset(self::$instance)) {
            return self::$instance;
        } else {
            if ($username == CoreUser::ADMINISTRATOR) {
                $this->privilege = Privilege::TECH;
                $this->fullName = \system\Lang::get('Administrator Full Name');
                $this->username = "admin";
            } else {
                $this->privilege = Privilege::UNAUTHENTICATED;
            }
            self::$instance = $this;
        }
    }

    /**
     *
     * @return User
     */
    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     *
     * @return type
     */
    public function getTheme() {
        return $this->theme;
    }

    /**
     *
     * @return type
     */
    public function getFullName() {
        return $this->fullName;
    }

    /**
     *
     * @param type $theme
     * @return User
     */
    public function setTheme($theme) {
        $this->theme = $theme;
        \system\app\Session::setUser($this);
        return $this;
    }

    /**
     *
     * @param type $fullName
     * @return User
     */
    public function setFullName($fullName) {
        $this->fullName = $fullName;
        return $this;
    }

    //put your code here
}
