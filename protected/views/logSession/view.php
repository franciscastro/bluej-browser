<?php
$this->breadcrumbs=array(
	'Logs'=>array('index'),
	'Log Session #' . $model->id,
);

$this->menu=array(
	array('label'=>'Stop Session', 'url'=>'#', 'linkOptions'=>array('submit'=>array('stopLive','id'=>$model->id),'confirm'=>'Are you sure you want to stop the logging?'), 'visible'=>($model->source == 'live' && $model->hasStarted() && !$model->hasEnded())),
	array('label'=>'View Report', 'url'=>array('report/summary', 'id'=>$model->id)),
	array('label'=>'Export Logs', 'url'=>array('export', 'id'=>$model->id)),
	array('label'=>'Update Information', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Logs', 'url'=>array('index')),
);
?>

<h1>Log Session #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'label' => 'Tags',
			'type' => 'raw',
			'value' => Tag::displayTags($model->tags),
		),
		'source',
		array(
			'label' => 'Path',
			'name' => 'path',
			'visible' => ($model->source != 'live'),
		),
		array(
			'label' => 'Filter',
			'name' => 'path',
			'visible' => ($model->source == 'live'),
		),
		'start:datetime',
		'end:datetime',
		'remarks',
	),
)); ?>

<?php /*if($model->source == 'live' && $model->end == null): ?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'log-log-form',
	'action'=>array('stopLive', 'id'=>$model->id),
	'enableAjaxValidation'=>false,
)); ?>
<?php echo CHtml::submitButton('Stop Session'); ?>
<?php $this->endWidget(); ?>
<?php endif; */?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'log-grid',
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		'id',
		array(
			'name'=>'username',
			'type'=>'raw',
			'value'=>'CHtml::link($data->user->name, array("user/view", "id"=>$data->user->id))',
			'sortable'=>true,
		),
		'user.computer:text:Computer',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {delete}',
			'buttons'=>array(
				'view'=>array(
					'url'=>'Yii::app()->controller->createUrl("log/view", array("id"=>$data->id))',
				),
				'delete'=>array(
					'url'=>'Yii::app()->controller->createUrl("log/delete", array("id"=>$data->id))',
				),
			),
		),
	),
)); ?>
