<?php
function createComboBox($label, $tagType) {
	global $tags;
	$tagModels = Tag::model()->getTags($tagType);
	if(count($tagModels) > 0):
			if(!isset($tags[$tagType])) {
				$tags[$tagType] = $tagModels[count($tagModels)-1];
			}
	?>
	<div class="row">
		<?php echo CHtml::label($label, 'tag['.$tagType.']'); ?>
		<?php echo CHtml::dropDownList('tag['.$tagType.']', $tags[$tagType], CHtml::listData($tagModels, 'id', 'name'), array('empty'=>'--')); ?>
	</div>
	<?php
	endif;
}
?>

	<?php createComboBox('Year', Tag::TERM_YEAR); ?>
	
	<?php createComboBox('Course', Tag::TERM_COURSE); ?>
	
	<?php createComboBox('Section', Tag::TERM_SECTION); ?>
		
	<div class="row">
		<?php echo CHtml::label('Lab', 'tag['.Tag::TERM_LAB.']'); ?>
		<?php echo CHtml::textField('tag['.Tag::TERM_LAB.']', $tags[Tag::TERM_LAB]); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('Other Tags', 'tag['.Tag::TERM_OTHER.']'); ?>
		<?php echo CHtml::textField('tag['.Tag::TERM_OTHER.']', $tags[Tag::TERM_OTHER]); ?>
	</div>
