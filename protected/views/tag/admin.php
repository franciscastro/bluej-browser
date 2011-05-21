<?php
$this->breadcrumbs=array(
	'Administration'=>array('admin/index'),
	'Tags',
);

$this->menu=array(
	array('label'=>'Add Tag', 'url'=>array('create')),
	array('label'=>'Merge Tags', 'url'=>array('merge')),
);

?>

<h1>Manage Tags</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tag-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'filter' => Tag::model()->getParentList(),
			'name' => 'parentId',
			'value' => '$data->parent->name',
		),
		'name',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
