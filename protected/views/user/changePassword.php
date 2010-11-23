<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'currentPassword'); ?>
		<?php echo $form->passwordField($model,'currentPassword',array('size'=>20,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'currentPassword'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>20,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'passwordAgain'); ?>
		<?php echo $form->passwordField($model,'passwordAgain',array('size'=>20,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'passwordAgain'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Change'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
