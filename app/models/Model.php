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

namespace App\Models;

/**
 * Description of Model
 *
 * @author cjacobsen
 */

use App\App\App;
use App\Models\Audit\Action\AuditAction;
use App\Models\Audit\AuditEntry;
use App\Models\Database\AuditDatabase;
use System\App\AppLogger;
use System\Log\CommonLogger;
use System\Traits\Parser;

class Model
{
    use Parser;

    /**
     *
     * @var CommonLogger
     */
    protected $logger;

    public function __construct()
    {
        $this->logger = AppLogger::get();
    }

    protected function audit(AuditAction $action)
    {
        $auditEntry = new AuditEntry(App::get()->request, App::get()->user, $action);
        AuditDatabase::addAudit($auditEntry);
    }

}
