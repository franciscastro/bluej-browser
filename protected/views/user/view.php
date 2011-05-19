<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Manage Users', 'url'=>array('index')),
	array('label'=>'Add User', 'url'=>array('create')),
	array('label'=>'Update User', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1><?php echo $model->name; ?></h1>
<h2>Statistics</h2>
<?php
$data = $model->getStatistics();
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model->getStatistics(),
	'attributes'=>array(
		'compileCount' => array(
			'label' => 'Compile Count',
			'value' => ($data['sessionCount'] == 0) ? null : sprintf("%d (Average per session: %.2f)", $data["compileCount"], $data["compileCount"] / $data["sessionCount"]),
		),
		'errorCount' => array(
			'label' => 'Error Count',
			'value' => ($data['sessionCount'] == 0) ? null : sprintf("%d (Average per session: %.2f)", $data["errorCount"], $data["errorCount"] / $data["sessionCount"]),
		),
		'errorPercentage' => array(
			'label' => 'Error Percentage',
			'value' => ($data['compileCount'] == 0) ? null : sprintf("%.2f%%", $data["errorCount"] / $data["compileCount"] * 100),
		),
		'eq:raw:Average EQ',
		'confusion' => array(
			'label' => 'Average Confusion Rate',
			'value' => sprintf("%.2f%% (Average clips: %d)", $data["confusion"] * 100, $data['clipCount']),
		),
	),
)); ?>
<br>
<h2>Compile Logs</h2>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'import-grid',
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		'id',
		array(
			'name'=>'path',
			'value'=>'($data->path == "live") ? $data->user->computer . "-" . $data->type : basename($data->path)',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {delete}',
			'buttons'=>array(
				'view'=>array(
					'url'=>'Yii::app()->controller->createUrl("import/view", array("id"=>$data->id))',
					'visible'=>'isset($data->type)',
				),
				'delete'=>array(
					'url'=>'Yii::app()->controller->createUrl("import/delete", array("id"=>$data->id))',
					'visible'=>'isset($data->type)',
				),
			),
		),
	),
)); ?>