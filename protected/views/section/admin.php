<?php
$this->breadcrumbs=array(
	'Classes',
);

$this->menu=array(
	array('label'=>'Add Class', 'url'=>array('create')),
);

$this->contextHelp = <<<CNH
<em>Classes</em> correspond to real-life classes. This
primarily serves to identify students while collecting logs.
It also enables access control for which logs teachers can
view.
CNH;

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('section-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Classes</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'section-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		array(
			'name'=>'active',
			'value'=>'$data->active ? "yes" : "no"',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {update} {delete}',
		),
	),
)); ?>
