<?php
$this->title = [Yii::t('GroupsModule.groups', 'Posts of "{category}" category', ['{category}' => CHtml::encode($target->name)]), Yii::app()->getModule('yupe')->siteName];
$this->metaDescription = Yii::t('GroupsModule.groups', 'Posts of "{category}" category', ['{category}' => CHtml::encode($target->name)]);
$this->metaKeywords = $target->name;

$this->breadcrumbs = [
    Yii::t('GroupsModule.groups', 'Groups') => ['/groups/group/index'],
    CHtml::encode($group->name)         => ['/groups/group/view', 'slug' => CHtml::encode($group->slug)],
    Yii::t('GroupsModule.groups', 'Records'),
];
?>

<p><?php echo Yii::t(
        'GroupsModule.groups',
        'Posts of "{category}" category',
        ['{category}' => CHtml::encode($target->name)]
    ); ?></p>

<?php $this->widget(
    'bootstrap.widgets.TbListView',
    [
        'dataProvider' => $posts->search(),
        'itemView'     => '_post',
        'template'     => "{items}\n{pager}",
    ]
); ?>
