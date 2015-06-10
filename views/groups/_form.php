<?php
/**
 * Отображение для Groups/_form:
 *
 * @category YupeView
 * @package  yupe
 * @author yupe team <team@yupe.ru>
 * @license  https://github.com/yupe/yupe/blob/master/LICENSE BSD
 * @link     http://yupe.ru
 **/
?>

<script type='text/javascript'>
    $(document).ready(function () {
        $('#groups-form').liTranslit({
            elName: '#Groups_name',
            elAlias: '#Groups_slug'
        });
    })
</script>

<?php $form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    [
        'id'                     => 'groups-form',
        'enableAjaxValidation'   => false,
        'enableClientValidation' => true,
        'type'                   => 'vertical',
        'htmlOptions'            => ['class' => 'well', 'enctype' => 'multipart/form-data'],
    ]
); ?>
<div class="alert alert-info">
    <?php echo Yii::t('GroupsModule.groups', 'Fields marked with'); ?>
    <span class="required">*</span>
    <?php echo Yii::t('GroupsModule.groups', 'are required.'); ?>
</div>

<?php echo $form->errorSummary($model); ?>

<div class="row">
    <div class="col-sm-3">
        <?php echo $form->dropDownListGroup(
            $model,
            'type',
            [
                'widgetOptions' => [
                    'data'        => $model->getTypeList(),
                    'htmlOptions' => [
                        'class'               => 'popover-help',
                        'data-original-title' => $model->getAttributeLabel('type'),
                        'data-content'        => $model->getAttributeDescription('type'),
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
            'name',
            [
                'widgetOptions' => [
                    'htmlOptions' => [
                        'class'               => 'popover-help',
                        'data-original-title' => $model->getAttributeLabel('name'),
                        'data-content'        => $model->getAttributeDescription('name'),
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
            'slug',
            [
                'widgetOptions' => [
                    'htmlOptions' => [
                        'class'               => 'popover-help',
                        'data-original-title' => $model->getAttributeLabel('slug'),
                        'data-content'        => $model->getAttributeDescription('slug'),
                    ],
                ],
            ]
        ); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 form-group popover-help"
         data-original-title='<?php echo $model->getAttributeLabel('description'); ?>'
         data-content='<?php echo $model->getAttributeDescription(
             'description'
         ); ?>'>
        <?php echo $form->labelEx($model, 'description'); ?>
        <?php
        $this->widget(
            $this->module->getVisualEditor(),
            [
                'model'     => $model,
                'attribute' => 'description',
            ]
        ); ?>
    </div>
</div>

<?php $this->widget(
    'bootstrap.widgets.TbButton',
    [
        'buttonType' => 'submit',
        'context'    => 'primary',
        'label'      => Yii::t('GroupsModule.groups', 'Create group'),
    ]
); ?>

<?php $this->endWidget(); ?>
