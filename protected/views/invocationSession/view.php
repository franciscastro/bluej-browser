<?php
$this->breadcrumbs=array(
	'Invocation Sessions'=>array('index'),
	$model->id,
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
