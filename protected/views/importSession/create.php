<?php
$this->breadcrumbs=array(
	'Logs'=>array('index'),
	'Upload',
);

$this->menu=array(
	array('label'=>'Manage Logs', 'url'=>array('index')),
);
?>

<h1>Upload Log Files</h1>

<p>For past records, please upload files in a zip file</p>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'terms'=>$terms)); ?>
