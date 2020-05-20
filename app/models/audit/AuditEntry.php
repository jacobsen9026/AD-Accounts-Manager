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
    private $actionType;
    private $description;

    /**
     * AuditEntry constructor.
     */
    public function __construct(Request $request=null, User $user=null, AuditAction $action=null)
    {
        $this->username=$user->getUsername();
        $this->ip=$request->getIp();
        $this->setTimestamp();
        $this->setAction($action->getType());
        $this->setDescription($action->getDescription());
    }


    /**
 * @return mixed
 */
public function getId()
{
    return $this->id;
}/**
 * @param mixed $id
 *
 * @return AuditEntry
 */
public function setId($id)
{
    $this->id = $id;
    return $this;
}/**
 * @return mixed
 */
public function getTimestamp()
{
    return $this->timestamp;
}/**
 * @param mixed $timestamp
 *
 * @return AuditEntry
 */
public function setTimestamp($timestamp)
{

    $this->timestamp =  time();
    return $this;
}/**
 * @return mixed
 */
public function getUsername()
{
    return $this->username;
}/**
 * @param mixed $username
 *
 * @return AuditEntry
 */
public function setUsername($username)
{
    $this->username = APPCLASS::get()->user->getUsername();
    return $this;
}/**
 * @return mixed
 */
public function getIp()
{
    return $this->ip;
}/**
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
}/**
 * @param mixed $action
 *
 * @return AuditEntry
 */
public function setActionType(string $action)
{
    $this->action = $action;
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
     * @return AuditEntry
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }


}