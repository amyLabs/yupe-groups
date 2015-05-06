<?php
$this->pageTitle = Yii::t('UserModule.user', 'Users');
$this->description = Yii::t(
    'GroupsModule.groups',
    'Members of "{group}" group',
    ['{group}' => CHtml::encode($group->name)]
);
$this->keywords = Yii::t('GroupsModule.groups', 'Members');
$this->breadcrumbs = [
    Yii::t('GroupsModule.groups', 'Groups') => ['/groups/group/index'],
    CHtml::encode($group->name)         => ['/groups/group/view', 'slug' => CHtml::encode($group->slug)],
    Yii::t('UserModule.user', 'Users'),
];
?>

<h1>
    <?php echo Yii::t(
        'GroupsModule.groups',
        'Members of "{group}" group',
        ['{group}' => CHtml::encode($group->name)]
    ); ?>
</h1>

<?php $form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    [
        'method' => 'get',
        'type'   => 'vertical'
    ]
); ?>

<?php $this->endWidget(); ?>

<?php
$this->widget(
    'bootstrap.widgets.TbGridView',
    [
        'dataProvider' => $members->search(),
        'type'         => 'condensed striped',
        'template'     => "{items}\n{pager}",
        'columns'      => [
            [
                'header' => false,
                'value'  => 'CHtml::link(CHtml::image($data->user->getAvatar(90), $data->user->getFullName(), array("width" => 90, "height" => 90)), array("/user/people/userInfo","username" => $data->user->nick_name))',
                'type'   => 'html'
            ],
            [
                'name'   => 'nick_name',
                'header' => Yii::t('GroupsModule.groups', 'User'),
                'type'   => 'html',
                'value'  => 'CHtml::link($data->user->nick_name, array("/user/people/userInfo","username" => $data->user->nick_name))'
            ],
            [
                'name'   => 'location',
                'header' => Yii::t('GroupsModule.groups', 'location')
            ],
            [
                'header' => Yii::t('GroupsModule.groups', 'Last visit'),
                'name'   => 'visit_time',
                'value'  => 'Yii::app()->getDateFormatter()->formatDateTime($data->user->visit_time, "long", false)'
            ],
            [
                'header' => Yii::t('GroupsModule.groups', 'Joined'),
                'name'   => 'create_time',
                'value'  => 'Yii::app()->getDateFormatter()->formatDateTime($data->user->create_time, "long", false)'
            ]
        ]
    ]
);
?>
