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

namespace App\Controllers\Settings;

/**
 * Description of user
 *
 * @author cjacobsen
 */

use App\App\App;
use App\Controllers\Controller;
use App\Models\User\NotificationOptions;
use System\App\UserLogger;
use System\Post;

class Profile extends Controller
{

    public function __construct(App $app)
    {
        parent::__construct($app);
        //$this->layout = "thin";
    }

    //put your code here
    public function index()
    {
        return $this->view('settings/profile');
    }

    public function indexPost()
    {

        $this->user->setTheme(Post::get('theme'));
        $this->user->setEmail(Post::get('email'));
        $options = new NotificationOptions();
        $options->setUserChange(Post::get('notify_user_changes'));
        $options->setUserDisable(Post::get('notify_user_enable'));
        $options->setUserCreate(Post::get('notify_user_create'));
        $options->setGroupChange(Post::get('notify_group_change'));
        $options->setGroupCreate(Post::get('notify_group_create'));
        UserLogger::get()->debug($options);
        $this->user->setNotificationOptions($options);
        $this->user->save();
        $this->redirect('/settings/profile');
        //return $this->view('/settings/profile');
    }

}
