<?php
$this->title = [Yii::t('GroupsModule.groups', 'Categories'), Yii::app()->getModule('yupe')->siteName];

$this->breadcrumbs = [
    Yii::t('GroupsModule.groups', 'Posts') => ['/blog/post/index/'],
    Yii::t('GroupsModule.groups', 'Categories')
];
?>

<?php foreach ($categories as $category): ?>
    <h4><strong><?php echo CHtml::link(
            CHtml::encode($category['name']),
            ['/blog/post/category/', 'alias' => CHtml::encode($category['alias'])]
        ); ?></strong>
    <?php echo strip_tags($category['description']); ?>
    <hr/>
<?php endforeach; ?>
