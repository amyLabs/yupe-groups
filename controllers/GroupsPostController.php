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
     * Показываем записи группы
     *
     * @param  string $slug - урл поста
     * @throws CHttpException
     * @return void
     */
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
    public function actionPostsByTag($slug, $tag)
    {
        $group = Groups::model()->getByUrl($slug)->find();

        if (null === $group) {
            throw new CHttpException(404);
        }

        $posts = GroupsPost::model()->getByTag($group->id, CHtml::encode($tag));

        if (empty($posts)) {
            throw new CHttpException(404, Yii::t('GroupsModule.groups', 'Posts not found!'));
        }

        $this->render('postsByTag', ['group' => $group, 'posts' => $posts, 'tag' => $tag]);
    }

    public function actionPostsByCategory($slug, $categorySlug)
    {
        $group = Groups::model()->getByUrl($slug)->find();

        if (null === $group) {
            throw new CHttpException(404);
        }

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
        $group = Groups::model()->getByUrl($slug)->find();

        if (null === $group) {
            throw new CHttpException(404);
        }

        $this->render('categories', ['categories' => GroupsPost::model()->getCategories($group->id)]);
    }
}
