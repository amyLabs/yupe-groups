<?php

use yupe\components\Event;

class GroupPostPublishEvent extends Event
{
    protected $groupPost;

    protected $user;

    public function __construct(GroupsPost $groupPost, IWebUser $user)
    {
        $this->groupPost = $groupPost;
        $this->user = $user;
    }

    /**
     * @param mixed $groupspost
     */
    public function setGroupPost($groupPost)
    {
        $this->groupPost = $groupPost;
    }

    /**
     * @return mixed
     */
    public function getGroupPost()
    {
        return $this->groupPost;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
}
