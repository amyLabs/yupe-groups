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

    public function actionCreate()
    {
        $model = new Groups();
        $data = Yii::app()->getRequest()->getPost('Groups');

        if (Yii::app()->getRequest()->getIsPostRequest() && $data !== null)
        {
            $model->setAttributes($data);
            $model->status = Groups::STATUS_MODERATED;
            $model->member_status = GroupsMembers::STATUS_ACTIVE;
            $model->post_status = GroupsPost::STATUS_MODERATED;

            if ($model->save())
            {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('GroupsModule.groups', 'Group was added!')
                );
                $this->redirect(['/groups/groups/index']);
            }
        }
        $this->render('create', ['model' => $model]);
    }
}
