<?php
$this->makeDetailBreadcrumbs('Error Report');
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
