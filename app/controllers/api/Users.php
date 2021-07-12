<?php


namespace App\Controllers\Api;


use App\Api\AD;
use App\Models\Database\DomainDatabase;
use App\Models\Domain\DomainUser;
use App\Models\View\CardPrinter;
use System\Traits\DomainTools;

class Users extends APIController
{
    use DomainTools;

    public function getMoveOU($username)
    {
        $user = new DomainUser($username);
        //var_dump($user->getUsername());
        $tree = AD::get()->getAllSubOUs(DomainDatabase::getAD_BaseDN(1));
        //var_dump($tree);
        return $this->returnHTML(CardPrinter::printUserOUTree($user->getDistinguishedName(), $tree) . '<br>Current location highlighted.');
    }


}