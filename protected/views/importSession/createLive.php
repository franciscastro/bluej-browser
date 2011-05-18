<?php
$this->breadcrumbs=array(
	'Logs'=>array('index'),
	'Start Live Session',
);

$this->menu=array(
	array('label'=>'Manage Logs', 'url'=>array('index')),
);
?>

<h1>Start Live Session</h1>

<?php echo $this->renderPartial('_formLive', array('model'=>$model, 'terms'=>$terms)); ?>
