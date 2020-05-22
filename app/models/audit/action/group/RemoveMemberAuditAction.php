<?php


namespace App\Models\Audit\Action\Group;


use App\Models\Audit\Action\AuditAction;

class RemoveMemberAuditAction extends AuditAction
{

    /**
     * RemoveMemberAuditAction constructor.
     *
     * @param string $groupName
     * @param string $usernameRemoved
     */
    public function __construct(string $groupName, string $usernameRemoved)
    {

        $this->setType('Group_Member_Remove');
        $this->setDescription('Removed member: ' . $usernameRemoved . ' to the group: ' . $groupName);

    }
}