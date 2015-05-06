<?php
/**
 * Отображение для groupsMembersBackend/view:
 *
 * @category YupeView
 * @package  yupe
 * @author yupe team <team@yupe.ru>
 * @license  https://github.com/yupe/yupe/blob/master/LICENSE BSD
 * @link     http://yupe.ru
 **/
$this->breadcrumbs = [
    Yii::t('GroupsModule.groups', 'Groups') => ['/groups/groupsBackend/index'],
    Yii::t('GroupsModule.groups', 'Members') => ['/groups/groupsMembersBackend/index'],
    $model->user->nick_name,
];

$this->pageTitle = Yii::t('GroupsModule.groups', 'Members - view');

$this->menu = array_merge(
    Yii::app()->getModule('groups')->getNavigation(),
    [
        ['label' => Yii::t('GroupsModule.groups', 'Member') . ' «' . mb_substr($model->id, 0, 32) . '»', 'utf-8'],
        [
            'icon'        => 'fa fa-fw fa-pencil',
            'encodeLabel' => false,
            'label'       => Yii::t('GroupsModule.groups', 'Edit member'),
            'url'         => [
                '/groups/groupsMembersBackend/update',
                'id' => $model->id
            ]
        ],
        [
            'icon'        => 'fa fa-fw fa-eye',
            'encodeLabel' => false,
            'label'       => Yii::t('GroupsModule.groups', 'View member'),
            'url'         => [
                '/groups/groupsMembersBackend/view',
                'id' => $model->id
            ]
        ],
        [
            'icon'        => 'fa fa-fw fa-trash-o',
            'label'       => Yii::t('GroupsModule.groups', 'Remove member'),
            'url'         => '#',
            'linkOptions' => [
                'submit'  => ['/groups/groupsMembersBackend/delete', 'id' => $model->id],
                'confirm' => Yii::t('GroupsModule.groups', 'Do you really want to remove the member?'),
                'params'  => [Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken],
            ]
        ],
    ]
);
?>
<div class="page-header">
    <h1>
        <?php echo Yii::t('GroupsModule.groups', 'View member'); ?><br/>
        <small>&laquo;<?php echo $model->user->nick_name; ?>&raquo;</small>
    </h1>
</div>

<?php $this->widget(
    'bootstrap.widgets.TbDetailView',
    [
        'data'       => $model,
        'attributes' => [
            'id',
            [
                'name'  => 'user_id',
                'value' => $model->user->getFullName(),
            ],
            [
                'name'  => 'group_id',
                'value' => $model->group->name,
            ],
            [
                'name'  => 'create_time',
                'value' => Yii::app()->getDateFormatter()->formatDateTime($model->create_time, "short", "short"),
            ],
            [
                'name'  => 'update_time',
                'value' => Yii::app()->getDateFormatter()->formatDateTime($model->update_time, "short", "short"),
            ],
            [
                'name'  => 'role',
                'value' => $model->getRole(),
            ],
            [
                'name'  => 'status',
                'value' => $model->getStatus(),
            ],
            'note',
        ],
    ]
); ?>
