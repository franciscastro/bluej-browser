<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'import-session-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'enctype'=>'multipart/form-data',
	),
)); ?>

	<?php echo $form->errorSummary($model); ?>
  
  <?php if(Yii::app()->user->hasRole(array('Teacher'))): ?>
  <?php echo $this->renderPartial('../section/_termInput', array('terms'=>$terms)); ?>
  <?php else: ?>
  <?php echo $this->renderPartial('../term/_termInput', array('terms'=>$terms)); ?>
  <?php endif; ?>
  
	<div class="row">
		<?php echo $form->labelEx($model,'remarks'); ?>
		<?php echo $form->textArea($model,'remarks',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'remarks'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Start Session' : 'Stop Session'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
