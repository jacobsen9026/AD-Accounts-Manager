<?php


namespace App\Api\Ad;


use App\Models\User\PermissionLevel;

class ADComputers
    extends ADApi
{

    public static function getComputer(string $name)
    {
        $computer = ADConnection::getConnectionProvider();
        $response = $computer->search()
            ->computers()
            ->where('cn', '=', $name)
            ->limit(1)
            ->get();
        if (isset($response[0])) {
            return $response[0];

        } else {
            return false;
        }
    }

    public static function getOS(string $name)
    {
        $computer = ADConnection::getConnectionProvider();
        $computer->search()
            ->computers()
            ->where('cn', '=', $name)
            ->limit(1)
            ->get();

    }

    public static function listComputers($searchTerm)
    {
        $filter = '(&(objectClass=computer)' . '(|(cn=*' . $searchTerm . '*)(sAMAccountName=*' . $searchTerm . '*)(name=*' . $searchTerm . '*)))';

        $result = $this->query($filter, $this->domain->getAdBaseDN());
        //$result = $this->query($filter, self::FQDNtoDN($this->domain->getAdFQDN()));
        $this->logger->debug($result);
        if ($result != false) {
            if (key_exists("count", $result)) {
                foreach ($result as $computer) {
                    if (is_array($computer)) {
                        if (key_exists("cn", $computer)) {
                            $insert["label"] = $computer["cn"][0];
                            $insert["value"] = $computer["cn"][0];
                            //var_dump($computer);
                            //continue;
                            if (self::hasPermission(self::getOUFromDN($computer["distinguishedname"][0]), PermissionLevel::GROUPS, PermissionLevel::GROUP_READ)) {
                                $computers[] = $computer["cn"][0];
                            }
                        }
                    }
                }
            }
        }
        if (isset($computers)) {

//var_dump($usernames);
            sort($computers);
            return $computers;
        }
        return false;
    }
}