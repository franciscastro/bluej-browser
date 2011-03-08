<?php
$this->breadcrumbs=array(
	'Invocation Sessions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List InvocationSession', 'url'=>array('index')),
	array('label'=>'Create InvocationSession', 'url'=>array('create')),
	array('label'=>'Update InvocationSession', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete InvocationSession', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage InvocationSession', 'url'=>array('admin')),
);
?>

<h1>View InvocationSession #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'deltaVersion',
		'extensionVersion',
		'systemUser',
		'home',
		'osName',
		'osVersion',
		'osArch',
		'ipAddress',
		'hostName',
		'locationId',
		'projectId',
		'sessionId',
		'projectPath',
		'packagePath',
		'deltaName',
	),
)); ?>
