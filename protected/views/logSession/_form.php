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
		<?php echo $form->labelEx($model,'source'); ?>
<?php if($model->isNewRecord): ?>
		<?php echo $form->fileField($model,'source'); ?>
<?php else: ?>
		<?php echo $model->source; ?>
<?php endif; ?>
		<?php echo $form->error($model,'source'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remarks'); ?>
		<?php echo $form->textArea($model,'remarks',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'remarks'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
