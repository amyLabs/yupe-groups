<?php
use yupe\components\Event;

class GroupJoinLeaveListener
{
    public static function onJoin(Event $event)
    {
        Yii::log("User {$event->getUserId()} join group {$event->getGroup()->name}...!!!!!", CLogger::LEVEL_ERROR);
    }

    public static function onLeave(Event $event)
    {
        Yii::log("User {$event->getUserId()} leave group {$event->getGroup()->name}...!!!!!", CLogger::LEVEL_ERROR);
    }
}
