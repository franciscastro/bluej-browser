<?php
$importSessionId = $model->import->importSessionId;
$this->breadcrumbs=array(
			'Logs'=>array('importSession/index'),
			'Log Session #'.$importSessionId=>array('importSession/view', 'id'=>$importSessionId),
			'Invocation Log #'.$_GET['id'],
		);
?>

<h1>Invocation Log #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'label'=>'Name',
			'type'=>'raw',
			'value'=>CHtml::link($model->import->user->name, array('user/view', 'id'=>$model->import->userId)),
		),
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
