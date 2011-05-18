<?php
$this->breadcrumbs=array(
	'Logs' => array('importSession/index', 'tags'=>$_GET['tags']),
	'General Summary' => array('summary', 'tags'=>$_GET['tags']),
	'Confusion Report',
);

?>

<h1>Confusion Report</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$viewData,
	'attributes'=>array(
		'average' => array(
			'label' => 'Average',
			'value' => sprintf("%.2f", $viewData["average"] * 100),
		),
	),
)); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'columns'=>array(
			'name:raw:Student',
			'confusion' => array(
				'name' => 'Confusion Rate',
				'value' => 'sprintf("%.2f", $data["confusion"] * 100)',
			),
		),
)); ?>
