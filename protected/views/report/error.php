<?php
$this->makeDetailBreadcrumbs('Error Report');
?>

<h1>Error Report</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'columns'=>array(
			array(
				'name' => 'messageText',
				'value' => '$data["messageText"] == "" ? "<no error>" : $data["messageText"]'
			),
			'count',
		),
)); ?>
