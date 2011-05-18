<?php
$this->breadcrumbs=array(
	'Classes'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Manage Classes', 'url'=>array('index')),
	array('label'=>'Create Class', 'url'=>array('create')),
);
?>

<h1>Update Class <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'terms'=>$terms,)); ?>
