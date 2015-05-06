<?php
/**
 * Отображение для groupsPostBackend/_form:
 *
 * @category YupeView
 * @package  yupe
 * @author yupe team <team@yupe.ru>
 * @license  https://github.com/yupe/yupe/blob/master/LICENSE BSD
 * @link     http://yupe.ru
 **/
$this->breadcrumbs = [
    Yii::t('GroupsModule.groups', 'Groups') => ['/groups/groupsBackend/index'],
    Yii::t('GroupsModule.groups', 'Posts') => ['/groups/groupsPostBackend/index'],
    $model->title,
];

$this->pageTitle = Yii::t('GroupsModule.groups', 'Posts - view');


$this->menu = array_merge(
    Yii::app()->getModule('groups')->getNavigation(),
    [
        ['label' => Yii::t('GroupsModule.groups', 'Post') . ' «' . mb_substr($model->title, 0, 32) . '»', 'utf-8'],
        [
            'icon'  => 'fa fa-fw fa-pencil',
            'label' => Yii::t('GroupsModule.groups', 'Edit posts'),
            'url'   => [
                '/groups/groupsPostBackend/update',
                'id' => $model->id
            ]
        ],
        [
            'icon'  => 'fa fa-fw fa-eye',
            'label' => Yii::t('GroupsModule.groups', 'View post'),
            'url'   => [
                '/groups/groupsPostBackend/view',
                'id' => $model->id
            ]
        ],
        [
            'icon'        => 'fa fa-fw fa-trash-o',
            'label'       => Yii::t('GroupsModule.groups', 'Remove post'),
            'url'         => '#',
            'linkOptions' => [
                'submit'  => ['/groups/groupsPostBackend/delete', 'id' => $model->id],
                'confirm' => Yii::t('GroupsModule.groups', 'Do you really want to remove the post?'),
                'params'  => [Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken],
            ]
        ],
    ]
);
?>
<div class="page-header">
    <h1>
        <?php echo Yii::t('GroupsModule.groups', 'View post'); ?><br/>
        <small>&laquo;<?php echo $model->title; ?>&raquo;</small>
    </h1>
</div>

<?php $this->widget(
    'bootstrap.widgets.TbDetailView',
    [
        'data'       => $model,
        'attributes' => [
            'id',
            [
                'name'  => 'group_id',
                'value' => $model->group->name,
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
                'name'  => 'publish_time',
                'value' => Yii::app()->getDateFormatter()->formatDateTime($model->publish_time, "short", "short"),
            ],
            [
                'name'  => 'create_time',
                'value' => Yii::app()->getDateFormatter()->formatDateTime($model->create_time, "short", "short"),
            ],
            [
                'name'  => 'update_time',
                'value' => Yii::app()->getDateFormatter()->formatDateTime($model->update_time, "short", "short"),
            ],
            'slug',
            'title',
            [
                'name' => 'quote',
                'type' => 'raw'
            ],
            [
                'name' => 'content',
                'type' => 'raw'
            ],
            'link',
            [
                'name'  => 'status',
                'value' => $model->getStatus(),
            ],
            [
                'name'  => 'comment_status',
                'value' => $model->getCommentStatus(),
            ],
            [
                'name'  => 'access_type',
                'value' => $model->getAccessType(),
            ],
            'keywords',
            'description',
        ],
    ]
); ?>
