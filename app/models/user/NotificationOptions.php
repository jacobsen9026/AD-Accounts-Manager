<?php


namespace App\Models\User;


use App\Models\Model;

class NotificationOptions extends Model
{
    protected $userChange = false;
    protected $userDisable = false;
    protected $userCreate = false;
    protected $groupChange = false;
    protected $groupCreate = false;

    public function isAll()
    {
        if ($this->isGroupCreate() && $this->isGroupChange() && $this->isUserCreate() && $this->isUserDisable() && $this->isUserChange()) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isGroupCreate(): bool
    {
        return $this->groupCreate;
    }

    /**
     * @param bool $groupCreate
     * @return NotificationOptions
     */
    public function setGroupCreate($groupCreate): NotificationOptions
    {
        $this->groupCreate = (bool)$groupCreate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isGroupChange(): bool
    {
        return $this->groupChange;
    }

    /**
     * @param bool $groupChange
     * @return NotificationOptions
     */
    public function setGroupChange($groupChange): NotificationOptions
    {
        $this->groupChange = (bool)$groupChange;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUserCreate(): bool
    {
        return $this->userCreate;
    }

    /**
     * @param bool $userCreate
     * @return NotificationOptions
     */
    public function setUserCreate($userCreate): NotificationOptions
    {
        $this->userCreate = (bool)$userCreate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUserDisable(): bool
    {
        return $this->userDisable;
    }

    /**
     * @param bool $userDisable
     * @return NotificationOptions
     */
    public function setUserDisable($userDisable): NotificationOptions
    {
        $this->userDisable = (bool)$userDisable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUserChange(): bool
    {
        return $this->userChange;
    }

    /**
     * @param bool $userChange
     * @return NotificationOptions
     */
    public function setUserChange($userChange): NotificationOptions
    {
        $this->userChange = (bool)$userChange;
        return $this;
    }


}