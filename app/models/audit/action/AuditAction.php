<?php


namespace App\Models\Audit\Action;



class AuditAction
{
    private $type;
    private $description;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     *
     * @return AuditAction
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return AuditAction
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }


}