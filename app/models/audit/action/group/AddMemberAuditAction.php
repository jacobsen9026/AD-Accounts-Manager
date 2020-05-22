<?php


namespace App\Models\Audit\Action\Group;


use App\Models\Audit\Action\AuditAction;

class AddMemberAuditAction extends AuditAction
{

    /**
     * AddMemberAuditAction constructor.
     *
     * @param string $groupName
     * @param string $usernameAdded
     */
    public function __construct(string $groupName, string $usernameAdded)
    {

        $this->setType('Group_Member_Add');
        $this->setDescription('Added member: ' . $usernameAdded . ' to the group: ' . $groupName);

    }
}