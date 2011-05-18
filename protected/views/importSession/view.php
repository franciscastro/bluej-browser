<?php
$this->breadcrumbs=array(
	'Logs'=>array('index'),
	'Log Session #' . $model->id,
);

$this->menu=array(
	array('label'=>'Stop Session', 'url'=>'#', 'linkOptions'=>array('submit'=>array('stopLive','id'=>$model->id),'confirm'=>'Are you sure you want to stop the logging?'), 'visible'=>($model->source == 'live' && $model->end == null)),
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
		'id',
		array(
			'label' => 'Tags',
			'type' => 'raw',
			'value' => Term::displayTerms($model->terms),
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
	'id'=>'import-session-form',
	'action'=>array('stopLive', 'id'=>$model->id),
	'enableAjaxValidation'=>false,
)); ?>
<?php echo CHtml::submitButton('Stop Session'); ?>
<?php $this->endWidget(); ?>
<?php endif; */?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'import-grid',
	'dataProvider'=>$import->search(),
	'columns'=>array(
		'id',
		'user.name:raw:Student',
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
