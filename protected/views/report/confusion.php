<?php
$this->makeDetailBreadcrumbs('Confusion Report');
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
			array(
				'name' => 'name',
				'type' => 'raw',
				'value' => '(isset($_GET["id"])) ? CHtml::link($data["name"], array("log/view", "id"=>$data["logId"])) : CHtml::link($data["name"], array("user/view", "id"=>$data["userId"]))',
			),
			array(
				'name' => 'confusion',
				'value' => 'sprintf("%.2f%%", $data["confusion"] * 100)',
				'cssClassExpression' => '"right"',
			),
			array(
				'name' => 'clips',
				'value' => '$data["clips"]',
				'cssClassExpression' => '"right"',
			),
		),
)); ?>
