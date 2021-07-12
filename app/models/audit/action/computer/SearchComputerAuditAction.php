<?php


namespace App\Models\Audit\Action\Computer;


use App\Models\Audit\Action\AuditAction;

class SearchComputerAuditAction extends AuditAction
{

    /**
     * SearchComputerAuditAction constructor.
     *
     * @param string $computerSearchedFor
     */
    public function __construct(string $computerSearchedFor)
    {

        $this->setType('Computer_Search');
        $this->setDescription('Searched for the computer: ' . $computerSearchedFor);

    }
}