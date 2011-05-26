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

<h1>Edit <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'tags'=>$tags,)); ?>
