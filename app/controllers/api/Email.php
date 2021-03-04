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
// Instantiation and passing `true` enables exceptions
        $mail = EmailDatabase::createMailer();
//Recipients
        try {

            $mail->addAddress($to);
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = AppDatabase::getAppAbbreviation() . ' Email Test';
            $mail->Body = 'Your email settings are correct!';

            AppLogger::get()->debug(EmailDatabase::getSMTPServer());

            if (\App\App\App::inDebugMode()) {
                $debugLog = [];
                $mail->Debugoutput = function ($string, $level) {
                    $debugLog[] = $level . ': ' . $string;
                };
                $mail->SMTPDebug = 1;
            }
            $mail->send();
            if (\App\App\App::inDebugMode()) {
                AppLogger::get()->debug($debugLog);
            }
            return $this->returnHTML('Message has been sent successfully');
        } catch (Exception $e) {
            return $this->returnHTML("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }

}
