<?php


namespace App\Api\Ad;


use App\Models\Database\DistrictDatabase;

class ADDirectory extends ADApi
{

    public function getSubOUs($dn)
    {
        if ($this->ouExists($dn)) {

            $filter = '(&(objectClass=organizationalUnit))';
            $subOUs = $this->list($filter, $dn);
//var_dump($buildingsRaw);
            return $subOUs;
        }
        return false;
    }

    public function getAllSubOUs($dn, $array = null)
    {
        if ($this->ouExists($dn) or $dn == DistrictDatabase::getAD_BaseDN(1)) {
            $filter = '(objectClass=organizationalUnit)';
            $result = $this->list($filter, $dn);
            foreach ($result as $resultEntry) {
                $ou = $resultEntry["distinguishedname"][0];
                if ($resultEntry != null and $ou != null and $ou != '' and $ou != $dn) {
                    if ($this->hasSubOUs($ou)) {
                        $ous [$ou] = $this->getAllSubOUs($ou, $array);
                    } else {
                        $ous[] = $ou;
                    }
                }
            }
            return $ous;
        }
        return false;
    }

    public function hasSubOUs($dn)
    {
        if ($this->ouExists($dn)) {
            $filter = '(objectClass=organizationalUnit)';
            $result = $this->list($filter, $dn);
            if ($result["count"] > 0) {
                return true;
            }
        }
        return false;
    }


    public function ouExists($dn)
    {
        //$this->logger->debug("Testing if " . $dn . " exists");
//$test = $this->query('(distinguishedName="' . $dn . '")');
        $test = $this->read('(&(objectClass=organizationalUnit))', $dn);
//var_dump($test);
        if ($test == "false" or $test["count"] == 0) {
            $this->logger->warning($dn . " is an invalid OU");
            return false;
        }
        $this->logger->debug($dn . " is a valid OU");
        return true;
    }

}