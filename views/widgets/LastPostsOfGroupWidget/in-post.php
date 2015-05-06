<?php Yii::import('application.modules.groups.GroupsModule'); ?>

<h4><?php echo Yii::t('GroupsModule.groups', 'Last group posts'); ?>:</h4>
<div>
    <ul class="list-unstyled">
        <?php foreach ($posts as $data): ?>
            <li><?php echo CHtml::link(
                    CHtml::encode($data->title),
                    $data->getUrl()
                ); ?></li>
        <?php endforeach ?>
    </ul>
</div>
