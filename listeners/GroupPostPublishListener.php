<?php
use yupe\components\Event;

class GroupPostPublishListener
{
    public static function onPublish(Event $event)
    {
        $groupPost = $event->getGroupPost();
        Yii::log("Publish groupPost {$groupPost->title} ...", CLogger::LEVEL_TRACE);
    }
}
