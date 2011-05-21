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
			'name' => array(
				'name' => 'Student',
				'type' => 'raw',
				'value' => '(isset($_GET["id"])) ? CHtml::link($data["name"], array("compileLog/view", "id"=>$data["logId"])) : CHtml::link($data["name"], array("user/view", "id"=>$data["userId"]))',
			),
			'confusion' => array(
				'name' => 'Confusion Rate',
				'value' => 'sprintf("%.2f%%", $data["confusion"] * 100)',
				'cssClassExpression' => '"right"',
			),
			'clips' => array(
				'name' => 'Clips',
				'value' => '$data["clips"]',
				'cssClassExpression' => '"right"',
			),
		),
)); ?>
