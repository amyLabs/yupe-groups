<?php
/**
 * Отображение для groupsPostBackend/index:
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
    Yii::t('GroupsModule.groups', 'Administration'),
];

$this->pageTitle = Yii::t('GroupsModule.groups', 'Post - administration');

$this->menu = Yii::app()->getModule('groups')->getNavigation();
?>

<div class="page-header">
    <h1>
        <?php echo Yii::t('GroupsModule.groups', 'Posts'); ?>
        <small><?php echo Yii::t('GroupsModule.groups', 'administration'); ?></small>
    </h1>
</div>

<a class="btn btn-default btn-sm dropdown-toggle" data-toggle="collapse" data-target="#search-toggle">
    <i class="fa fa-search">&nbsp;</i>
    <?php echo Yii::t('GroupsModule.groups', 'Find posts'); ?>
    <span class="caret">&nbsp;</span>
</a>

<div id="search-toggle" class="collapse out search-form">
    <?php
    Yii::app()->clientScript->registerScript(
        'search',
        "
    $('.search-form form').submit(function () {
        $.fn.yiiGridView.update('post-grid', {
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
        'id'           => 'post-grid',
        'dataProvider' => $model->search(),
        'filter'       => $model,
        'columns'      => [
            [
                'class'    => 'bootstrap.widgets.TbEditableColumn',
                'editable' => [
                    'url'    => $this->createUrl('/groups/postBackend/inline'),
                    'mode'   => 'popup',
                    'type'   => 'select',
                    'title'  => Yii::t(
                        'GroupsModule.groups',
                        'Select {field}',
                        ['{field}' => mb_strtolower($model->getAttributeLabel('group_id'))]
                    ),
                    'source' => CHtml::listData(Groups::model()->findAll(), 'id', 'name'),
                    'params' => [
                        Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken
                    ]
                ],
                'name'     => 'group_id',
                'type'     => 'raw',
                'filter'   => CHtml::activeDropDownList(
                    $model,
                    'group_id',
                    CHtml::listData(Groups::model()->findAll(), 'id', 'name'),
                    ['class' => 'form-control', 'empty' => '']
                ),
            ],
            [
                'class'    => 'bootstrap.widgets.TbEditableColumn',
                'name'     => 'title',
                'editable' => [
                    'url'    => $this->createUrl('/groups/postBackend/inline'),
                    'mode'   => 'inline',
                    'params' => [
                        Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken
                    ]
                ],
                'filter'   => CHtml::activeTextField($model, 'title', ['class' => 'form-control']),
            ],
            [
                'class'    => 'bootstrap.widgets.TbEditableColumn',
                'name'     => 'slug',
                'editable' => [
                    'url'    => $this->createUrl('/groups/postBackend/inline'),
                    'mode'   => 'inline',
                    'params' => [
                        Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken
                    ]
                ],
                'filter'   => CHtml::activeTextField($model, 'slug', ['class' => 'form-control']),
            ],
            [
                'class'    => 'bootstrap.widgets.TbEditableColumn',
                'name'     => 'publish_time',
                'editable' => [
                    'url'        => $this->createUrl('/groups/postBackend/inline'),
                    //'mode' => 'inline',
                    'type'       => 'datetime',
                    'options'    => [
                        'datetimepicker' => [
                            'format'   => 'dd-mm-yyyy hh:ii',
                            'language' => Yii::app()->language,
                        ],
                        'datepicker'     => [
                            'format' => 'dd-mm-yyyy',
                        ],

                    ],
                    'viewformat' => 'dd-mm-yyyy hh:ii',
                    'params'     => [
                        Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken
                    ]
                ],
                'value'    => '$data->publish_time',
                'filter'   => CHtml::activeTextField($model, 'publish_time', ['class' => 'form-control']),
            ],
            [
                'name'   => 'create_user_id',
                'type'   => 'raw',
                'value'  => 'CHtml::link($data->createUser->getFullName(), array("/user/userBackend/view", "id" => $data->createUser->id))',
                'filter' => CHtml::activeDropDownList(
                    $model,
                    'create_user_id',
                    CHtml::listData(User::model()->cache($this->yupe->coreCacheTime)->findAll(), 'id', 'nick_name'),
                    ['class' => 'form-control', 'empty' => '']
                ),
            ],
            [
                'class'    => 'bootstrap.widgets.TbEditableColumn',
                'editable' => [
                    'url'    => $this->createUrl('/groups/postBackend/inline'),
                    'mode'   => 'popup',
                    'type'   => 'select',
                    'title'  => Yii::t(
                        'GroupsModule.groups',
                        'Select {field}',
                        ['{field}' => mb_strtolower($model->getAttributeLabel('comment_status'))]
                    ),
                    'source' => array_merge(['' => '---'], $model->getCommentStatusList()),
                    'params' => [
                        Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken
                    ]
                ],
                'name'     => 'comment_status',
                'type'     => 'raw',
                'value'    => '$data->getCommentStatus()',
                'filter'   => CHtml::activeDropDownList(
                    $model,
                    'comment_status',
                    $model->getCommentStatusList(),
                    ['class' => 'form-control', 'empty' => '']
                ),
            ],
            [
                'class'    => 'bootstrap.widgets.TbEditableColumn',
                'editable' => [
                    'url'     => $this->createUrl('/groups/postBackend/inline'),
                    'mode'    => 'inline',
                    'type'    => 'select2',
                    'select2' => [
                        'tags' => array_values(CHtml::listData(Tag::model()->findAll(), 'id', 'name')),
                    ],
                ],
                'name'     => 'tags',
                'value'    => 'join(", ", $data->getTags())',
                'filter'   => false,
            ],
            [
                'header' => "<i class=\"fa fa-comment\"></i>",
                'value'  => 'CHtml::link(($data->commentsCount>0) ? $data->commentsCount-1 : 0,array("/comment/commentBackend/index/","Comment[model]" => "Post","Comment[model_id]" => $data->id))',
                'type'   => 'raw',
            ],
            [
                'class'   => 'yupe\widgets\EditableStatusColumn',
                'name'    => 'status',
                'url'     => $this->createUrl('/groups/postBackend/inline'),
                'source'  => $model->getStatusList(),
                'options' => [
                    Post::STATUS_PUBLISHED => ['class' => 'label-success'],
                    Post::STATUS_SCHEDULED => ['class' => 'label-info'],
                    Post::STATUS_DRAFT     => ['class' => 'label-default'],
                    Post::STATUS_MODERATED => ['class' => 'label-warning'],
                ],
            ],
            [
                'class' => 'yupe\widgets\CustomButtonColumn',
            ],
        ],
    ]
); ?>
