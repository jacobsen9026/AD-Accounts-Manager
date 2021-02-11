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
 * Description of Authentication
 *
 * @author cjacobsen
 */

use App\Models\Database\AuditDatabase;
use DateInterval;
use DateTime;
use System\Post;

class Audit extends SettingsController
{

    public function index()
    {

        $this->tab = 'audit';

        $this->fromTime = new DateTime();
        $this->toTime = new DateTime();
        $this->fromTime->sub(new DateInterval('P1D'));
        $this->fromTime = $this->fromTime->format('Y-m-d') . "T" . $this->fromTime->format('H:i');
        $this->toTime = $this->toTime->format('Y-m-d') . "T" . $this->toTime->format('H:i');

        $this->audit = AuditDatabase::getLast24Hrs();

        return $this->view('settings/audit');
    }


    public function indexPost()
    {

        $this->fromTime = Post::get("fromTime");
        $this->toTime = Post::get("toTime");
        $fromTime = new DateTime($this->fromTime);
        $toTime = new DateTime($this->toTime);
        $this->tab = 'audit';
        $this->audit = AuditDatabase::getBetween($fromTime->getTimestamp(), $toTime->getTimestamp());


        return $this->view('settings/audit');
    }

}
