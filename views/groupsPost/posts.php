<?php
/**
 * @var $this GroupsPostController
 * @var $group Groups
 */
$this->title = [Yii::t('GroupsModule.groups', 'Posts of "{group}" group', ['{group}' => CHtml::encode($group->name)]), Yii::app()->getModule('yupe')->siteName];
$this->metaDescription = Yii::t('GroupsModule.groups', 'Posts of "{group}" group', ['{group}' => CHtml::encode($group->name)]);
$this->metaKeywords = $group->name;

$this->breadcrumbs = [
    Yii::t('GroupsModule.groups', 'Groups') => ['/groups/groups/index'],
    CHtml::encode($group->name)         => ['/groups/group/view', 'slug' => CHtml::encode($group->slug)],
    Yii::t('GroupsModule.groups', 'Records'),
];
?>

<p><?php echo Yii::t(
        'GroupsModule.groups',
        'Posts of "{group}" group',
        ['{group}' => CHtml::encode($group->name)]
    ); ?></p>

<?php $this->widget(
    'bootstrap.widgets.TbListView',
    [
        'dataProvider' => $posts->search(),
        'itemView'     => '_post',
        'template'     => "{items}\n{pager}",
    ]
); ?>
