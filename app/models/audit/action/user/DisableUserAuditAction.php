<?php


namespace App\Models\Audit\Action\User;

use App\Models\Audit\Action\AuditAction;

class DisableUserAuditAction extends AuditAction
{

    /**
     * DisableUserAuditAction constructor.
     *
     * @param string $usernameSearchedFor
     */
    public function __construct(string $usernameSearchedFor)
    {

        $this->setType('User_Disable');
        $this->setDescription('Disabled: ' . $usernameSearchedFor);

    }
}