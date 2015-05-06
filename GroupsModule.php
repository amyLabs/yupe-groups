<?php
/**
 * GroupsModule основной класс модуля group
 *
 * @author yupe team <team@yupe.ru>
 * @link http://yupe.ru
 * @copyright 2009-2013 amyLabs && Yupe! team
 * @package yupe.modules.group
 * @since 0.1
 *
 */
use yupe\components\WebModule;

class GroupsModule extends yupe\components\WebModule
{
    const VERSION = '0.9.2';

    public $mainPostCategory;
    public $minSize = 0;
    public $maxSize = 5368709120;
    public $maxFiles = 1;
    public $allowedExtensions = 'jpg,jpeg,png,gif';
    public $uploadPath = 'groups';
    public $rssCount = 10;
    public $assetsPath = "application.modules.groups.views.assets";

    public function getDependencies()
    {
        return [
            'user',
            'comment',
            'image'
        ];
    }

    public function checkSelf()
    {
        $messages = [];
        // count moderated users
        $membersCnt = GroupsMembers::model()->count(
            'status = :status',
            [':status' => GroupsMembers::STATUS_CONFIRMATION]
        );

        if ($membersCnt) {
            $messages[WebModule::CHECK_NOTICE][] = [
                'type'    => WebModule::CHECK_NOTICE,
                'message' => Yii::t(
                        'GroupsModule.groups',
                        '{count} new members of groups wait for confirmation!',
                        [
                            '{count}' => CHtml::link(
                                    $membersCnt,
                                    [
                                        '/groups/groupsMembersBackend/index',
                                        'GroupsMembers[status]' => GroupsMembers::STATUS_CONFIRMATION,
                                        'order'              => 'id.desc'
                                    ]
                                )
                        ]
                    )
            ];
        }

        $groupsPostsCount = GroupsPost::model()->count('status = :status', [':status' => GroupsPost::STATUS_MODERATED]);

        if ($groupsPostsCount) {
            $messages[WebModule::CHECK_NOTICE][] = [
                'type'    => WebModule::CHECK_NOTICE,
                'message' => Yii::t(
                        'GroupsModule.groups',
                        '{count} new GroupsPosts wait for moderation!',
                        [
                            '{count}' => CHtml::link(
                                    $groupsPostsCount,
                                    [
                                        '/groups/groupsPostBackend/index',
                                        'GroupsPost[status]' => GroupsPost::STATUS_MODERATED,
                                        'order'        => 'id.desc'
                                    ]
                                )
                        ]
                    )
            ];
        }

        return (isset($messages[WebModule::CHECK_ERROR]) || isset($messages[WebModule::CHECK_NOTICE]))
            ? $messages
            : true;
    }

    public function getCategory()
    {
        return Yii::t('YupeModule.yupe', 'Users');
    }

    public function getParamsLabels()
    {
        return [
            'mainCategory'      => Yii::t('GroupsModule.groups', 'Main groups category'),
            'mainGroupsPostCategory'  => Yii::t('GroupsModule.groups', 'Main GroupsPosts category'),
            'adminMenuOrder'    => Yii::t('GroupsModule.groups', 'Menu items order'),
            'editor'            => Yii::t('GroupsModule.groups', 'Visual editor'),
            'uploadPath'        => Yii::t(
                    'GroupsModule.groups',
                    'File directory (relatively {path})',
                    [
                        '{path}' => Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . Yii::app()->getModule(
                                "yupe"
                            )->uploadPath
                    ]
                ),
            'allowedExtensions' => Yii::t('GroupsModule.groups', 'Allowed extensions (separated by comma)'),
            'minSize'           => Yii::t('GroupsModule.groups', 'Minimum size (in bytes)'),
            'maxSize'           => Yii::t('GroupsModule.groups', 'Maximum size (in bytes)'),
            'rssCount'          => Yii::t('GroupsModule.groups', 'RSS records count')
        ];
    }

    public function getEditableParams()
    {
        return [
            'adminMenuOrder',
            'editor'           => Yii::app()->getModule('yupe')->getEditors(),
            'mainCategory'     => CHtml::listData($this->getCategoryList(), 'id', 'name'),
            'mainGroupsPostCategory' => CHtml::listData($this->getCategoryList(), 'id', 'name'),
            'uploadPath',
            'allowedExtensions',
            'minSize',
            'maxSize',
            'rssCount'
        ];
    }

    public function getCategoryListForGroupsPost()
    {
        return $this->getCategoryList();
    }

    public function getNavigation()
    {
        return [
            ['label' => Yii::t('GroupsModule.groups', 'Groups')],
            [
                'icon'  => 'fa fa-fw fa-list-alt',
                'label' => Yii::t('GroupsModule.groups', 'Groups list'),
                'url'   => ['/groups/groupsBackend/index']
            ],
            [
                'icon'  => 'fa fa-fw fa-plus-square',
                'label' => Yii::t('GroupsModule.groups', 'New group'),
                'url'   => ['/groups/groupsBackend/create']
            ],
            ['label' => Yii::t('GroupsModule.groups', 'Posts')],
            [
                'icon'  => 'fa fa-fw fa-list-alt',
                'label' => Yii::t('GroupsModule.groups', 'Posts list'),
                'url'   => ['/groups/groupsPostBackend/index']
            ],
            [
                'icon'  => 'fa fa-fw fa-plus-square',
                'label' => Yii::t('GroupsModule.groups', 'New post'),
                'url'   => ['/groups/groupsPostBackend/create']
            ],
            ['label' => Yii::t('GroupsModule.groups', 'Members')],
            [
                'icon'  => 'fa fa-fw fa-list-alt',
                'label' => Yii::t('GroupsModule.groups', 'Members list'),
                'url'   => ['/groups/groupsMembersBackend/index']
            ],
            [
                'icon'  => 'fa fa-fw fa-plus-square',
                'label' => Yii::t('GroupsModule.groups', 'New member'),
                'url'   => ['/groups/groupsMembersBackend/create']
            ],
        ];
    }

    public function getVersion()
    {
        return Yii::t('GroupsModule.groups', self::VERSION);
    }

    public function getName()
    {
        return Yii::t('GroupsModule.groups', 'Groups');
    }

    public function getDescription()
    {
        return Yii::t('GroupsModule.groups', 'This module allows you to combine users into groups');
    }

    public function getAuthor()
    {
        return Yii::t('GroupsModule.groups', 'apexwire');
    }

    public function getAuthorEmail()
    {
        return Yii::t('GroupsModule.groups', 'apexwire@amylabs.ru');
    }

    public function getUrl()
    {
        return Yii::t('GroupsModule.groups', 'http://yupe.ru');
    }

    public function getAdminPageLink()
    {
        return '/groups/groupsBackend/index';
    }

    public function getIcon()
    {
        return "fa fa-fw fa-pencil";
    }

    /**
     * Возвращаем статус, устанавливать ли галку для установки модуля в инсталяторе по умолчанию:
     *
     * @return bool
     **/
    public function getIsInstallDefault()
    {
        return false;
    }

    public function init()
    {
        parent::init();

        $this->setImport(
            [
                'groups.listeners.*',
                'groups.events.*',
                'groups.models.*',
                'groups.components.*',
                'vendor.yiiext.taggable-behavior.*',
            ]
        );
    }

    public function getAuthItems()
    {
        return [
            [
                'name'        => 'groups.groupsManager',
                'description' => Yii::t('GroupsModule.groups', 'Manage groupss'),
                'type'        => AuthItem::TYPE_TASK,
                'items'       => [
                    //groups
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'Groups.GroupsBackend.Create',
                        'description' => Yii::t('GroupsModule.groups', 'Creating groups')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'Groups.GroupsBackend.Delete',
                        'description' => Yii::t('GroupsModule.groups', 'Removing groups')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'Groups.GroupsBackend.Index',
                        'description' => Yii::t('GroupsModule.groups', 'List of groups')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'Groups.GroupsBackend.Update',
                        'description' => Yii::t('GroupsModule.groups', 'Editing groups')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'Groups.GroupsBackend.Inline',
                        'description' => Yii::t('GroupsModule.groups', 'Editing groups')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'groups.groupsBackend.View',
                        'description' => Yii::t('GroupsModule.groups', 'Viewing groups')
                    ],
                    //GroupsPosts
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'Groups.GroupsPostBackend.Create',
                        'description' => Yii::t('GroupsModule.groups', 'Creating GroupsPost')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'Groups.GroupsPostBackend.Delete',
                        'description' => Yii::t('GroupsModule.groups', 'Removing GroupsPost')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'Groups.GroupsPostBackend.Index',
                        'description' => Yii::t('GroupsModule.groups', 'List of GroupsPosts')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'Groups.GroupsPostBackend.Update',
                        'description' => Yii::t('GroupsModule.groups', 'Editing GroupsPost')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'Groups.GroupsPostBackend.Inline',
                        'description' => Yii::t('GroupsModule.groups', 'Editing GroupsPost')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'Groups.GroupsPostBackend.View',
                        'description' => Yii::t('GroupsModule.groups', 'Viewing GroupsPost')
                    ],
                    //members
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'Groups.GroupsMembersBackend.Create',
                        'description' => Yii::t('GroupsModule.groups', 'Creating member')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'Groups.GroupsMembersBackend.Delete',
                        'description' => Yii::t('GroupsModule.groups', 'Removing member')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'Groups.GroupsMembersBackend.Index',
                        'description' => Yii::t('GroupsModule.groups', 'List of members')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'Groups.GroupsMembersBackend.Update',
                        'description' => Yii::t('GroupsModule.groups', 'Editing member')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'Groups.GroupsMembersBackend.Inline',
                        'description' => Yii::t('GroupsModule.groups', 'Editing member')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'Groups.GroupsMembersBackend.View',
                        'description' => Yii::t('GroupsModule.groups', 'Viewing member')
                    ],

                ]
            ]
        ];
    }
}
