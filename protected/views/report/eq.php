<?php
$this->breadcrumbs=array(
	'Logs' => array('importSession/index', 'tags'=>$_GET['tags']),
	'General Summary' => array('summary', 'tags'=>$_GET['tags']),
	'EQ Report',
);

?>

<h1>EQ Report</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$viewData,
	'attributes'=>array(
		'average',
	),
)); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'columns'=>array(
			'name:raw:Student',
			'eq:raw:EQ',
		),
)); ?>
