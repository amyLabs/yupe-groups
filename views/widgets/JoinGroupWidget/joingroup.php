<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->getModule('groups')->getAssetsUrl() . '/js/joingroup.js');
?>

<?php if (!$group->isPrivate()): ?>
    <?php if ($user->isAuthenticated()): ?>
        <?php if (false === $inGroup || GroupsMembers::STATUS_DELETED === $inGroup): ?>
            <a class="btn btn-warning btn-sm join-group" data-id="<?php echo $group->id; ?>"
               data-url="<?php echo Yii::app()->createUrl('/groups/group/join'); ?>"><?php echo Yii::t(
                    'GroupsModule.groups',
                    'Join group'
                ); ?></a>
        <?php elseif ($inGroup == GroupsMembers::STATUS_CONFIRMATION): ?>
            <button type="button" class="btn btn-info disabled"><?php echo Yii::t('GroupsModule.groups', 'Wait for confirmation'); ?></button>
        <?php
        else: ?>
            <a class="btn btn-warning btn-sm leave-group" data-id="<?php echo $group->id; ?>"
               data-url="<?php echo Yii::app()->createUrl('/groups/group/leave'); ?>"><?php echo Yii::t(
                    'GroupsModule.groups',
                    'Leave group'
                ); ?></a>
        <?php endif; ?>
    <?php else: ?>
        <a class="btn btn-warning btn-sm"
           href="<?php echo Yii::app()->createUrl('/user/account/login'); ?>"><?php echo Yii::t(
                'GroupsModule.groups',
                'Join group'
            ); ?></a>
    <?php endif; ?>
<?php endif; ?>
