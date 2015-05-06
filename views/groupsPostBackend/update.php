<?php
/**
 * Отображение для groupsPostBackend/update:
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
    $model->title                      => ['/groups/groupsPostBackend/view', 'id' => $model->id],
    Yii::t('GroupsModule.groups', 'Edit'),
];

$this->pageTitle = Yii::t('GroupsModule.groups', 'Posts - edit');

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
            'icon'  => 'fa fa-fw fa-comment',
            'label' => Yii::t('GroupsModule.groups', 'Comments'),
            'url'   => [
                '/comment/commentBackend/index',
                'Comment[model_id]' => $model->id,
                'Comment[model]'    => 'GroupsPost'

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
                'confirm' => Yii::t('GroupsModule.groups', 'Do you really want to delete selected post?'),
                'params'  => [Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken],
            ]
        ],
    ]
);
?>
<div class="page-header">
    <h1>
        <?php echo Yii::t('GroupsModule.groups', 'Edit post'); ?><br/>
        <small>&laquo;<?php echo $model->title; ?>&raquo;</small>
    </h1>
</div>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>
