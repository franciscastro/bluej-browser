<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'section-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
  
<?php
function createComboBox($label, $termType) {
  global $terms;
  $termModels = Term::model()->getTerms($termType);
  if(count($termModels) > 0):
      if(!isset($terms[$termType])) {
        $terms[$termType] = $termModels[count($termModels)-1];
      }
  ?>
  <div class="row">
    <?php echo CHtml::label($label, 'term['.$termType.']'); ?>
    <?php echo CHtml::dropDownList('term['.$termType.']', $terms[$termType], CHtml::listData($termModels, 'id', 'name'), array('empty'=>'--')); ?>
  </div>
  <?php
  endif;
}
?>

  <?php createComboBox('Year', Term::TERM_YEAR); ?>
  
  <?php createComboBox('Course', Term::TERM_COURSE); ?>
  
  <?php createComboBox('Section', Term::TERM_SECTION); ?>
  
  <div class="row">
    <?php echo CHtml::label('Teachers', 'teacher'); ?>
    <?php echo CHtml::checkBoxList('teacher', $this->modelArrayToAttributeArray($model->teachers, 'id'), CHtml::listData(User::model()->getUsers(User::ROLE_TEACHER), 'id', 'name'), array('labelOptions'=>array('class'=>'checkboxLabel'))); ?>
  </div>
  
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
