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
        '/groups'                  => 'groups/group/index',
        '/groups/join'             => 'groups/group/join',
        '/groups/leave'            => 'groups/group/leave',
        '/group/<slug>'            => 'groups/group/view',
        '/group/<slug>/members'    => 'groups/group/members',

    ],
];
