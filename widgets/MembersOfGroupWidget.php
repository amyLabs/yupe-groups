<?php

/**
 * MembersOfGroupWidget виджет для вывода участников блога
 *
 * @author yupe team <team@yupe.ru>
 * @link http://yupe.ru
 * @copyright 2009-2013 amyLabs && Yupe! team
 * @package yupe.modules.groups.widgets
 * @since 0.1
 *
 */

Yii::import('application.modules.groups.models.*');

class MembersOfGroupWidget extends yupe\widgets\YWidget
{
    public $groupId;

    public $group;

    public $view = 'membersofgroup';

    public function run()
    {
        if (!$this->group) {
            $this->group = Groups::model()->with('members')->findByPk($this->groupId);
        }

        $this->render($this->view, ['model' => $this->group]);
    }
}
