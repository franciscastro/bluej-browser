<?php
$this->breadcrumbs=array(
	'Logs'=>array('index'),
	'Upload',
);

$this->menu=array(
	array('label'=>'Manage Logs', 'url'=>array('index')),
);

$this->contextHelp = <<<CNH
<p>
Old log files from the previous data collection server may be
put in a zip file and uploaded here. The logs will be tagged
automatically based on the folder hierarchy inside the zip file.
</p>
CNH;
?>

<h1>Upload Log Files</h1>

<p>For past records, please upload files in a zip file</p>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'tags'=>$tags)); ?>
