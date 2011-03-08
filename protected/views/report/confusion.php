<?php
$this->breadcrumbs=array(
	'Reports' => array('index'),
	'General Summary' => array('summary', 'tags'=>$_GET['tags']),
	'Confusion Report',
);

?>

<h1>Confusion Report</h1>

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
			'confusion:raw:Confusion Rate',
		),
)); ?>
