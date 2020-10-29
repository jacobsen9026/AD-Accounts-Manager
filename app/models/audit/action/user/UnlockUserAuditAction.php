<?php


namespace App\Models\Audit\Action\User;

use App\Models\Audit\Action\AuditAction;

class UnlockUserAuditAction extends AuditAction
{

    /**
     * UnlockUserAuditAction constructor.
     *
     * @param string $usernameSearchedFor
     */
    public function __construct(string $usernameSearchedFor)
    {

        $this->setType('User_Unlock');
        $this->setDescription('Unlocked: ' . $usernameSearchedFor);

    }
}