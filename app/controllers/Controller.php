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

namespace App\Controllers;

/**
 * Description of Default
 *
 * @author cjacobsen
 */

use App\Models\Audit\Action\AuditAction;
use App\Models\Audit\Action\System\UnauthorizedAccessAuditAction;
use App\Models\Audit\AuditEntry;
use App\Models\Database\AuditDatabase;
use System\App\AppException;
use System\App\RequestRedirection;
use System\Common\CommonController;
use App\Models\User\User;
use System\App\AppLogger;
use App\Models\Database\DomainDatabase;
use App\App\App;
use App\Models\District\Domain;

class Controller extends CommonController
{

    use RequestRedirection;

    //put your code here

    /** @var User|null The system logger */
    public $user;


    /** @var Domain The domain */
    public $domain;

    /**
     *
     * @var AppLogger
     */
    protected $logger;

    /* @var $app App */

    function __construct(App $app)
    {

        parent::__construct($app);
        $this->user = $app->user;
        $this->logger = $app->logger;
        $this->layout = "default";
    }

    /**
     * public function preProcessDistrictID($districtID)
     * {
     * $this->districtID = $districtID;
     * $this->domain = DomainDatabase::getDomain($this->districtID);
     * }
     */

    /**
     *
     * @return string
     */
    public function unauthorized()
    {
        $this->audit(new UnauthorizedAccessAuditAction($this->user->username, $this->app->route->getString()));
        throw new AppException('You\'ve entered a forbidden area.', AppException::UNAUTHORIZED_ACCESS);

        //return $this->view('errors/403');
    }

    protected function audit(AuditAction $action)
    {
        $auditEntry = new AuditEntry($this->app->request, $this->user, $action);
        AuditDatabase::addAudit($auditEntry);
    }


}

?>
