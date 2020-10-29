<?php


namespace App\Models\District;


use App\Models\Model;
use System\App\LDAPLogger;

class ADModel extends Model
{
    /**
     * @var LDAPLogger
     */
    protected $logger;

    /**
     * ADModel constructor.
     */
    public function __construct()
    {
        $this->logger = LDAPLogger::get();
    }


    protected function getAttribute($attributeName)
    {
        if (array_key_exists($attributeName, $this->activeDirectory->getAttributes())) {
            $attribute = $this->activeDirectory->getAttributes()[$attributeName];
            if(is_array($attribute)){
                $attribute=$attribute[0];
            }
            return $attribute;
        }
        return '';
    }

}