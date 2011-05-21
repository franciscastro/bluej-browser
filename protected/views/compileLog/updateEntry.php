<?php
$this->breadcrumbs=Yii::app()->user->getState('compileLog_breadcrumbs');
$this->breadcrumbs[] = 'Entry #'.$model->deltaSequenceNumber;

$this->menu=array(
	array('label'=>'List CompileLogEntry', 'url'=>array('index')),
	array('label'=>'Create CompileLogEntry', 'url'=>array('create')),
	array('label'=>'View CompileLogEntry', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CompileLogEntry', 'url'=>array('admin')),
);
?>

<h1>Update Entry #<?php echo $model->deltaSequenceNumber; ?></h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'compile-log-entry-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'logId'); ?>
		<?php echo $form->textField($model,'logId'); ?>
		<?php echo $form->error($model,'logId'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->