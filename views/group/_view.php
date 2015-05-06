<div class="row">
    <div class="col-sm-2">
        <?php echo CHtml::image(
            $data->getImageUrl(),
            CHtml::encode($data->name),
            ['width' => 64, 'height' => 64, 'class' => 'thumbnail']
        ); ?>
    </div>
    <div class="col-sm-6 blog-info">

        <h2><?php echo CHtml::link(
                CHtml::encode($data->name),
                ['/groups/group/view/', 'slug' => CHtml::encode($data->slug)]
            ); ?></h2>
        <?php echo CHtml::image(
            $data->createUser->getAvatar(24),
            CHtml::encode($data->createUser->nick_name)
        ); ?> <?php echo CHtml::link(
            CHtml::encode($data->createUser->nick_name),
            ['/user/people/userInfo', 'username' => CHtml::encode($data->createUser->nick_name)]
        ); ?> </span>
        <span> <i class="glyphicon glyphicon-calendar"></i> <?php echo Yii::app()->getDateFormatter()->formatDateTime(
                $data->create_time,
                "long",
                false
            ); ?> </span>
        <span> <i class="glyphicon glyphicon-pencil"></i> <?php /*echo CHtml::link(
                CHtml::encode($data->postsCount),
                ['/blog/post/blog/', 'slug' => CHtml::encode($data->slug)]
            );*/ ?> </span>
        <span> <i class="glyphicon glyphicon-user"></i> <?php echo CHtml::link(
                CHtml::encode($data->membersCount),
                ['/groups/group/members', 'slug' => CHtml::encode($data->slug)]
            ); ?> </span>
        <span> <?php echo strip_tags($data->description); ?> </span>
    </div>

    <div class="col-sm-4 text-right">
        <?php $this->widget(
            'application.modules.groups.widgets.JoinGroupWidget',
            ['user' => Yii::app()->user, 'group' => $data]
        ); ?>
    </div>
</div>
<hr/>
