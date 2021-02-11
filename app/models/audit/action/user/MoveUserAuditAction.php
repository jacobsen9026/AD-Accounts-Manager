<?php


namespace App\Models\Audit\Action\User;

use App\Models\Audit\Action\AuditAction;

class MoveUserAuditAction extends AuditAction
{

    /**
     * MoveUserAuditAction constructor.
     *
     * @param string $username
     */
    public function __construct(string $username, string $destinationOU)
    {

        $this->setType('User_Move');
        $this->setDescription('Moved: ' . $username . ' to : ' . $destinationOU);

    }
}