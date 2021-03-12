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

namespace App\Models\Database;

/**
 * Description of UserNotification
 *
 * @author cjacobsen
 */

use App\Models\User\PrivilegeLevel;
use App\Models\User\Permission;
use App\Models\User\User;
use System\App\AppException;
use System\App\AppLogger;
use System\App\LDAPLogger;
use System\App\UserLogger;
use System\Traits\DomainTools;

abstract class UserNotificationDatabase extends DatabaseModel
{
    use DomainTools;

    const TABLE_NAME = 'UserNotification';

    public static function setUserOption(User $user)
    {
        if (self::getUserOptions($user->getId()) != null) {
            $query = new Query(self::TABLE_NAME, Query::UPDATE);
            $query->set('Notify_User_Change', (int)$user->getNotificationOptions()->isUserChange())
                ->set('Notify_User_Disable', (int)$user->getNotificationOptions()->isUserDisable())
                ->set('Notify_User_Create', (int)$user->getNotificationOptions()->isUserCreate())
                ->set('Notify_Group_Change', (int)$user->getNotificationOptions()->isGroupChange())
                ->set('Notify_Group_Create', (int)$user->getNotificationOptions()->isGroupCreate())
                ->where("User_ID", $user->getId());
            return $query->run();
        } else {
            $query = new Query(self::TABLE_NAME, Query::INSERT);
            $query->insert('Notify_User_Change', (int)$user->getNotificationOptions()->isUserChange())
                ->insert('Notify_User_Disable', (int)$user->getNotificationOptions()->isUserDisable())
                ->insert('Notify_User_Create', (int)$user->getNotificationOptions()->isUserCreate())
                ->insert('Notify_Group_Change', (int)$user->getNotificationOptions()->isGroupChange())
                ->insert('Notify_Group_Create', (int)$user->getNotificationOptions()->isGroupCreate())
                ->insert("User_ID", $user->getId());
            return $query->run();

        }
    }

    public static function getUserOptions($getId)
    {
        $query = new Query(self::TABLE_NAME);
        $query->where("User_ID", $getId);
        return $query->run()[0];
    }

}
