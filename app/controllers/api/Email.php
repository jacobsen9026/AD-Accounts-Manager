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

namespace App\Controllers\Api;

/**
 * Description of Email
 *
 * @author cjacobsen
 */

use App\Models\Database\AppDatabase;
use App\Models\Database\EmailDatabase;
use System\App\AppLogger;
use System\Post;

class Email extends APIController
{

    /**
     *
     * @param type $to The email address to send a test to.
     */
    public function test($to = null)
    {
        //var_dump($to);
        if ($to == null) {
            $to = Post::get('to');
        }
        if ($this->user->superAdmin) {
            return $this->sendTest($to);
        }
    }


    public function sendTest($to = null)
    {
        return $this->returnHTML(\app\api\Email::sendMail($to, AppDatabase::getAppAbbreviation() . ' Email Test', 'Your email settings are correct!'));

    }


}
