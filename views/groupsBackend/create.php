<?php
/**
 * Отображение для GroupsBackend/create:
 *
 * @category YupeView
 * @package  yupe
 * @author yupe team <team@yupe.ru>
 * @license  https://github.com/yupe/yupe/blob/master/LICENSE BSD
 * @link     http://yupe.ru
 **/
$this->breadcrumbs = [
    Yii::t('GroupsModule.groups', 'Groups') => ['/groups/groupsBackend/index'],
    Yii::t('GroupsModule.groups', 'Create'),
];

$this->pageTitle = Yii::t('GroupsModule.groups', 'Groups - create');

$this->menu = Yii::app()->getModule('groups')->getNavigation();
?>
<div class="page-header">
    <h1>
        <?php echo Yii::t('GroupsModule.groups', 'Groups'); ?>
        <small><?php echo Yii::t('GroupsModule.groups', 'Create'); ?></small>
    </h1>
</div>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>
