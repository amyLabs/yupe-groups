<?php
/**
 * Отображение для groupsBackend/update:
 *
 * @category YupeView
 * @package  yupe
 * @author yupe team <team@yupe.ru>
 * @license  https://github.com/yupe/yupe/blob/master/LICENSE BSD
 * @link     http://yupe.ru
 **/
$this->breadcrumbs = [
    Yii::t('GroupsModule.groups', 'Groups') => ['/groups/groupsBackend/index'],
    $model->name => ['/groups/groupsBackend/view', 'id' => $model->id],
    Yii::t('GroupsModule.groups', 'Edit'),
];

$this->pageTitle = Yii::t('GroupsModule.groups', 'Group - edit');

$this->menu = array_merge(
    Yii::app()->getModule('groups')->getNavigation(),
    [
        ['label' => Yii::t('GroupsModule.groups', 'Group') . ' «' . mb_substr($model->name, 0, 32) . '»', 'utf-8'],
        [
            'icon'  => 'fa fa-fw fa-pencil',
            'label' => Yii::t('GroupsModule.groups', 'Edit group'),
            'url'   => [
                '/groups/groupsBackend/update',
                'id' => $model->id
            ]
        ],
        [
            'icon'  => 'fa fa-fw fa-eye',
            'label' => Yii::t('GroupsModule.groups', 'View group'),
            'url'   => [
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
                'confirm' => Yii::t('GroupsModule.groups', 'Do you really want to remove the groups?'),
                'params'  => [Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken],
            ]
        ],
    ]
);
?>
<div class="page-header">
    <h1>
        <?php echo Yii::t('GroupsModule.groups', 'Group edit'); ?><br/>
        <small>&laquo;<?php echo $model->name; ?>&raquo;</small>
    </h1>
</div>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>
