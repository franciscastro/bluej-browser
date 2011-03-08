<?php
$this->breadcrumbs=array(
	'Tags',
);

$this->menu=array(
	array('label'=>'Add Tag', 'url'=>array('create')),
);

?>

<h1>Manage Tags</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'term-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'filter' => Term::model()->getParentList(),
			'name' => 'parentId',
			'value' => '$data->parent->name',
		),
		'name',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
