<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Update '.$model->name,
);

$this->menu=array(
	array('label'=>'Manage Users', 'url'=>array('index')),
	array('label'=>'Add User', 'url'=>array('create')),
);
?>

<h1>Update User <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
