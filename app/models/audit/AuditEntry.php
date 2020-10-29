<?php


namespace App\Models\Audit;

use App\Models\Audit\Action\AuditAction;
use App\Models\User\User;
use System\Request;

class AuditEntry
{
    private $id;
    private $timestamp;
    private $username;
    private $ip;
    /**
     * @var AuditAction
     */


    /**
     * AuditEntry constructor.
     *
     * @param Request|null $request
     * @param User|null $user
     * @param AuditAction|null $action
     */
    public function __construct(Request $request = null, User $user = null, AuditAction $action = null)
    {
        if($user!=null){
            $this->username = $user->getUsername();
        }
        $this->ip = $request->getIp();
        $this->setTimestamp();
        $this->setAction($action);

    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return AuditEntry
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     *
     * @return AuditEntry
     */
    public function setTimestamp()
    {

        $this->timestamp = time();
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     *
     * @return AuditEntry
     */
    public function setUsername($username)
    {
        $this->username = APPCLASS::get()->user->getUsername();
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     *
     * @return AuditEntry
     */
    public function setIp()
    {

        $this->ip = APPCLASS::get()->request->ip;
        return $this;
    }

    /**
     * @return AuditAction
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param AuditAction $action
     *
     * @return $this
     */
    public function setAction(AuditAction $action)
    {
        $this->action = $action;
        return $this;
    }


}