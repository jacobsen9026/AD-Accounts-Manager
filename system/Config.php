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


/*
 * Core Config
 *
 * This file contains the hard coded core configuration settings.
 * Do not change anything in here unless you know what you are doing
 */


/*
 * The Language of the app. The value must be a valid folder with contents in the lang directory of the app.
 */
define('DEFAULT_LANG', 'en');
/*
 * The System directory
 */
define('SYSTEMPATH', ROOTPATH . DIRECTORY_SEPARATOR . "system");
/*
 * The Application directory
 */
define('APPPATH', ROOTPATH . DIRECTORY_SEPARATOR . "app");
/*
 * The View directory under the application directory
 */
define('VIEWPATH', ROOTPATH . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "views");
/*
 * The Config directory under the application directory
 */
define('CONFIGPATH', ROOTPATH . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "config");
/*
 * The Database fie path under the application directory
 */
define('APPCONFIGDBPATH', APPPATH . DIRECTORY_SEPARATOR . "database" . DIRECTORY_SEPARATOR . "config.db");
/*
 * The Database fie path under the application directory
 */
define('APPAUDITDBPATH', APPPATH . DIRECTORY_SEPARATOR . "database" . DIRECTORY_SEPARATOR . "audit.db");
/*
 * The Class name with namespace to launch
 */
define('APPCLASS', "system\app\App");
/*
 * Toggle for core debug mode. Has no effect on app debug, but does allow App output directly.
 */
define('DEBUG_MODE', TRUE);

$this->include('system/schema.php');

/*
 * Include other config files like the example below
 * $this->include('system/example.php');
 */
?>
