<?php
$this->breadcrumbs=array(
	'Administration'=>array('admin/index'),
	'Tags'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Tags', 'url'=>array('index')),
);
?>

<h1>Add Tag</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
