<?php

/**
 * LastPostsOfGroupWidget виджет для вывода последних записей группы
 *
 * @author yupe team <team@yupe.ru>
 * @link http://yupe.ru
 * @copyright 2009-2013 amyLabs && Yupe! team
 * @package yupe.modules.groups.widgets
 * @since 0.1
 *
 */
Yii::import('application.modules.groups.models.*');

class LastPostsOfGroupWidget extends yupe\widgets\YWidget
{
    public $groupId;

    public $view = 'lastpostsofgroup';

    public $postId;

    public function run()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('group_id = :group_id');
        $criteria->limit = (int)$this->limit;
        $criteria->params = [
            ':group_id' => (int)$this->groupId
        ];

        if ($this->postId) {
            $criteria->addCondition('t.id != :post_id');
            $criteria->params[':post_id'] = (int)$this->postId;
        }

        $this->render(
            $this->view,
            [
                'posts' => GroupsPost::model()->public()->published()->sortByPubDate('DESC')->with(
                        'commentsCount',
                        'createUser',
                        'group'
                    )->findAll($criteria)
            ]
        );
    }
}
