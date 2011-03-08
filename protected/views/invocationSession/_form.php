<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'invocation-session-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'deltaVersion'); ?>
		<?php echo $form->textArea($model,'deltaVersion',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'deltaVersion'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'extensionVersion'); ?>
		<?php echo $form->textArea($model,'extensionVersion',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'extensionVersion'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'systemUser'); ?>
		<?php echo $form->textArea($model,'systemUser',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'systemUser'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'home'); ?>
		<?php echo $form->textArea($model,'home',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'home'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osName'); ?>
		<?php echo $form->textArea($model,'osName',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'osName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osVersion'); ?>
		<?php echo $form->textArea($model,'osVersion',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'osVersion'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osArch'); ?>
		<?php echo $form->textArea($model,'osArch',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'osArch'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ipAddress'); ?>
		<?php echo $form->textArea($model,'ipAddress',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'ipAddress'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hostName'); ?>
		<?php echo $form->textArea($model,'hostName',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'hostName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'locationId'); ?>
		<?php echo $form->textArea($model,'locationId',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'locationId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'projectId'); ?>
		<?php echo $form->textArea($model,'projectId',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'projectId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sessionId'); ?>
		<?php echo $form->textArea($model,'sessionId',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'sessionId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'projectPath'); ?>
		<?php echo $form->textArea($model,'projectPath',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'projectPath'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'packagePath'); ?>
		<?php echo $form->textArea($model,'packagePath',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'packagePath'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deltaName'); ?>
		<?php echo $form->textArea($model,'deltaName',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'deltaName'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->