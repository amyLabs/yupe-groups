<?php

/**
 * GroupsPostBackendController контроллер для постов в панели управления
 *
 * @author yupe team <team@yupe.ru>
 * @link http://yupe.ru
 * @copyright 2009-2013 amyLabs && Yupe! team
 * @package yupe.modules.groups.controllers
 * @since 0.1
 *
 */
class GroupsPostBackendController extends yupe\components\controllers\BackController
{
    public function accessRules()
    {
        return [
            ['allow', 'roles' => ['admin']],
            ['allow', 'actions' => ['create'], 'roles' => ['Groups.GroupsPostBackend.Create']],
            ['allow', 'actions' => ['delete'], 'roles' => ['Groups.GroupsPostBackend.Delete']],
            ['allow', 'actions' => ['index'], 'roles' => ['Groups.GroupsPostBackend.Index']],
            ['allow', 'actions' => ['inline'], 'roles' => ['Groups.GroupsPostBackend.Update']],
            ['allow', 'actions' => ['update'], 'roles' => ['Groups.GroupsPostBackend.Update']],
            ['allow', 'actions' => ['view'], 'roles' => ['Groups.GroupsPostBackend.View']],
            ['deny']
        ];
    }

    public function actions()
    {
        return [
            'inline' => [
                'class'           => 'yupe\components\actions\YInLineEditAction',
                'model'           => 'GroupsPost',
                'validAttributes' => [
                    'title',
                    'slug',
                    'publish_time',
                    'status',
                    'comment_status',
                    'group_id',
                    'tags',
                ]
            ]
        ];
    }

    /**
     * Отображает запись по указанному идентификатору
     *
     * @param  integer $id Идинтификатор запись для отображения
     * @throws CHttpException
     * @return void
     */
    public function actionView($id)
    {
        if (($post = GroupsPost::model()->loadModel($id)) === null) {

            throw new CHttpException(404, Yii::t('GroupsModule.groups', 'Requested page was not found'));
        }

        $this->render('view', ['model' => $post]);
    }

    /**
     * Создает новую модель записи.
     * Если создание прошло успешно - перенаправляет на просмотр.
     *
     * @return void
     */
    public function actionCreate()
    {
        $model = new GroupsPost();

        $model->publish_time = date('d-m-Y h:i');

        if (Yii::app()->getRequest()->getIsPostRequest() && Yii::app()->getRequest()->getPost('GroupsPost')) {
            $model->setAttributes(
                Yii::app()->getRequest()->getPost('GroupsPost')
            );
            $model->tags = Yii::app()->getRequest()->getPost('tags');

            if ($model->save()) {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('GroupsModule.groups', 'Post was created!')
                );
                $this->redirect(
                    (array)Yii::app()->getRequest()->getPost(
                        'submit-type',
                        ['create']
                    )
                );
            }
        }
        $this->render('create', ['model' => $model]);
    }

    /**
     * Редактирование записи.
     *
     * @param  integer $id the ID of the model to be updated
     * @throws CHttpException
     * @return void
     */
    public function actionUpdate($id)
    {
        if (($model = GroupsPost::model()->loadModel($id)) === null) {
            throw new CHttpException(404, Yii::t('GroupsModule.groups', 'Requested page was not found!'));
        }

        if (Yii::app()->getRequest()->getIsPostRequest() && Yii::app()->getRequest()->getPost('GroupsPost')) {
            $model->setAttributes(
                Yii::app()->getRequest()->getPost('GroupsPost')
            );
            $model->tags = Yii::app()->getRequest()->getPost('tags');

            if ($model->save()) {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('GroupsModule.groups', 'Post was updated!')
                );

                if (isset($_POST['post-publish'])) {
                    $model->publish();
                }

                $this->redirect(
                    (array)Yii::app()->getRequest()->getPost(
                        'submit-type',
                        [
                            'update',
                            'id' => $model->id,
                        ]
                    )
                );
            }
        }

        $this->render('update', ['model' => $model]);
    }

    /**
     * Удаляет модель записи из базы.
     * Если удаление прошло успешно - возвращется в index
     *
     * @param  integer $id идентификатор записи, который нужно удалить
     * @throws CHttpException
     * @return void
     */
    public function actionDelete($id)
    {
        if (Yii::app()->getRequest()->getIsPostRequest()) {
            // поддерживаем удаление только из POST-запроса

            if (($post = GroupsPost::model()->loadModel($id)) === null) {
                throw new CHttpException(404, Yii::t('GroupsModule.groups', 'Requested page was not found'));
            } else {
                $post->delete();
            }

            Yii::app()->user->setFlash(
                yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                Yii::t('GroupsModule.groups', 'Post was removed!')
            );

            // если это AJAX запрос ( кликнули удаление в админском grid view), мы не должны никуда редиректить
            if (!Yii::app()->getRequest()->getIsAjaxRequest()) {
                $this->redirect(
                    (array)Yii::app()->getRequest()->getPost(
                        'returnUrl',
                        ['index']
                    )
                );
            }
        } else {
            throw new CHttpException(400, Yii::t(
                'GroupsModule.groups',
                'Wrong request. Please don\'t repeate requests like this!'
            ));
        }
    }

    /**
     * Управление записями.
     *
     * @return void
     */
    public function actionIndex()
    {
        $model = new GroupsPost('search');
        $model->unsetAttributes(); // clear any default values
        if (Yii::app()->getRequest()->getParam('GroupsPost')) {
            $model->setAttributes(
                Yii::app()->getRequest()->getParam('GroupsPost')
            );
        }
        $this->render('index', ['model' => $model]);
    }
}
