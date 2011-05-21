<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'section-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

<?php
function createInput($view, $label, $tagType, $tags) {
	?>
	<div class="row">
		<?php echo CHtml::label($label, 'tag['.$tagType.']'); ?>
		<?php $view->widget('zii.widgets.jui.CJuiAutoComplete', array(
			'name'=>'tag['.$tagType.']',
			'sourceUrl'=>array('tag/search', 'parent'=>$tagType),
			'options'=>array('minLength'=>0,)
		));
		?>
	</div>
	<?php
}
?>
	<?php if($model->isNewRecord): ?>
		<?php createInput($this, 'Year', Tag::TERM_YEAR, $tags); ?>

		<?php createInput($this, 'Course', Tag::TERM_COURSE, $tags); ?>

		<?php createInput($this, 'Section', Tag::TERM_SECTION, $tags); ?>
	<?php endif; ?>

	<div class="row">
		<?php echo CHtml::label('Teachers', 'teacher'); ?>
		<?php echo CHtml::checkBoxList('teacher', $this->modelArrayToAttributeArray($model->teachers, 'id'), CHtml::listData(User::model()->getUsers(User::ROLE_TEACHER), 'id', 'name'), array('labelOptions'=>array('class'=>'checkboxLabel'))); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
