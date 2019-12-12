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

namespace app\config;

/**
 * Description of EmailConfig
 *
 * @author cjacobsen
 */
use system\common\CoreConfig;

class EmailConfig extends CoreConfig {

    //put your code here
    protected $fromAddress = null;
    protected $fromName = null;
    protected $admins = null;
    protected $welcomeBCC = null;
    protected $welcomeEmail = null;
    protected $smtpServer = null;
    protected $smtpPort = null;
    protected $useSMTPAuth = null;
    protected $useSMTPSSL = null;
    protected $smtpUsername = null;
    protected $smtpPassword = null;
    protected $replyToAddress = null;
    protected $replyToName = null;

}
