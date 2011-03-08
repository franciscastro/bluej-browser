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
		<?php echo CHtml::label('Lab', 'term['.Term::TERM_LAB.']'); ?>
		<?php echo CHtml::textField('term['.Term::TERM_LAB.']', $terms[Term::TERM_LAB]); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('Other Tags', 'term['.Term::TERM_OTHER.']'); ?>
		<?php echo CHtml::textField('term['.Term::TERM_OTHER.']', $terms[Term::TERM_OTHER]); ?>
	</div>
