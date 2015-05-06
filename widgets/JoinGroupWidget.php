<?php

/**
 * JoinGroupWidget виджет позволяет вступить/покинуть группу
 *
 * @author yupe team <team@yupe.ru>
 * @link http://yupe.ru
 * @copyright 2009-2013 amyLabs && Yupe! team
 * @package yupe.modules.groups.widgets
 * @since 0.1
 *
 */

class JoinGroupWidget extends \yupe\widgets\YWidget
{
    public $group;

    public $user;

    public $view = 'joingroup';

    public function init()
    {
        if (!$this->group || !$this->user) {
            throw new CException(Yii::t('GroupsModule.groups', 'Set "groupId" and "user" !'));
        }

        parent::init();
    }

    public function run()
    {
        $this->render(
            $this->view,
            [
                'inGroup' => $this->group->userIn($this->user->getId(), false),
                'user' => $this->user,
                'group' => $this->group
            ]
        );
    }
}
