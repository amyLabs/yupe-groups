<?php

/**
 * GroupsPostController контроллер для постов групп на публичной части сайта
 *
 * @author yupe team <team@yupe.ru>
 * @link http://yupe.ru
 * @copyright 2009-2013 amyLabs && Yupe! team
 * @package yupe.modules.groups.controllers
 * @since 0.1
 *
 */
class GroupsPostController extends \yupe\components\controllers\FrontController
{
    /**
     * Возвращает модель группы
     * @param $slug
     * @return mixed
     * @throws CHttpException
     */
    private function loadModel($slug)
    {
        $group = Groups::model()->getBySlug($slug);

        if ($group === null) {
            throw new CHttpException(404, Yii::t(
                'GroupsModule.groups',
                'Group "{group}" was not found!',
                ['{group}' => $slug]
            ));
        }

        if ( $group->type == Groups::TYPE_PRIVATE && !$group->userIn(Yii::app()->getUser()->getId()) ) {
            throw new CHttpException(403, Yii::t(
                'GroupsModule.groups',
                'Group "{group}" is private. To view you must be a member of!',
                ['{group}' => $group->name]
            ));
        }

        return $group;
    }

    /**
     * Добавляем запись в группу
     *
     * @param $slug
     * @throws CHttpException
     */
    public function actionCreate($slug)
    {
        $group = $this->loadModel($slug);
        $model = new GroupsPost();
        $data = Yii::app()->getRequest()->getPost('GroupsPost');

        if (Yii::app()->getRequest()->getIsPostRequest() && $data !== null)
        {
            $model->setAttributes($data);
            $model->group_id = $group->id;
            $model->publish_time = date('d-m-Y H:i');
            $model->status = GroupsPost::STATUS_DRAFT;
            $model->tags = Yii::app()->getRequest()->getPost('tags');

            if ($model->save())
            {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('GroupsModule.groups', 'Post was created!')
                );
                $this->redirect(['/groups/group/view', 'slug' => CHtml::encode($group->slug)]);
            }
        }
        $this->render('create', ['group' => $group, 'model' => $model]);
    }

    /**
     * Показываем записи группы
     *
     * @param  string $slug - урл поста
     * @throws CHttpException
     * @return void
     */
    public function actionPosts($slug)
    {
        $group = $this->loadModel($slug);
        $this->render('posts', ['group' => $group, 'posts' => $group->getPosts()]);
    }

    /**
     * Показываем пост по урлу
     *
     * @param  string $slug - урл поста
     * @throws CHttpException
     * @return void
     */
    public function actionView($slug, $postSlug)
    {
        $this->loadModel($slug);

        $post = GroupsPost::model()->get($postSlug, ['group', 'createUser', 'comments.author']);

        if (null === $post) {
            throw new CHttpException(404, Yii::t('GroupsModule.groups', 'Post was not found!'));
        }

        $this->render('view', ['post' => $post]);
    }

    /**
     * Показываем посты по тегу
     *
     * @param  string $tag - Tag поста
     * @throws CHttpException
     * @return void
     */
    public function actionPostsByTag($slug, $tag)
    {
        $group = $this->loadModel($slug);
        $posts = GroupsPost::model()->getByTag($group->id, CHtml::encode($tag));

        if (empty($posts)) {
            throw new CHttpException(404, Yii::t('GroupsModule.groups', 'Posts not found!'));
        }

        $this->render('postsByTag', ['group' => $group, 'posts' => $posts, 'tag' => $tag]);
    }

    public function actionPostsByCategory($slug, $categorySlug)
    {
        $group = $this->loadModel($slug);
        $category = Category::model()->getByAlias($categorySlug);

        if (null === $category) {
            throw new CHttpException(404, Yii::t('GroupsModule.groups', 'Page was not found!'));
        }

        $posts = GroupsPost::model()->getForCategory($group->id, $category->id);

        if (empty($posts)) {
            throw new CHttpException(404, Yii::t('GroupsModule.groups', 'Posts not found!'));
        }

        $this->render('postsByCategory',
            [
                'group' => $group,
                'posts' => $posts,
                'target' => $category
            ]
        );
    }

    public function actionCategories($slug)
    {
        $group = $this->loadModel($slug);
        $this->render('categories', ['categories' => GroupsPost::model()->getCategories($group->id)]);
    }
}
