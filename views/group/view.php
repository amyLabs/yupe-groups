<?php
$this->pageTitle = CHtml::encode($group->name);
$this->description = CHtml::encode($group->name);
$this->keywords = CHtml::encode($group->name);
?>

<?php
$this->breadcrumbs = [
    Yii::t('GroupsModule.groups', 'Groups') => ['/groups/group/index/'],
    CHtml::encode($group->name),
];
?>
<style>
    .group-logo {
        border: 1px solid #e2e2e2;
        width: 109px;
        height: 109px;
        display: inline-block;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }
    .group-description {
        padding-top: 13px;
        margin-left: 130px;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="group-logo pull-left">
            <?php echo CHtml::image(
                $group->getImageUrl(),
                CHtml::encode($group->name),
                [
                    'width'  => 109,
                    'height' => 109
                ]
            ); ?>
        </div>
        <div class="group-description">
            <div class="group-description-name">

                <?php echo CHtml::link(
                    CHtml::encode($group->name),
                    ['/group/post/group/', 'slug' => CHtml::encode($group->slug)]
                ); ?>

                <div class="pull-right">
                    <?php $this->widget(
                        'application.modules.groups.widgets.JoinGroupWidget',
                        ['user' => Yii::app()->user, 'group' => $group]
                    ); ?>
                </div>
            </div>

            <div class="group-description-info">

            <span class="group-description-owner">
                <i class="glyphicon glyphicon-user"></i>
                <?php echo Yii::t('GroupsModule.groups', 'Created'); ?>:
                <strong>
                    <?php $this->widget(
                        'application.modules.user.widgets.UserPopupInfoWidget',
                        [
                            'model' => $group->createUser
                        ]
                    ); ?>
                </strong>
            </span>

            <span class="group-description-datetime">
                <i class="glyphicon glyphicon-calendar"></i>
                <?php echo Yii::app()->getDateFormatter()->formatDateTime($group->create_time, "short", "short"); ?>
            </span>

            <span class="group-description-posts">
                <i class="glyphicon glyphicon-pencil"></i>
                <?php echo CHtml::link(
                    count($group->posts),
                    ['/group/post/group/', 'slug' => CHtml::encode($group->slug)]
                ); ?>
            </span>

            </div>

            <?php if ($group->description) : ?>
                <div class="group-description-text">
                    <?php echo strip_tags($group->description); ?>
                </div>
            <?php endif; ?>

            <?php $this->widget('application.modules.groups.widgets.MembersOfGroupWidget', ['groupId' => $group->id, 'group' => $group]); ?>

        </div>
    </div>
</div>

<?php $this->widget('application.modules.groups.widgets.LastPostsOfGroupWidget', ['groupId' => $group->id, 'limit' => 10]); ?>

<br/>

<?php echo CHtml::link(
    Yii::t('GroupsModule.groups', 'All entries for group "{group}"', ['{group}' => CHtml::encode($group->name)]),
    ['/groups/groupsPost/posts', 'slug' => $group->slug],
    ['class' => 'btn btn-default']
); ?>

<br/><br/>

<?php $this->widget('application.modules.groups.widgets.ShareWidget'); ?>
