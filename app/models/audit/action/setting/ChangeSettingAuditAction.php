<?php


namespace App\Models\Audit\Action\Settings;


use App\Models\Audit\Action\AuditAction;

class ChangeSettingAuditAction extends AuditAction
{

    /**
     * AddMemberAuditAction constructor.
     *
     * @param string $groupName
     * @param string $usernameAdded
     */
    public function __construct(string $settingName, $oldValue, $newValue)
    {

        $this->setType('Group_Member_Add');
        $this->setDescription('User member: ' . $usernameAdded . ' to the group: ' . $groupName);

    }
}