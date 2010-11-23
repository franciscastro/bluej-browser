<?php
$this->breadcrumbs=array(
	'Sections'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Manage Sections', 'url'=>array('index')),
	array('label'=>'Create Section', 'url'=>array('create')),
);
?>

<h1>Update Section <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
