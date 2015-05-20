<?php
/**
 * Отображение для groupsBackend/index:
 *
 * @category YupeView
 * @package  yupe
 * @author yupe team <team@yupe.ru>
 * @license  https://github.com/yupe/yupe/blob/master/LICENSE BSD
 * @link     http://yupe.ru
 **/
$this->breadcrumbs = [
    Yii::t('GroupsModule.groups', 'Groups') => ['/groups/groupsBackend/index'],
    Yii::t('GroupsModule.groups', 'Administration'),
];

$this->pageTitle = Yii::t('GroupsModule.groups', 'Groups - administration');

$this->menu = Yii::app()->getModule('groups')->getNavigation();
?>

<div class="page-header">
    <h1>
        <?php echo Yii::t('GroupsModule.groups', 'Groups'); ?>
        <small><?php echo Yii::t('GroupsModule.groups', 'Administration'); ?></small>
    </h1>
</div>

<a class="btn btn-default btn-sm dropdown-toggle" data-toggle="collapse" data-target="#search-toggle">
    <i class="fa fa-search">&nbsp;</i>
    <?php echo Yii::t('GroupsModule.groups', 'Find a group'); ?>
    <span class="caret">&nbsp;</span>
</a>

<div id="search-toggle" class="collapse out search-form">
    <?php
    Yii::app()->clientScript->registerScript(
        'search',
        "
    $('.search-form form').submit(function () {
        $.fn.yiiGridView.update('groups-grid', {
            data: $(this).serialize()
        });

        return false;
    });"
    );
    $this->renderPartial('_search', ['model' => $model]);
    ?>
</div>

<?php $this->widget(
    'yupe\widgets\CustomGridView',
    [
        'id'           => 'groups-grid',
        'dataProvider' => $model->search(),
        'filter'       => $model,
        'columns'      => [
            [
                'name'   => 'icon',
                'header' => false,
                'type'   => 'raw',
                'value'  => 'CHtml::image($data->getImageUrl(64, 64), $data->name, array("width"  => 64, "height" => 64))',
                'filter' => false
            ],
            [
                'class'    => 'bootstrap.widgets.TbEditableColumn',
                'name'     => 'name',
                'editable' => [
                    'url'    => $this->createUrl('/groups/groupsBackend/inline'),
                    'mode'   => 'inline',
                    'params' => [
                        Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken
                    ]
                ],
                'filter'   => CHtml::activeTextField($model, 'name', ['class' => 'form-control']),
            ],
            [
                'class'    => 'bootstrap.widgets.TbEditableColumn',
                'name'     => 'slug',
                'editable' => [
                    'url'    => $this->createUrl('/groups/groupsBackend/inline'),
                    'mode'   => 'inline',
                    'params' => [
                        Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken
                    ]
                ],
                'filter'   => CHtml::activeTextField($model, 'slug', ['class' => 'form-control']),
            ],
            [
                'class'    => 'bootstrap.widgets.TbEditableColumn',
                'editable' => [
                    'url'    => $this->createUrl('/groups/groupsBackend/inline'),
                    'mode'   => 'popup',
                    'type'   => 'select',
                    'title'  => Yii::t(
                        'GroupsModule.groups',
                        'Select {field}',
                        ['{field}' => mb_strtolower($model->getAttributeLabel('type'))]
                    ),
                    'source' => $model->getTypeList(),
                    'params' => [
                        Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken
                    ]
                ],
                'name'     => 'type',
                'type'     => 'raw',
                'value'    => '$data->getType()',
                'filter'   => CHtml::activeDropDownList(
                    $model,
                    'type',
                    $model->getTypeList(),
                    ['class' => 'form-control', 'empty' => '']
                ),

            ],
            [
                'class'   => 'yupe\widgets\EditableStatusColumn',
                'name'    => 'status',
                'url'     => $this->createUrl('/groups/groupsBackend/inline'),
                'source'  => $model->getStatusList(),
                'options' => [
                    Groups::STATUS_ACTIVE  => ['class' => 'label-success'],
                    Groups::STATUS_BLOCKED => ['class' => 'label-default'],
                    Groups::STATUS_DELETED => ['class' => 'label-danger'],
                ],
            ],
            [
                'name'   => 'create_user_id',
                'type'   => 'raw',
                'value'  => 'CHtml::link($data->createUser->getFullName(), array("/user/userBackend/view", "id" => $data->createUser->id))',
                'filter' => CHtml::listData(User::model()->findAll(), 'id', 'nick_name')
            ],
            [
                'name'   => 'create_time',
                'value'  => 'Yii::app()->getDateFormatter()->formatDateTime($data->create_time, "short", "short")',
                'filter' => false
            ],
            [
                'header' => Yii::t('GroupsModule.groups', 'Posts'),
                'value'  => 'CHtml::link($data->postsCount, array("/groups/postBackend/index","Post[groups_id]" => $data->id ))',
                'type'   => 'html'
            ],
            [
                'header' => Yii::t('GroupsModule.groups', 'Members'),
                'value'  => 'CHtml::link($data->membersCount, array("/groups/groupsMembersBackend/index","GroupsMembers[group_id]" => $data->id ))',
                'type'   => 'html'
            ],
            [
                'class' => 'yupe\widgets\CustomButtonColumn',
            ],
        ],
    ]
); ?>
