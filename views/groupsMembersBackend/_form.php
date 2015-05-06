<?php
/**
 * Отображение для groupsMembersBackend/_form:
 *
 * @category YupeView
 * @package  yupe
 * @author yupe team <team@yupe.ru>
 * @license  https://github.com/yupe/yupe/blob/master/LICENSE BSD
 * @link     http://yupe.ru
 **/
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    [
        'id'                     => 'groups-members-form',
        'enableAjaxValidation'   => false,
        'enableClientValidation' => true,
        'type'                   => 'vertical',
        'htmlOptions'            => ['class' => 'well'],
    ]
);

?>

<div class="alert alert-info">
    <?php echo Yii::t('GroupsModule.groups', 'Fields marked with'); ?>
    <span class="required">*</span>
    <?php echo Yii::t('GroupsModule.groups', 'are required.'); ?>
</div>

<?php echo $form->errorSummary($model); ?>

<div class="row">
    <div class="col-sm-7">
        <?php echo $form->dropDownListGroup(
            $model,
            'user_id',
            [
                'widgetOptions' => [
                    'data'        => CHtml::listData(User::model()->findAll(), 'id', 'nick_name'),
                    'htmlOptions' => [
                        'class'               => 'span7 popover-help',
                        'data-original-title' => $model->getAttributeLabel('user_id'),
                        'data-content'        => $model->getAttributeDescription('user_id'),
                    ],
                ],
            ]
        ); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-7">
        <?php echo $form->dropDownListGroup(
            $model,
            'group_id',
            [
                'widgetOptions' => [
                    'data'        => CHtml::listData(Groups::model()->findAll(), 'id', 'name'),
                    'htmlOptions' => [
                        'class'               => 'span7 popover-help',
                        'data-original-title' => $model->getAttributeLabel('group_id'),
                        'data-content'        => $model->getAttributeDescription('group_id'),
                    ],
                ],
            ]
        ); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-7">
        <?php echo $form->dropDownListGroup(
            $model,
            'role',
            [
                'widgetOptions' => [
                    'data'        => $model->getRoleList(),
                    'htmlOptions' => [
                        'class'               => 'span7 popover-help',
                        'data-original-title' => $model->getAttributeLabel('role'),
                        'data-content'        => $model->getAttributeDescription('role'),
                    ],
                ],
            ]
        ); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-7">
        <?php echo $form->dropDownListGroup(
            $model,
            'status',
            [
                'widgetOptions' => [
                    'data'        => $model->getStatusList(),
                    'htmlOptions' => [
                        'class'               => 'span7 popover-help',
                        'data-original-title' => $model->getAttributeLabel('status'),
                        'data-content'        => $model->getAttributeDescription('status'),
                    ],
                ],
            ]
        ); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-7">
        <?php echo $form->textFieldGroup(
            $model,
            'note',
            [
                'widgetOptions' => [
                    'htmlOptions' => [
                        'class'               => 'popover-help',
                        'data-original-title' => $model->getAttributeLabel('note'),
                        'data-content'        => $model->getAttributeDescription('note')
                    ],
                ],
            ]
        ); ?>
    </div>
</div>

<?php
$this->widget(
    'bootstrap.widgets.TbButton',
    [
        'buttonType' => 'submit',
        'context'    => 'primary',
        'label'      => $model->isNewRecord ? Yii::t('GroupsModule.groups', 'Add member and continue') : Yii::t(
                'GroupsModule.groups',
                'Save member and continue'
            ),
    ]
); ?>

<?php
$this->widget(
    'bootstrap.widgets.TbButton',
    [
        'buttonType'  => 'submit',
        'htmlOptions' => ['name' => 'submit-type', 'value' => 'index'],
        'label'       => $model->isNewRecord ? Yii::t('GroupsModule.groups', 'Add member and close') : Yii::t(
                'GroupsModule.groups',
                'Save member and close'
            ),
    ]
); ?>

<?php $this->endWidget(); ?>
