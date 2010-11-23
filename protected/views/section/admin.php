<?php
$this->breadcrumbs=array(
	'Sections',
);

$this->menu=array(
	array('label'=>'Create Section', 'url'=>array('create')),
);

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

<h1>Manage Sections</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'section-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'name',
		array(
			'class'=>'CButtonColumn',
      'template'=>'{update} {delete}',
		),
	),
)); ?>
