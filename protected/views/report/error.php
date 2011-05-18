<?php
$this->breadcrumbs=array(
	'Logs' => array('importSession/index', 'tags'=>$_GET['tags']),
	'General Summary' => array('summary', 'tags'=>$_GET['tags']),
	'Error Report',
);

?>

<h1>Error Report</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'columns'=>array(
			'messageText' => array(
				'name' => 'Error',
				'value' => '$data["messageText"] == "" ? "<no error>" : $data["messageText"]'
			),
			'count:raw:Count',
		),
)); ?>
