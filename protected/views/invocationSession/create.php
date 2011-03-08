<?php
$this->breadcrumbs=array(
	'Invocation Sessions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List InvocationSession', 'url'=>array('index')),
	array('label'=>'Manage InvocationSession', 'url'=>array('admin')),
);
?>

<h1>Create InvocationSession</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>