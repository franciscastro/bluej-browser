<?php
$this->breadcrumbs=array(
	'Imports'=>array('index'),
	'Start Live Session',
);

$this->menu=array(
	array('label'=>'Manage ImportSession', 'url'=>array('index')),
	array('label'=>'Upload Log Files', 'url'=>array('create')),
);
?>

<h1>Create Live Session</h1>

<?php echo $this->renderPartial('_formLive', array('model'=>$model, 'terms'=>$terms)); ?>
