<?php
$this->breadcrumbs=array(
	'Classes'=>array('index'),
	'Add',
);

$this->menu=array(
	array('label'=>'Manage Classes', 'url'=>array('index')),
);
?>

<h1>Add Class</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'tags'=>$tags,)); ?>
