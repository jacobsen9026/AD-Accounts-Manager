<?php


namespace App\Models\Audit\Action\Computer;


use App\Models\Audit\Action\AuditAction;

class RestartComputerAuditAction extends AuditAction
{

    /**
     * RestartComputerAuditAction constructor.
     *
     * @param string $computerName
     */
    public function __construct(string $computerName)
    {

        $this->setType('Computer_Restart');
        $this->setDescription('Restarted computer: ' . $computerName);

    }
}