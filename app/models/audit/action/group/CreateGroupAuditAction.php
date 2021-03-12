<?php


namespace App\Models\Audit\Action\Group;


use App\Models\Audit\Action\AuditAction;
use App\Models\Domain\DomainGroup;

class CreateGroupAuditAction extends AuditAction
{

    /**
     * DeleteGroupAuditAction constructor.
     *
     * @param DomainGroup $group
     */
    public function __construct(DomainGroup $group)
    {

        $this->setType('Group_Create');
        $this->setDescription('Created group: ' . $group->activeDirectory->getName() . ' at ' . $group->getOU());

    }
}