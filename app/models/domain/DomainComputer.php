<?php


namespace App\Models\Domain;


use App\Api\Ad\ADComputers;
use App\Api\Windows\PowerShell;
use System\App\AppLogger;

class DomainComputer extends ADModel
{
    protected string $name;
    protected string $ip = '';
    protected ?string $os = '';
    protected ?string $osServicePack = '';
    protected ?string $osVersion = '';
    protected ?bool $online = null;
    protected bool $sessionConnected = false;
    protected bool $exists;

    /**
     * DomainComputer constructor.
     * @param string $name
     */
    public function __construct(string $name, $importFromAD = true)
    {
        if (filter_var($name, FILTER_VALIDATE_IP)) {
            $this->ip = $name;

            $name = explode(".", gethostbyaddr($name))[0];
        }
        $this->name = $name;
        if ($importFromAD) {
            $rawComputer = ADComputers::getComputer($name);
            if ($rawComputer !== false) {
                $this->exists = true;
                AppLogger::get()->debug($rawComputer);
                $this->os = $rawComputer['operatingsystem'][0];
                if (!is_null($rawComputer['operatingsystemservicepack'])) {
                    $this->osServicePack = $rawComputer['operatingsystemservicepack'][0];
                }
                $this->osVersion = $rawComputer['operatingsystemversion'][0];
            } else if ($this->getIp() !== null) {
                $this->exists = true;
            } else {
                $this->exists = false;
            }

        }
    }

    public function getIp()
    {
        if ($this->ip == '') {
            $dns = dns_get_record($this->getName());
            if (is_array($dns) && key_exists(0, $dns)) {

                if (key_exists('ip', $dns[0])) {
                    $this->ip = $dns[0]["ip"];

                }
            }
        }
        return $this->ip;
        /**
         * if ($this->ip == '') {
         * putenv('RES_OPTIONS=retrans:0 retry:0 timeout:1 attempts:1');
         * $ip = gethostbyname($this->getName());
         * if (filter_var($ip, FILTER_VALIDATE_IP)) {
         * $this->ip = $ip;
         * return $ip;
         * }
         * } else {
         * return $this->ip;
         * }
         */
    }

    /**
     * @return string
     */
    public
    function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return DomainComputer
     */
    public function setName(string $name): DomainComputer
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed|string|null
     */
    public function getOsServicePack(): ?string
    {
        return $this->osServicePack;
    }

    /**
     * @return mixed|string|null
     */
    public function getOsVersion(): ?string
    {
        return $this->osVersion;
    }

    /**
     * @return string
     */
    public function getOs(): ?string
    {
        return $this->os;
    }

    public function isOnline()
    {
        if ($this->online == null) {
            AppLogger::get()->info("Checking if " . $this->getName() . " is online");
            $ip = $this->getIp();
            AppLogger::get()->info("Found IP of: " . $ip);
            //var_dump($ip);

            if (!is_null($ip)) {
                $wait = 1;

                $socket = @\fsockopen($ip, 445, $errCode, $errStr, $wait);
                if ($socket) {

                    stream_set_timeout($socket, 1);
                    fclose($socket);
                    AppLogger::get()->info("It is online");
                    $this->online = true;

                    return true;
                } else {
                    $socket = @\fsockopen($ip, 139, $errCode, $errStr, $wait);

                    if ($socket) {
                        stream_set_timeout($socket, 1);

                        fclose($socket);
                        AppLogger::get()->info("It is online");
                        $this->online = true;

                        return true;
                    }
                    AppLogger::get()->warning("ERROR: $errCode - $errStr");
                }
            }
            AppLogger::get()->info("It is not online");
            $this->online = false;
            return false;
        } else {
            return $this->online;
        }
    }

    public function getManufacturer()
    {
        $return = $this->getCIMValue("Win32_ComputerSystem", "Manufacturer");

        $return = trim(explode("------------", trim($return))[1]);
        return $return;


    }

    protected function getCIMValue($class, $property)
    {
        if (!$this->sessionConnected) {
            PowerShell::run('$session = New-CimSession –ComputerName  ' . $this->getName());
            $this->sessionConnected = true;
        }
        $cmd = "Get-CimInstance -ClassName " . $class . " -CimSession \$session | Select-Object -Property " . $property;
        $return = PowerShell::run($cmd);
        //var_dump($return);
        return ($return);
    }

    public function getModel()
    {
        $return = $this->getCIMValue("Win32_ComputerSystem", "Model");

        $return = trim(explode("-----", trim($return))[1]);
        return $return;
    }

    public function getProcessor()
    {
        $return = $this->getCIMValue("Win32_Processor", "Name");
        //var_dump($return);
        $return = trim(explode("----", trim($return))[1]);
        return $return;
    }

    public function getPhysicalMemorySize()
    {
        $return = $this->getCIMValue("Win32_ComputerSystem", "TotalPhysicalMemory");
        //var_dump($return);
        $return = trim(explode("-------------------", trim($return))[1]);
        $return = $return / 1024 / 1024 / 1024;
        $return = number_format($return, 2);
        return $return . "GB";
    }

    public function reboot()
    {
        return Powershell::run("Restart-Computer -ComputerName " . $this->name);

    }

    public function rename($newName, $reboot = false)
    {
        $cmd = "Rename-Computer -ComputerName " . $this->getName() . " -NewName " . $newName;
        if ($reboot) {
            $cmd .= ' -Restart';
        }
        return Powershell::run($cmd);

    }

    public function shutdown()
    {
        return PowerShell::run("Stop-Computer -Force -ComputerName " . $this->getName());

    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return $this->exists;
    }


}