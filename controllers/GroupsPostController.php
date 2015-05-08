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

    public function actionPosts($slug)
    {
        $group = Groups::model()->getByUrl($slug)->find();

        if (null === $group) {
            throw new CHttpException(404);
        }

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
        $group = Groups::model()->getByUrl($slug)->find();

        if (null === $group) {
            throw new CHttpException(404);
        }

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
    public function actionList($tag)
    {
        $tag = CHtml::encode($tag);

        $posts = GroupsPost::model()->getByTag($tag);

        if (empty($posts)) {
            throw new CHttpException(404, Yii::t('GroupsModule.groups', 'Posts not found!'));
        }

        $this->render('list', ['posts' => $posts, 'tag' => $tag]);
    }

    public function actionCategory($slug)
    {
        $category = Category::model()->getByAlias($slug);

        if (null === $category) {
            throw new CHttpException(404, Yii::t('GroupsModule.groups', 'Page was not found!'));
        }

        $this->render(
            'category-post',
            ['target' => $category, 'posts' => GroupsPost::model()->getForCategory($category->id)]
        );
    }

    public function actionCategories()
    {
        $this->render('categories', ['categories' => GroupsPost::model()->getCategories()]);
    }
}
