<?php
$logSessionId = $model->log->logSessionId;
$this->breadcrumbs=array(
			'Logs'=>array('logSession/index'),
			'Log Session #'.$logSessionId=>array('logSession/view', 'id'=>$logSessionId),
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
			'value'=>CHtml::link($model->log->user->name, array('user/view', 'id'=>$model->log->userId)),
		),
		'log.date:date'
	),
)); ?>
<?php
$this->widget('zii.widgets.jui.CJuiAccordion', array(
		'panels'=>array(
				'More Details'=>$this->renderPartial('../compileLog/_moreInformation', array('model'=>$model), true),
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
	'id'=>'compile-log-entry-grid',
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