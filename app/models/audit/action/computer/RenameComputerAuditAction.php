<?php


namespace App\Models\Audit\Action\Computer;


use App\Models\Audit\Action\AuditAction;

class RenameComputerAuditAction extends AuditAction
{

    /**
     * RenameComputerAuditAction constructor.
     *
     * @param string $computerName
     */
    public function __construct(string $computerName, string $newName)
    {

        $this->setType('Computer_Rename');
        $this->setDescription('Renamed computer: ' . $computerName . ' to: ' . $newName);

    }
}