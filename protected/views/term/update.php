<?php
$this->breadcrumbs=array(
	'Administration'=>array('admin/index'),
	'Tags'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Manage Tags', 'url'=>array('index')),
	array('label'=>'View Tag', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1>Update Tag <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
