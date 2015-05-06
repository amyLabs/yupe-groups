<?php
use yupe\components\Event;

class GroupJoinLeaveEvent extends Event
{
    protected $group;

    protected $userId;

    public function __construct(Groups $group, $userId)
    {
        $this->group = $group;
        $this->userId = $userId;
    }

    /**
     * @param mixed $user
     */
    public function setUserId($user)
    {
        $this->userId = $user;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $groups
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }
}
