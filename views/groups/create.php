<?php
$this->pageTitle = Yii::t('GroupsModule.groups', 'Create group');
$this->description = Yii::t('GroupsModule.groups', 'Create group');
$this->keywords = Yii::t('GroupsModule.groups', 'Create group');

$this->breadcrumbs = [
    Yii::t('GroupsModule.groups', 'Groups') => ['groups/groups/index'],
    Yii::t('GroupsModule.groups', 'Create group')
]; ?>


<h1>
    <small>
        <?php echo Yii::t('GroupsModule.groups', 'Create group'); ?>
    </small>
</h1>

<?php $this->renderPartial('_form', ['model' => $model]);

