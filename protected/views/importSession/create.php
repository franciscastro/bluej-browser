<?php
$this->breadcrumbs=array(
	'Imports'=>array('index'),
	'Upload',
);

$this->menu=array(
	array('label'=>'Manage Imports', 'url'=>array('index')),
	array('label'=>'Start Live Session', 'url'=>array('createLive')),
);
?>

<h1>Upload Log Files</h1>

<p>For past records, please upload files in a zip file</p>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'terms'=>$terms)); ?>
