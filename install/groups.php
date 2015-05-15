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
        //groups
        '/groups'                                     => 'groups/groups/index',
        //group
        '/group/<slug>'                               => 'groups/group/view',
        '/group/<slug>/join'                          => 'groups/group/join',
        '/group/<slug>/leave'                         => 'groups/group/leave',
        '/group/<slug>/members'                       => 'groups/group/members',
        //groupsPost
        '/group/<slug>/posts'                         => 'groups/groupsPost/posts',
        '/group/<slug>/post/<postSlug>.html'          => 'groups/groupsPost/view',
        '/group/<slug>/posts/tag/<tag>'               => 'groups/groupsPost/postsByTag',
        '/group/<slug>/posts/categories'              => 'groups/groupsPost/categories',
        '/group/<slug>/posts/category/<categorySlug>' => 'groups/groupsPost/postsByCategory',
    ],
];
