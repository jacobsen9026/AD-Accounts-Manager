<?php


namespace App\Controllers\Api;


use App\Models\Domain\DomainComputer;
use System\Post;

class Computers extends APIController
{
    /**
     * @var DomainComputer
     */
    protected DomainComputer $computer;


    /**
     * Computers constructor.
     */
    public function __construct()
    {
        $target = Post::get('target');

        $this->computer = new DomainComputer($target, false);
    }

    public function reboot()
    {
        $reponse = $this->computer->reboot();
        return $this->returnHTML($reponse);

    }

    public function shutdown()
    {
        $reponse = $this->computer->shutdown();
        return $this->returnHTML($reponse);
    }

    public function getInfo()
    {
        if ($this->computer->isOnline()) {
            $reponse["Online"] = 'Yes';
            $reponse["Manufacturer"] = $this->computer->getManufacturer();
            $reponse["Model"] = $this->computer->getModel();
            $reponse["Processor"] = $this->computer->getProcessor();
            $reponse["Memory"] = $this->computer->getPhysicalMemorySize();
        } else {
            $reponse["Online"] = 'No';

        }
        return ($reponse);
    }
}