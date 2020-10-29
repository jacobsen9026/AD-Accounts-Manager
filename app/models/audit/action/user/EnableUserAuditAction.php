<?php


namespace App\Models\Audit\Action\User;

use App\Models\Audit\Action\AuditAction;

class EnableUserAuditAction extends AuditAction
{

    /**
     * EnableUserAuditAction constructor.
     *
     * @param string $usernameSearchedFor
     */
    public function __construct(string $usernameSearchedFor)
    {

        $this->setType('User_Enable');
        $this->setDescription('Enabled: ' . $usernameSearchedFor);

    }
}