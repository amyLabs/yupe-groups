<?php
/**
 * Файл настроек для модуля
 *
 * @author yupe team <team@yupe.ru>
 * @link http://yupe.ru
 * @copyright 2009-2013 amyLabs && Yupe! team
 * @package yupe.modules.groups.install
 * @since 0.1
 *
 */
return [
    'module'    => [
        'class'        => 'application.modules.groups.GroupsModule',
    ],
    'rules'     => [
        '/groups'                            => 'groups/group/index',
        '/groups/join'                       => 'groups/group/join',
        '/groups/leave'                      => 'groups/group/leave',
        '/group/<slug>'                      => 'groups/group/view',
        '/group/<slug>/members'              => 'groups/group/members',
        //groupsPost
        '/group/<slug>/posts'                => 'groups/groupsPost/posts',
        '/group/<slug>/post/<postSlug>.html' => 'groups/groupsPost/view',

        /*'/posts/tag/<tag>'        => 'blog/post/list',
        '/posts/categories'       => 'blog/post/categories',
        '/posts/category/<slug>' => 'blog/post/category'*/

    ],
];
