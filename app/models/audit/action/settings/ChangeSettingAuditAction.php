<?php


namespace App\Models\Audit\Action\Settings;


use App\Models\Audit\Action\AuditAction;

class ChangeSettingAuditAction extends AuditAction
{

    /**
     * ChangeSettingAuditAction constructor.
     *
     * @param string $groupName
     * @param string $usernameAdded
     */
    public function __construct(string $settingName, $oldValue, $newValue)
    {
        parent::__construct();
        $this->setType('Setting_Change');
        $this->setDescription('Setting: ' . $settingName . ' changed from: ' . $oldValue . ' to: ' . $newValue);

    }
}