<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'yearId'); ?>
		<?php echo $form->textField($model,'yearId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'courseId'); ?>
		<?php echo $form->textField($model,'courseId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sectionId'); ?>
		<?php echo $form->textField($model,'sectionId'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->