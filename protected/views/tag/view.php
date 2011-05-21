<?php
$this->breadcrumbs=array(
	'Administration'=>array('admin/index'),
	'Tags'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Manage Tags', 'url'=>array('index')),
	array('label'=>'Update Tag', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Tag', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>Items tagged with <?php echo $model->name; ?></h1>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		'id',
		array(
			'name'=>'source',
			'type'=>'raw',
			'value'=>'($data->source != "live") ? "upload" : "live"',
		),
		'start:datetime',
		'end:datetime',
		'remarks',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			'buttons'=>array(
				'view'=>array(
					'url'=>'Yii::app()->controller->createUrl("logSession/view",array("id"=>$data->primaryKey))',
				),
			),
		),
	),
));
