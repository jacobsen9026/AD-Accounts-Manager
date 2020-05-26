<?php


namespace App\Models\Audit\Action;


class SearchUserAudit extends AuditAction
{

    /**
     * SearchUserAudit constructor.
     */
    public function __construct($usernameSearchedFor)
    {

        $this->setType('Search_User');
        $this->getDescription('Searched for the username: '.$usernameSearchedFor);

    }
}