<?php
$this->breadcrumbs=array(
	'Classes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Classes', 'url'=>array('index')),
);
?>

<h1>Create Class</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'terms'=>$terms,)); ?>
