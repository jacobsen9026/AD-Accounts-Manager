<?php


namespace App\Models\Audit\Action\Group;


use App\Models\Audit\Action\AuditAction;

class DeleteGroupAuditAction extends AuditAction
{

    /**
     * DeleteGroupAuditAction constructor.
     *
     * @param string $groupName
     */
    public function __construct(string $groupName)
    {

        $this->setType('Group_Delete');
        $this->setDescription('Deleted group: ' . $groupName);

    }
}