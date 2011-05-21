<?php
$this->breadcrumbs=array(
	'Administration'=>array('admin/index'),
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
			'value' => ($data['logCount'] == 0) ? null : sprintf("%d (Average per log: %.2f)", $data["compileCount"], $data["compileCount"] / $data["logCount"]),
		),
		'errorCount' => array(
			'label' => 'Error Count',
			'value' => ($data['logCount'] == 0) ? null : sprintf("%d (Average per log: %.2f)", $data["errorCount"], $data["errorCount"] / $data["logCount"]),
		),
		'errorPercentage' => array(
			'label' => 'Error Percentage',
			'value' => ($data['compileCount'] == 0) ? null : sprintf("%.2f%%", $data["errorCount"] / $data["compileCount"] * 100),
		),
		'eq:raw:Average EQ',
		'confusion' => array(
			'label' => 'Average Confusion Rate',
			'value' => sprintf("%.2f%% (Average clips: %.4f)", $data["confusion"] * 100, $data['clipCount']),
		),
	),
)); ?>
<br>
<h2>Compile Logs</h2>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'log-grid',
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		'id',
		array(
			'name' => 'Tags',
			'type' => 'raw',
			'value' => 'Tag::displayTags($data->logSession->tags)',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{compile} {invocation} {delete}',
			'buttons'=>array(
				'compile'=>array(
					'url'=>'Yii::app()->controller->createUrl("compileLog/view", array("id"=>$data->id))',
					'visible'=>'$data->compileLog != null',
					'imageUrl'=>Yii::app()->baseURL . '/images/page_white_cup.png',
				),
				'invocation'=>array(
					'url'=>'Yii::app()->controller->createUrl("invocationLog/view", array("id"=>$data->id))',
					'visible'=>'$data->invocationLog != null',
					'imageUrl'=>Yii::app()->baseURL . '/images/page_white_gear.png',
				),
			),
		),
	),
)); ?>
