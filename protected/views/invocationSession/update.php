<?php
$this->breadcrumbs=array(
	'Invocation Sessions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List InvocationSession', 'url'=>array('index')),
	array('label'=>'Create InvocationSession', 'url'=>array('create')),
	array('label'=>'View InvocationSession', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage InvocationSession', 'url'=>array('admin')),
);
?>

<h1>Update InvocationSession <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>