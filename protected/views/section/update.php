<?php
$this->breadcrumbs=array(
	'Classes'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Edit Teachers',
);

$this->menu=array(
	array('label'=>'Manage Classes', 'url'=>array('index')),
	array('label'=>'Add Class', 'url'=>array('create')),
);
?>

<h1>Edit Teachers of <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'terms'=>$terms,)); ?>
