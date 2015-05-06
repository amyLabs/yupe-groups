<?php
/**
 * Отображение для groupsMembersBackend/create:
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
    Yii::t('GroupsModule.groups', 'Add'),
];

$this->pageTitle = Yii::t('GroupsModule.groups', 'Members - add');

$this->menu = Yii::app()->getModule('groups')->getNavigation();
?>
<div class="page-header">
    <h1>
        <?php echo Yii::t('GroupsModule.groups', 'Members'); ?>
        <small><?php echo Yii::t('GroupsModule.groups', 'add'); ?></small>
    </h1>
</div>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>
