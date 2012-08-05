<?php
$this->breadcrumbs=array(
	'Logs'=>array('index'),
	'Schedule Live Session',
);

$this->menu=array(
	array('label'=>'Manage Logs', 'url'=>array('index')),
);

$this->contextHelp = "Schedule collection of logs for a particular section. You may opt to filter the collected logs based on the computer name, in case two different labs are active at the same time.";
?>

<h1>Schedule Live Session</h1>

<?php echo $this->renderPartial('_formLive', array('model'=>$model, 'tags'=>$tags)); ?>
