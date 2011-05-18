	<div class="row">
		<?php echo CHtml::label('Section', 'term[section]'); ?>
		<?php if(Yii::app()->user->hasRole(array('Teacher'))): ?>
			<?php echo CHtml::dropDownList('term[section]', $terms['section'], CHtml::listData(Yii::app()->user->getModel()->sections, 'id', 'name'), array('empty'=>'--')); ?>
		<?php else: ?>
			<?php echo CHtml::dropDownList('term[section]', $terms['section'], CHtml::listData(Section::model()->findAll(), 'id', 'name'), array('empty'=>'--')); ?>
		<?php endif; ?>
	</div>

	<div class="row">
		<?php echo CHtml::label('Lab', 'term['.Term::TERM_LAB.']'); ?>
		<?php echo CHtml::textField('term['.Term::TERM_LAB.']', $terms[Term::TERM_LAB]); ?>
	</div>

	<div class="row">
		<?php echo CHtml::label('Other Tags', 'term['.Term::TERM_OTHER.']'); ?>
		<?php echo CHtml::textField('term['.Term::TERM_OTHER.']', $terms[Term::TERM_OTHER]); ?>
	</div>
