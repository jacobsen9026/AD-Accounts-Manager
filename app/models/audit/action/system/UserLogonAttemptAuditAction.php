<?php


namespace App\Models\Audit\Action\System;


use App\Models\Audit\Action\AuditAction;

class UserLogonAttemptAuditAction extends AuditAction
{

    /**
     * UserLogonAttemptAuditAction constructor.
     *
     * @param string $username
     */
    public function __construct(string $username)
    {

        $this->setType('System_Login_Attempt');
        $this->setDescription('User attempt to log in: ' . $username);

    }
}