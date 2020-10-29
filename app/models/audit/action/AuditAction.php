<?php


namespace App\Models\Audit\Action;


class AuditAction
{
    protected $type;
    protected $description;
    protected $user;

    /**
     * AuditAction constructor.
     *
     * @param $type
     */
    public function __construct()
    {
        /* @var $user \App\Models\User\User */
        if($this->user==null) {
            if(class_exists(APPCLASS)) {
                $appClass=APPCLASS;
                $app = $appClass::get();
                if(isset($app->user ) && $app->user !=null) {
                    $this->user = $app->user;
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }



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