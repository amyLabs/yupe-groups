<?php
$this->pageTitle = Yii::t('GroupsModule.groups', 'Groups');
$this->description = Yii::t('GroupsModule.groups', 'Groups');
$this->keywords = Yii::t('GroupsModule.groups', 'Groups');

$this->breadcrumbs = [Yii::t('GroupsModule.groups', 'Groups')];
?>

<div class="row">
    <div class="col-sm-9">
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
            'method' => 'get',
            'type'   => 'vertical'
        ]); ?>
        <div class="input-group">
            <?php echo $form->textField(
                $groups,
                'name',
                ['placeholder' => Yii::t('GroupsModule.groups', 'Search by group name'), 'class' => 'form-control']
            ); ?>
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit"><?php echo Yii::t('GroupsModule.groups', 'search'); ?></button>
            </span>
        </div>
        <?php $this->endWidget(); ?>
    </div>
    <div class="col-sm-3">
        <?php echo CHtml::link(
            "<i class='glyphicon glyphicon-pencil'></i> " . Yii::t('GroupsModule.groups', 'Create group'),
            ['/groups/groups/create'],
            ['class' => 'btn btn-success']);
        ?>
    </div>
</div>

<h1>
    <small>
        <?php echo Yii::t('GroupsModule.groups', 'Groups'); ?>
    </small>
</h1>

<?php
$this->widget(
    'bootstrap.widgets.TbListView',
    [
        'dataProvider'       => $groups->search(),
        'template'           => '{sorter}<br/><hr/>{items} {pager}',
        'sorterCssClass'     => 'sorter pull-left',
        'itemView'           => '_view',
        'ajaxUpdate'         => false,
        'sortableAttributes' => [
            'name',
            'postsCount',
            'membersCount'
        ],
    ]
);
?>
