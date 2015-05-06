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
class GroupController extends \yupe\components\controllers\FrontController
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

    /**
     * Отобразить карточку группы
     *
     * @param  string $slug - url группы
     * @throws CHttpException
     *
     * @return void
     */
    public function actionView($slug = null)
    {
        $group = Groups::model()->getBySlug($slug);

        if ($group === null) {
            throw new CHttpException(404, Yii::t(
                'GroupsModule.groups',
                'Group "{group}" was not found!',
                ['{group}' => $slug]
            ));
        }

        $this->render('view', ['group' => $group]);
    }

    /**
     * "вступление" в группу
     *
     * @param int $groupId - id-группы
     * @throw CHttpException
     *
     * @return void
     */
    public function actionJoin()
    {
        if (!Yii::app()->getRequest()->getIsPostRequest() || !Yii::app()->user->isAuthenticated()) {
            throw new CHttpException(404);
        }

        $groupId = (int)Yii::app()->request->getPost('groupId');

        if (!$groupId) {
            throw new CHttpException(404);
        }

        $group = Groups::model()->get($groupId);

        if (!$group) {
            throw new CHttpException(404);
        }

        if ($group->join(Yii::app()->user->getId())) {
            Yii::app()->ajax->success(Yii::t('GroupsModule.groups', 'You have joined!'));
        }

        //check if user is in group but blocked
        if ($group->hasUserInStatus(Yii::app()->getUser()->getId(), GroupsMembers::STATUS_BLOCK)) {
            Yii::app()->ajax->failure(Yii::t('GroupsModule.groups', 'You are blocking in this group!'));
        }

        Yii::app()->ajax->failure(Yii::t('GroupsModule.groups', 'An error occurred when you were joining the group!'));
    }

    /**
     * "покинуть" группу
     *
     * @param  int $groupId - id-группы
     * @throw CHttpException
     * @return void
     */
    public function actionLeave()
    {
        if (!Yii::app()->getRequest()->getIsPostRequest() || !Yii::app()->user->isAuthenticated()) {
            throw new CHttpException(404);
        }

        $groupId = (int)Yii::app()->request->getPost('groupId');

        if (!$groupId) {
            throw new CHttpException(404);
        }

        $group = Groups::model()->get($groupId);

        if (!$group) {
            throw new CHttpException(404);
        }

        if ($group->leave(Yii::app()->user->getId())) {
            Yii::app()->ajax->success(Yii::t('GroupsModule.groups', 'You left the group!'));
        }

        Yii::app()->ajax->failure(Yii::t('GroupsModule.groups', 'An error occurred when you were leaving the group!'));
    }

    /**
     * @param $slug
     * @throws CHttpException
     */
    public function actionMembers($slug)
    {
        $group = Groups::model()->getBySlug($slug);

        if (null === $group) {
            throw new CHttpException(404);
        }

        $this->render('members', ['group' => $group, 'members' => $group->getMembersList()]);
    }
}
