<?php
$this->breadcrumbs=array(
	'Imports'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'View Information', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Imports', 'url'=>array('index')),	
	array('label'=>'Upload Log Files', 'url'=>array('create')),
	array('label'=>'Start Live Session', 'url'=>array('createLive')),
);
?>

<h1>Update Import #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'terms'=>$terms)); ?>
