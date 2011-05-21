	<div class="row">
		<?php echo CHtml::label('Section', 'tag[section]'); ?>
		<?php if(Yii::app()->user->hasRole(array('Teacher'))): ?>
			<?php echo CHtml::dropDownList('tag[section]', $tags['section'], CHtml::listData(Yii::app()->user->getModel()->sections, 'id', 'name'), array('empty'=>'--')); ?>
		<?php else: ?>
			<?php echo CHtml::dropDownList('tag[section]', $tags['section'], CHtml::listData(Section::model()->findAll(), 'id', 'name'), array('empty'=>'--')); ?>
		<?php endif; ?>
	</div>

	<div class="row">
		<?php echo CHtml::label('Lab', 'tag['.Tag::TERM_LAB.']'); ?>
		<?php echo CHtml::textField('tag['.Tag::TERM_LAB.']', $tags[Tag::TERM_LAB]); ?>
	</div>

	<div class="row">
		<?php echo CHtml::label('Other Tags', 'tag['.Tag::TERM_OTHER.']'); ?>
		<?php echo CHtml::textField('tag['.Tag::TERM_OTHER.']', $tags[Tag::TERM_OTHER]); ?>
	</div>
