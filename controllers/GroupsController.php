<?php

/**
 * GroupController контроллер для групп пользователей на публичной части сайта
 *
 * @author yupe team <team@yupe.ru>
 * @link http://yupe.ru
 * @copyright 2009-2013 amyLabs && Yupe! team
 * @package yupe.modules.groups.controllers
 * @since 0.1
 *
 */
class GroupsController extends \yupe\components\controllers\FrontController
{
    /**
     * Выводит список групп
     *
     * @return void
     */
    public function actionIndex()
    {
        $groups = new Groups('search');
        $groups->unsetAttributes();
        $groups->status = Groups::STATUS_ACTIVE;

        if (isset($_GET['Groups']['name'])) {
            $groups->name = CHtml::encode($_GET['Groups']['name']);
        }

        $this->render('index', ['groups' => $groups]);
    }
}
