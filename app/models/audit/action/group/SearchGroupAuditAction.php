<?php


namespace App\Models\Audit\Action\Group;


use App\Models\Audit\Action\AuditAction;

class SearchGroupAuditAction extends AuditAction
{

    /**
     * SearchUserAuditAction constructor.
     *
     * @param string $groupSearchedFor
     */
    public function __construct(string $groupSearchedFor)
    {

        $this->setType('Group_Search');
        $this->setDescription('Searched for the group: ' . $groupSearchedFor);

    }
}