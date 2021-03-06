<?php Yii::import('application.modules.groups.GroupsModule'); ?>

<div class="posts">

    <p class="posts-header">
        <span class="posts-header-text"><?php echo Yii::t('GroupsModule.groups', 'Last group posts'); ?></span>
    </p>

    <div class="posts-list">
        <?php foreach ($posts as $post): ?>
            <div class="posts-list-block">
                <div class="posts-list-block-header">
                    <?php echo CHtml::link(
                        CHtml::encode($post->title),
                        $post->getUrl()
                    ); ?>
                </div>

                <div class="posts-list-block-meta">
                    <span>
                        <i class="glyphicon glyphicon-user"></i>

                        <?php $this->widget(
                            'application.modules.user.widgets.UserPopupInfoWidget',
                            [
                                'model' => $post->createUser
                            ]
                        ); ?>
                    </span>

                    <span>
                        <i class="glyphicon glyphicon-pencil"></i>

                        <?php echo CHtml::link(
                            CHtml::encode($post->group->name),
                            [
                                '/groups/group/view',
                                'slug' => CHtml::encode($post->group->slug)
                            ]
                        ); ?>
                    </span>

                    <span>
                        <i class="glyphicon glyphicon-calendar"></i>

                        <?php echo Yii::app()->getDateFormatter()->formatDateTime(
                            $post->publish_time,
                            "long",
                            "short"
                        ); ?>
                    </span>
                </div>

                <div class="posts-list-block-text">
                    <p>
                        <?php echo $post->getImageUrl() ? CHtml::image($post->getImageUrl(), CHtml::encode($post->title), ['class' => 'img-responsive']) : ''; ?>
                    </p>
                    <?php echo strip_tags($post->getQuote()); ?>
                </div>

                <div class="posts-list-block-tags">
                    <div>
                        <span class="posts-list-block-tags-block">
                            <i class="glyphicon glyphicon-tags"></i>

                            <?php echo Yii::t('GroupsModule.groups', 'Tags'); ?>:

                            <?php foreach ((array)$post->getTags() as $tag): ?>
                                <span>
                                    <?php echo CHtml::link(
                                        CHtml::encode($tag),
                                        Yii::app()->createUrl('/groups/groupsPost/postsByTag', [
                                            'slug' => $post->group->slug,
                                            'tag' => CHtml::encode($tag)
                                        ])
                                    ); ?>

                                </span>
                            <?php endforeach; ?>
                        </span>

                        <span class="posts-list-block-tags-comments">
                            <i class="glyphicon glyphicon-comment"></i>

                            <?php echo CHtml::link(
                                $post->getCommentCount(),
                                $post->getUrl(['#' => 'comments'])
                            ); ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
