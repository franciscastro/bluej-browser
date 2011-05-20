<?php
$this->breadcrumbs=array(
	'Administration'=>array('admin/index'),
	'Users'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Users', 'url'=>array('index')),
);
?>

<h1>Create User</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
