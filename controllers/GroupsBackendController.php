<?php

/**
 * GroupsBackendController контроллер для групп в панели управления
 *
 * @author yupe team <team@yupe.ru>
 * @link http://yupe.ru
 * @copyright 2009-2013 amyLabs && Yupe! team
 * @package yupe.modules.groups.controllers
 * @since 0.1
 *
 */
class GroupsBackendController extends yupe\components\controllers\BackController
{
    public function accessRules()
    {
        return [
            ['allow', 'roles' => ['admin']],
            ['allow', 'actions' => ['create'], 'roles' => ['Groups.GroupsBackend.Create']],
            ['allow', 'actions' => ['delete'], 'roles' => ['Groups.GroupsBackend.Delete']],
            ['allow', 'actions' => ['index'], 'roles' => ['Groups.GroupsBackend.Index']],
            ['allow', 'actions' => ['inline'], 'roles' => ['Groups.GroupsBackend.Update']],
            ['allow', 'actions' => ['update'], 'roles' => ['Groups.GroupsBackend.Update']],
            ['allow', 'actions' => ['view'], 'roles' => ['Groups.GroupsBackend.View']],
            ['allow', 'actions' => ['multiaction'], 'roles' => ['Groups.GroupsBackend.Multiaction']],
            ['deny']
        ];
    }

    public function actions()
    {
        return [
            'inline' => [
                'class'           => 'yupe\components\actions\YInLineEditAction',
                'model'           => 'Groups',
                'validAttributes' => ['name', 'slug', 'status', 'type']
            ]
        ];
    }

    /**
     * Отображает группу по указанному идентификатору
     * @throws CHttpException
     * @param  integer $id Идинтификатор групп для отображения
     *
     * @return nothing
     **/
    public function actionView($id)
    {
        if (($model = Groups::model()->loadModel($id)) !== null) {
            $this->render('view', ['model' => $model]);
        } else {
            throw new CHttpException(404, Yii::t('GroupsModule.groups', 'Page was not found!'));
        }
    }

    /**
     * Создает новую модель группы.
     * Если создание прошло успешно - перенаправляет на просмотр.
     *
     * @return nothing
     **/
    public function actionCreate()
    {
        $model = new Groups();
        $data = Yii::app()->getRequest()->getPost('Groups');

        if (Yii::app()->getRequest()->getIsPostRequest() && $data !== null)
        {
            $model->setAttributes($data);
            if ($model->save())
            {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('GroupsModule.groups', 'Group was added!')
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
     * Редактирование группы.
     *
     * @param  integer $id Идинтификатор группы для редактирования
     * @throw CHttpException
     * @return nothing
     **/
    public function actionUpdate($id)
    {
        if (($model = Groups::model()->loadModel($id)) === null) {
            throw new CHttpException(404, Yii::t('GroupsModule.groups', 'Page was not found!'));
        }

        if (Yii::app()->getRequest()->getIsPostRequest() && Yii::app()->getRequest()->getPost('Groups') !== null) {
            $model->setAttributes(Yii::app()->getRequest()->getPost('Groups'));
            if ($model->save()) {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('GroupsModule.groups', 'Group was updated!')
                );
                $this->redirect(
                    (array)Yii::app()->getRequest()->getPost(
                        'submit-type',
                        [
                            'update',
                            'id' => $model->id
                        ]
                    )
                );
            }
        }

        $this->render('update', ['model' => $model]);
    }

    /**
     * Удаляет модель группы из базы.
     * Если удаление прошло успешно - возвращется в index
     *
     * @param integer $id - идентификатор группы, который нужно удалить
     *
     * @return nothing
     **/
    public function actionDelete($id)
    {
        if (Yii::app()->getRequest()->getIsPostRequest()) {

            // поддерживаем удаление только из POST-запроса
            if (($model = Groups::model()->loadModel($id)) === null) {
                throw new CHttpException(404, Yii::t('GroupsModule.groups', 'Page was not found!'));
            }

            $model->delete();

            Yii::app()->user->setFlash(
                yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                Yii::t('GroupsModule.groups', 'Group was deleted!')
            );

            // если это AJAX запрос ( кликнули удаление в админском grid view), мы не должны никуда редиректить
            if (!Yii::app()->getRequest()->getIsAjaxRequest()) {
                $this->redirect(Yii::app()->getRequest()->getPost('returnUrl', ['index']));
            }
        } else {
            throw new CHttpException(400, Yii::t(
                'GroupsModule.groups',
                'Wrong request. Please don\'t repeate requests like this anymore!'
            ));
        }
    }

    /**
     * Управление группами.
     *
     * @return nothing
     **/
    public function actionIndex()
    {
        $model = new Groups('search');
        $model->unsetAttributes(); // clear any default values
        if (Yii::app()->getRequest()->getParam('Groups') !== null) {
            $model->setAttributes(Yii::app()->getRequest()->getParam('Groups'));
        }
        $this->render('index', ['model' => $model]);
    }
}
