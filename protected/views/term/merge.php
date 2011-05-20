 <?php
$this->breadcrumbs=array(
	'Administration'=>array('admin/index'),
	'Tags'=>array('index'),
	'Merge',
);

$this->menu=array(
	array('label'=>'Manage Tags', 'url'=>array('index')),
);
?>

<h1>Merge Tags</h1>

<?php echo CHtml::beginForm('', 'post'); ?>

<?php
echo CHtml::script('
function split(val) {
	return val.split(/,\s*/);
}
function extractLast(term) {
	return split(term).pop();
}
');
echo CHtml::label('Tags ', 'tags');
$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
		'name'=>'tags',
		'source'=>'js:function(request, response) {
				$.getJSON("'.CHtml::normalizeUrl(array('term/search')).'", {
					term: extractLast(request.term)
				}, response);
			}',
		// additional javascript options for the autocomplete plugin
		'options'=>array(
				'showAnim'=>'fold',
				'search'=>'js:function() {
					// custom minLength
					var term = extractLast(this.value);
					if (term.length < 2) {
						return false;
					}
				}',
				'focus'=>'js:function() { return false; }',
				'select'=>'js:function(event, ui) {
					var terms = split( this.value );
					// remove the current input
					terms.pop();
					// add the selected item
					terms.push( ui.item.value );
					// add placeholder to get the comma-and-space at the end
					terms.push("");
					this.value = terms.join(", ");
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

