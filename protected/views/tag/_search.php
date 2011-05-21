<?php echo CHtml::beginForm('', 'get'); ?>

<?php
echo CHtml::script('
function split(val) {
	return val.split(/,\s*/);
}
function extractLast(tag) {
	return split(tag).pop();
}
');
echo CHtml::label('Tags ', 'tags');
$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
		'name'=>'tags',
		'source'=>'js:function(request, response) {
				$.getJSON("'.CHtml::normalizeUrl(array('tag/search')).'", {
					tag: extractLast(request.term)
				}, response);
			}',
		// additional javascript options for the autocomplete plugin
		'options'=>array(
				'showAnim'=>'fold',
				'search'=>'js:function() {
					// custom minLength
					var tag = extractLast(this.value);
					if (tag.length < 2) {
						return false;
					}
				}',
				'focus'=>'js:function() { return false; }',
				'select'=>'js:function(event, ui) {
					var tags = split( this.value );
					// remove the current input
					tags.pop();
					// add the selected item
					tags.push( ui.item.value );
					// add placeholder to get the comma-and-space at the end
					tags.push("");
					this.value = tags.join(", ");
					return false;
				}',
		),
		'htmlOptions'=>array(
				'style'=>'height:20px;',
				'size'=>50,
		),
));
?>

<?php echo CHtml::endForm(); ?>
