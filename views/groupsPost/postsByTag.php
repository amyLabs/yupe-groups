<?php
$this->title = [Yii::t('GroupsModule.groups', 'Posts list with tag "{tag}"',
    [
        '{tag}' => CHtml::encode($tag)]), Yii::app()->getModule('yupe')->siteName
];
$this->breadcrumbs = [
    Yii::t('GroupsModule.groups', 'Groups') => ['/groups/group/index'],
    CHtml::encode($group->name)         => ['/groups/group/view', 'slug' => CHtml::encode($group->slug)],
    Yii::t('GroupsModule.groups', 'Records'),
];
?>

<p><?php echo Yii::t('GroupsModule.groups', 'Posts with tag'); ?> <strong><?php echo CHtml::encode($tag); ?></strong>...</p>

<?php foreach ($posts as $post): ?>
    <?php $this->renderPartial('_post', ['data' => $post]); ?>
<?php endforeach; ?>
