<?php
$this->breadcrumbs=array(
	'Sections'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Sections', 'url'=>array('index')),
);
?>

<h1>Create Section</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
