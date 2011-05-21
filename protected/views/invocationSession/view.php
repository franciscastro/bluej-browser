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
		'import.date:date'
	),
)); ?>
<?php
$this->widget('zii.widgets.jui.CJuiAccordion', array(
		'panels'=>array(
				'More Details'=>$this->renderPartial('../compileSession/_moreInformation', array('model'=>$model), true),
		),
		// additional javascript options for the accordion plugin
		'options'=>array(
				'animated'=>'bounceslide',
				'collapsible'=>'true',
				'active'=>'false',
		),
));
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'compile-session-entry-grid',
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		'deltaSequenceNumber:raw:Id',
		'className',
		'objectName',
		'methodName',
		'parameters',
		'result',
		'timestamp:time:Time',
	),
));
?>