<?php
$this->makeDetailBreadcrumbs('EQ Report');
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
			array(
				'name' => 'name',
				'type' => 'raw',
				'value' => '(isset($_GET["id"])) ? CHtml::link($data["name"], array("log/view", "id"=>$data["logId"])) : CHtml::link($data["name"], array("user/view", "id"=>$data["userId"]))',
			),
			'eq:raw:EQ',
		),
)); ?>
