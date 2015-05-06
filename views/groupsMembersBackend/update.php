<?php
/**
 * Отображение для groupsMembersBackend/update:
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
    $model->user->nick_name              => ['/groups/groupsMembersBackend/view', 'id' => $model->id],
    Yii::t('GroupsModule.groups', 'Edit'),
];

$this->pageTitle = Yii::t('GroupsModule.groups', 'Members - edit');

$this->menu = array_merge(
    Yii::app()->getModule('groups')->getNavigation(),
    [
        ['label' => Yii::t('GroupsModule.groups', 'Member') . ' «' . mb_substr($model->id, 0, 32) . '»', 'utf-8'],
        [
            'icon'  => 'fa fa-fw fa-pencil',
            'label' => Yii::t('GroupsModule.groups', 'Edit member'),
            'url'   => [
                '/groups/groupsMembersBackend/update',
                'id' => $model->id
            ]
        ],
        [
            'icon'  => 'fa fa-fw fa-eye',
            'label' => Yii::t('GroupsModule.groups', 'View member'),
            'url'   => [
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
        <?php echo Yii::t('GroupsModule.groups', 'Edit member'); ?><br/>
        <small>&laquo;<?php echo $model->user->nick_name; ?>&raquo;</small>
    </h1>
</div>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>
