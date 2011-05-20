<?php
$this->breadcrumbs=Yii::app()->user->getState('compileSession_breadcrumbs');
$this->breadcrumbs[] = 'Entry #'.$model->deltaSequenceNumber;

$this->menu=array(
	array('label'=>'List CompileSessionEntry', 'url'=>array('index')),
	array('label'=>'Create CompileSessionEntry', 'url'=>array('create')),
	array('label'=>'View CompileSessionEntry', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CompileSessionEntry', 'url'=>array('admin')),
);
?>

<h1>Update Entry #<?php echo $model->deltaSequenceNumber; ?></h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'compile-session-entry-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'compileSessionId'); ?>
		<?php echo $form->textField($model,'compileSessionId'); ?>
		<?php echo $form->error($model,'compileSessionId'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->