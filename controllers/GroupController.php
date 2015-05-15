<?php

/**
 * GroupController контроллер для группы пользователей на публичной части сайта
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
    public function actionJoin($slug)
    {
        if (!Yii::app()->getRequest()->getIsPostRequest() || !Yii::app()->user->isAuthenticated()) {
            throw new CHttpException(404);
        }

        $group = Groups::model()->getBySlug($slug);

        if (null === $group) {
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
    public function actionLeave($slug)
    {
        if (!Yii::app()->getRequest()->getIsPostRequest() || !Yii::app()->user->isAuthenticated()) {
            throw new CHttpException(404);
        }

        $group = Groups::model()->getBySlug($slug);

        if (null === $group) {
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
