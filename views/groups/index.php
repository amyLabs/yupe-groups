<?php
$this->pageTitle = Yii::t('GroupsModule.groups', 'Groups');
$this->description = Yii::t('GroupsModule.groups', 'Groups');
$this->keywords = Yii::t('GroupsModule.groups', 'Groups');
?>

<?php $this->breadcrumbs = [Yii::t('GroupsModule.groups', 'Groups')]; ?>

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
    'method' => 'get',
    'type'   => 'vertical'
]); ?>

<div class="row">
    <div class="col-sm-12">
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
    </div>
</div>

<?php $this->endWidget(); ?>

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
