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

namespace app\lang\en;

/**
 * Description of Common
 *
 * @author cjacobsen
 */

use app\lang\Language;

abstract class ENCommon
{

    use Language;

    //put your code here

    public static $strings = [
        'Administrator Full Name' => 'Administrator',
        'Login' => 'Login',
        'Remember Username' => 'Remember Username',
        'Remember Me' => 'Remember Me',
        'Username' => 'Username',
        'Password' => 'Password',
        'Group' => 'Group',
        'Groups' => 'Groups',
        'Users' => 'Users',
        'Search' => 'Search',
        'User Search' => 'User Search',
        'Group Search' => 'Group Search',
        'Application' => 'Application',
        'Authentication' => 'Authentication',
        'Email' => 'Email',
        'First Name' => 'First Name',
        'Last Name' => 'Last Name',
        'Initials' => 'Initials',
        'Full Name' => 'Full Name',
        'Logon Name' => 'Logon Name',
        'Notification' => 'Notification',
        'Update' => 'Update',
        'Create' => 'Create',
        'Group Name' => 'Group Name',
        'Description' => 'Description',
        'Email Address' => 'Email Address',
        'OU' => 'OU',
        'New Version Available!' => 'New Version Available!',
        'Domain Setup' => 'Domain Setup',
        'Audit Log' => 'Audit Log',
        'From' => 'From',
        'To' => 'To',
        'Timestamp' => 'Timestamp',
        'IP' => 'IP',
        'Action' => 'Action',
        'Defaults' => 'Defaults',
        'Permissions' => 'Permissions',
        'Backup' => 'Backup'
    ];
    public static $help = [
        'User_Search' => 'Can also enter first or last name to search for username.',
        'Group_Search' => "Can search by name, email, or description",
        "Add user or group to group" => "Add user or group to group",
        'Can also search by first or last name.' => 'Can also search by first or last name.',
        'Times are in UTC' => 'Times are in UTC',
        'Privilege_Levels' => 'Privilege levels can be assigned at the Domain or OU level with propagation. So a group with permission for a School OU will have that same permission for all sub-OU\'s, unless explicitly set at a lower level.',
        'Permissions' => 'Permissions are different for users or groups, but they both are successive levels of access that inherit the previous level. A group with Change permission can also Read, but can not Add or Delete (for groups).',
        'Super_Admin' => 'Privilege Levels with Super Admin enabled have permission to the whole directory as well as settings and setup pages.'
    ];

    public static $error = [
        "No user or group was supplied to the add group members modal" => "No user or group was supplied to the add group members modal",
        'Object not found' => 'Object not found',
    ];

}
