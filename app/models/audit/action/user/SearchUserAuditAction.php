<?php


namespace App\Models\Audit\Action\User;

use App\Models\Audit\Action\AuditAction;

class SearchUserAuditAction extends AuditAction
{

    /**
     * SearchUserAuditAction constructor.
     *
     * @param string $usernameSearchedFor
     */
    public function __construct(string $usernameSearchedFor)
    {

        $this->setType('User_Search');
        $this->setDescription('Searched for the username: ' . $usernameSearchedFor);

    }
}