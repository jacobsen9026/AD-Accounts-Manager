<?php


namespace App\Models\Audit\Action\System;


use App\Models\Audit\Action\AuditAction;

class UnauthorizedAccessAuditAction extends AuditAction
{

    /**
     * UnauthorizedAccessAuditAction constructor.
     *
     * @param string $username
     */
    public function __construct(string $username, $intent)
    {

        $this->setType('System_Unauthorized_Access');
        $this->setDescription('User: ' . $username . ' forbidden from: ' . $intent);

    }
}