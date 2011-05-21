<?php
$this->breadcrumbs=array(
	'Administration'=>array('admin/index'),
	'Users',
);

$this->menu=array(
	array('label'=>'Add User', 'url'=>array('create')),
);

$this->contextHelp = <<<CNH
Note: while it is possible to delete users via the interface, it is not advised because it may break relations in the database. A better option would be to just remove the user's username which would disallow them from logging in.
CNH;

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Users</h1>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'username',
		'name',
		array(
			'filter'=>$model->roles(),
			'name'=>'roleId',
			'value'=>'$data->getRole()',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {update} {delete}',
		),
	),
)); ?>
