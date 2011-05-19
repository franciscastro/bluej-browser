<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'section-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

<?php
function createInput($view, $label, $termType, $terms) {
	?>
	<div class="row">
		<?php echo CHtml::label($label, 'term['.$termType.']'); ?>
		<?php $view->widget('zii.widgets.jui.CJuiAutoComplete', array(
			'name'=>'term['.$termType.']',
			'sourceUrl'=>array('term/search', 'parent'=>$termType),
			'options'=>array('minLength'=>0,)
		));
		?>
	</div>
	<?php
}
?>
	<?php if($model->isNewRecord): ?>
		<?php createInput($this, 'Year', Term::TERM_YEAR, $terms); ?>

		<?php createInput($this, 'Course', Term::TERM_COURSE, $terms); ?>

		<?php createInput($this, 'Section', Term::TERM_SECTION, $terms); ?>
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
