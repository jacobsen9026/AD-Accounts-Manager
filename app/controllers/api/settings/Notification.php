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

namespace App\Controllers\Api\settings;

/**
 * Description of Application
 *
 * @author cjacobsen
 */

use App\Controllers\Api\APIController;
use App\Models\Audit\Action\Settings\ChangeSettingAuditAction;
use App\Models\Database\EmailTemplateDatabase;
use app\models\view\EmailTemplateInterpreter;
use System\App\AppException;
use System\App\AppLogger;
use System\Post;

class Notification extends APIController
{

    //put your code here

    public function indexPost()
    {
        if (!$this->user->superAdmin) {
            throw new AppException('You\'ve entered a forbidden area.', AppException::UNAUTHORIZED_ACCESS);
        }
        //$notification = new \App\Controllers\Settings\Notification($this->app);
        //$notification->indexPost();

        return $this->returnHTML($this->view('settings/notification'));
    }

    public function update()
    {
        $id = Post::get("id");
        if ($id != null) {
            $name = Post::get("name");
            $subject = Post::get("subject");
            $body = Post::get("body");
            $cc = Post::get("cc");
            $bcc = Post::get("bcc");
            $oldTemplate = EmailTemplateDatabase::get($id);
            $body = str_replace("'", "''", $body);
            EmailTemplateDatabase::updateTemplate($id, $name, $subject, $body, $cc, $bcc);
            $this->audit(new ChangeSettingAuditAction('Update email template name ' . $id, $oldTemplate["Name"], $name));
            $this->audit(new ChangeSettingAuditAction('Update email template subject' . $id, $oldTemplate["Subject"], $subject));
            //$this->audit(new ChangeSettingAuditAction('Update email template body' . $id, $oldTemplate["Body"], $body));

        }
    }

    public function interpretPost()
    {
        $body = Post::get("body");
        AppLogger::get()->debug($body);
        return $this->returnHTML(EmailTemplateInterpreter::interpret($body));
    }

}
