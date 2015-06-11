<?php
/**
 * Отображение для groupsBackend/view:
 *
 * @category YupeView
 * @package  yupe
 * @author yupe team <team@yupe.ru>
 * @license  https://github.com/yupe/yupe/blob/master/LICENSE BSD
 * @link     http://yupe.ru
 **/
$this->breadcrumbs = [
    Yii::t('GroupsModule.groups', 'Groups') => ['/groups/groupsBackend/index'],
    $model->name,
];

$this->pageTitle = Yii::t('GroupsModule.groups', 'Group - view');

$this->menu = array_merge(
    Yii::app()->getModule('groups')->getNavigation(),
    [
        ['label' => Yii::t('GroupsModule.groups', 'Group') . ' «' . mb_substr($model->name, 0, 32) . '»', 'utf-8'],
        [
            'icon'        => 'fa fa-fw fa-pencil',
            'encodeLabel' => false,
            'label'       => Yii::t('GroupsModule.groups', 'Edit group'),
            'url'         => [
                '/groups/groupsBackend/update',
                'id' => $model->id
            ]
        ],
        [
            'icon'        => 'fa fa-fw fa-eye',
            'encodeLabel' => false,
            'label'       => Yii::t('GroupsModule.groups', 'View group'),
            'url'         => [
                '/groups/groupsBackend/view',
                'id' => $model->id
            ]
        ],
        [
            'icon'        => 'fa fa-fw fa-trash-o',
            'label'       => Yii::t('GroupsModule.groups', 'Remove group'),
            'url'         => '#',
            'linkOptions' => [
                'submit'  => ['/groups/groupsBackend/delete', 'id' => $model->id],
                'confirm' => Yii::t('GroupsModule.groups', 'Do you really want to remove the group?'),
                'params'  => [Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken],
            ]
        ],
    ]
);
?>
<div class="page-header">
    <h1>
        <?php echo Yii::t('GroupsModule.groups', 'View group'); ?><br/>
        <small>&laquo;<?php echo $model->name; ?>&raquo;</small>
    </h1>
</div>

<?php $this->widget(
    'bootstrap.widgets.TbDetailView',
    [
        'data'       => $model,
        'attributes' => [
            'id',
            'name',
            [
                'name'  => 'icon',
                'value' => CHtml::image($model->getImageUrl()),
                'type'  => 'raw'
            ],
            [
                'name' => 'description',
                'type' => 'raw'
            ],
            [
                'name'  => 'create_user_id',
                'value' => CHtml::link($model->createUser->getFullName(), ['/user/userBackend/view', 'id' => $model->createUser->id]),
                'type'  => 'raw'
            ],
            [
                'name'  => 'update_user_id',
                'value' => CHtml::link($model->updateUser->getFullName(), ['/user/userBackend/view', 'id' => $model->updateUser->id]),
                'type'  => 'raw'
            ],
            [
                'name'  => Yii::t('GroupsModule.groups', 'Posts'),
                'value' => $model->postsCount
            ],
            [
                'name'  => Yii::t('GroupsModule.groups', 'Members'),
                'value' => $model->membersCount
            ],
            'icon',
            'slug',
            [
                'name'  => 'type',
                'value' => $model->getType(),
            ],
            [
                'name'  => 'status',
                'value' => $model->getStatus(),
            ],
            [
                'name'  => 'create_user_id',
                'value' => $model->createUser->getFullName(),
            ],
            [
                'name'  => 'update_user_id',
                'value' => $model->updateUser->getFullName(),
            ],
            [
                'name'  => 'create_time',
                'value' => Yii::app()->getDateFormatter()->formatDateTime($model->create_time, "short", "short"),
            ],
            [
                'name'  => 'update_time',
                'value' => Yii::app()->getDateFormatter()->formatDateTime($model->update_time, "short", "short"),
            ]
        ],
    ]
); ?>