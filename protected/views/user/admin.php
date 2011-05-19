<?php
$this->breadcrumbs=array(
	'Users',
);

$this->menu=array(
	array('label'=>'Add User', 'url'=>array('create')),
);

$this->contextHelp = <<<CNH
<p>
There are 4 kinds of users:
</p>
<p>
<em>Administrators</em> have full access to the application.
They may also create new users as needed.
</p>
<p>
<em>Researchers</em> are allowed to view and collect logs.
</p>
<p>
<em>Teachers</em> may create and view logs of their own sections.
</p>
<p>
<em>Students</em> may view their own data. These are also
automatically generated while logs are being collected but
no username or password is assigned to them.
</p>
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
