<?php


namespace App\Models\Audit\Action\System;


use App\Models\Audit\Action\AuditAction;
use App\Models\User\User;

class UserLogonSuccessAuditAction extends AuditAction
{

    /**
     * UserLogonAuditAction constructor.
     *
     * @param string $username
     */
    public function __construct(User $user)
    {
        parent::__construct();

        $this->setUser($user);

        $this->setType('System_Login_Success');
        $this->setDescription('User successfully logged in: ' . $user->getUsername());

    }
}