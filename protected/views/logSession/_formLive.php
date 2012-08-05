<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'log-log-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'enctype'=>'multipart/form-data',
	),
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $this->renderPartial('../section/_tagInput', array('tags'=>$tags)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'remarks'); ?>
		<?php echo $form->textArea($model,'remarks',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'remarks'); ?>
	</div>

<?php if($model->isNewRecord || !$model->hasStarted()): ?>
	<div class="row">
		<?php echo CHtml::label('Filter', 'path'); ?>
		<?php echo $form->textField($model,'path'); ?>
		<?php echo $form->error($model,'path'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::label('Start', 'start'); ?>
		<?php echo $form->textField($model,'start', array('class' => 'times')); ?>
		<?php echo $form->error($model,'start'); ?>
	</div>
<?php endif; ?>

<?php if($model->isNewRecord || !$model->hasEnded()): ?>
	<div class="row">
		<?php echo CHtml::label('End', 'end'); ?>
		<?php echo $form->textField($model,'end', array('class' => 'times')); ?>
		<?php echo $form->error($model,'end'); ?>
	</div>
<?php endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
	'name' => 'hidden',
	'htmlOptions' => array('style' => 'display:none')
));
$cs = Yii::app()->clientScript;
$cs->registerScriptFile('js/jquery-ui-timepicker-addon.js', CClientScript::POS_END);
$cs->registerScript('timepicker', <<<QQQ
	$('.times').timepicker();
QQQ
, CClientScript::POS_READY);
?>
