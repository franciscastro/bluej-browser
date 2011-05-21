<?php
$this->breadcrumbs=array(
	'Logs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'View Information', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Logs', 'url'=>array('index')),
);
?>

<h1>Update Log #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'tags'=>$tags)); ?>
