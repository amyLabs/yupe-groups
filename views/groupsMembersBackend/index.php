<?php
/**
 * Отображение для groupsMembersBackend/index:
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
    Yii::t('GroupsModule.groups', 'Administration'),
];

$this->pageTitle = Yii::t('GroupsModule.groups', 'Members - administration');

$this->menu = Yii::app()->getModule('groups')->getNavigation();
?>
<div class="page-header">
    <h1>
        <?php echo Yii::t('GroupsModule.groups', 'Members'); ?>
        <small><?php echo Yii::t('GroupsModule.groups', 'administration'); ?></small>
    </h1>
</div>

<p>
    <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="collapse" data-target="#search-toggle">
        <i class="fa fa-search">&nbsp;</i>
        <?php echo Yii::t('GroupsModule.groups', 'Find members'); ?>
        <span class="caret">&nbsp;</span>
    </a>
</p>

<div id="search-toggle" class="collapse out search-form">
    <?php
    Yii::app()->clientScript->registerScript(
        'search',
        "
    $('.search-form form').submit(function () {
        $.fn.yiiGridView.update('groups-members-grid', {
            data: $(this).serialize()
        });

        return false;
    });"
    );
    $this->renderPartial('_search', ['model' => $model]);
    ?>
</div>

<p>
    <?php echo Yii::t('GroupsModule.groups', 'In this category located member administration functions'); ?>
</p>

<?php $this->widget(
    'yupe\widgets\CustomGridView',
    [
        'id'           => 'groups-members-grid',
        'dataProvider' => $model->search(),
        'filter'       => $model,
        'columns'      => [
            [
                'name'   => 'user_id',
                'type'   => 'raw',
                'value'  => 'CHtml::link($data->user->getFullName(), array("/user/userBackend/view", "id" => $data->user->id))',
                'filter' => CHtml::listData(
                    User::model()->cache($this->yupe->coreCacheTime)->findAll(),
                    'id',
                    'nick_name'
                )
            ],
            [
                'name'   => 'group_id',
                'type'   => 'raw',
                'value'  => 'CHtml::link($data->group->name, array("/groupBackend/view", "id" => $data->group->id))',
                'filter' => CHtml::listData(Groups::model()->cache($this->yupe->coreCacheTime)->findAll(), 'id', 'name')
            ],
            [
                'class'    => 'bootstrap.widgets.TbEditableColumn',
                'editable' => [
                    'url'    => $this->createUrl('/groups/groupsMembersBackend/inline'),
                    'mode'   => 'popup',
                    'type'   => 'select',
                    'title'  => Yii::t(
                        'GroupsModule.groups',
                        'Select {field}',
                        ['{field}' => mb_strtolower($model->getAttributeLabel('role'))]
                    ),
                    'source' => $model->getRoleList(),
                    'params' => [
                        Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken
                    ]
                ],
                'name'     => 'role',
                'type'     => 'raw',
                'value'    => '$data->getRole()',
                'filter'   => CHtml::activeDropDownList(
                    $model,
                    'role',
                    $model->getRoleList(),
                    ['class' => 'form-control', 'empty' => '']
                ),
            ],
            [
                'class'   => 'yupe\widgets\EditableStatusColumn',
                'name'    => 'status',
                'url'     => $this->createUrl('/groups/groupsMembersBackend/inline'),
                'source'  => $model->getStatusList(),
                'options' => [
                    GroupsMembers::STATUS_ACTIVE       => ['class' => 'label-success'],
                    GroupsMembers::STATUS_BLOCK        => ['class' => 'label-default'],
                    GroupsMembers::STATUS_CONFIRMATION => ['class' => 'label-info'],
                    GroupsMembers::STATUS_DELETED      => ['class' => 'label-danger'],

                ],
            ],
            [
                'class'    => 'bootstrap.widgets.TbEditableColumn',
                'editable' => [
                    'url'    => $this->createUrl('/groups/groupsMembersBackend/inline'),
                    'mode'   => 'inline',
                    'title'  => Yii::t(
                        'GroupsModule.groups',
                        'Select {field}',
                        ['{field}' => mb_strtolower($model->getAttributeLabel('note'))]
                    ),
                    'params' => [
                        Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken
                    ]
                ],
                'name'     => 'note',
                'type'     => 'raw',
                'filter'   => CHtml::activeTextField($model, 'note', ['class' => 'form-control']),
            ],
            [
                'name'  => 'create_time',
                'value' => 'Yii::app()->getDateFormatter()->formatDateTime($data->create_time, "medium", "short")',
            ],
            [
                'name'  => 'update_time',
                'value' => 'Yii::app()->getDateFormatter()->formatDateTime($data->update_time, "medium", "short")',
            ],
            [
                'class' => 'yupe\widgets\CustomButtonColumn',
            ],
        ],
    ]
); ?>
