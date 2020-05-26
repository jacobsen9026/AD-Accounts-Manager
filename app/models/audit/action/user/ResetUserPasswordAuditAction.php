<?php

namespace App\Models\Audit\Action\User;

use App\Models\Audit\Action\AuditAction;

class ResetUserPasswordAuditAction extends AuditAction
{

    /**
     * ResetUserPasswordAuditAction constructor.
     *
     * @param null $username
     */
    public function __construct($username)
    {
        $this->setType('User_Reset_Password');
        $this->setDescription('Password reset for the username: ' . $username);

    }
}