<?php


namespace App\Models\Audit\Action\User;

use App\Models\Audit\Action\AuditAction;
use App\Models\Domain\DomainUser;

class CreateUserAuditAction extends AuditAction
{

    /**
     * CreateUserAuditAction constructor.
     *
     * @param DomainUser $user
     */
    public function __construct(DomainUser $user)
    {

        $this->setType('User_Created');
        $this->setDescription('Created: ' . $user->getUsername() . ' at ' . $user->getOU());

    }
}