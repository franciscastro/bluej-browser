<?php
$this->breadcrumbs=array(
	'Imports'=>array('index'),
	'Import #' . $model->id,
);

$this->menu=array(
	array('label'=>'View Report', 'url'=>array('report/summary', 'tags'=>implode(',', $this->modelArrayToAttributeArray($model->terms, 'name')))),
	array('label'=>'Stop Session', 'url'=>'#', 'linkOptions'=>array('submit'=>array('stopLive','id'=>$model->id),'confirm'=>'Are you sure you want to stop the logging?'), 'visible'=>($model->source == 'live' && $model->end == null)),
	array('label'=>'Export Logs', 'url'=>array('export', 'id'=>$model->id)),
	array('label'=>'Update Information', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Manage Imports', 'url'=>array('index')),	
	array('label'=>'Upload Log Files', 'url'=>array('create')),
	array('label'=>'Start Live Session', 'url'=>array('createLive')),
);
?>

<h1>View Import #<?php echo $model->id; ?></h1>

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
		'sessionId',
		array(
			'name'=>'path',
			'value'=>'basename($data->path)',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {delete}',
			'buttons'=>array(
				'view'=>array(
					'url'=>'Yii::app()->controller->createUrl("session/view", array("id"=>$data->sessionId))',
					'visible'=>'$data->sessionId != 0',
				),
				'delete'=>array(
					'url'=>'Yii::app()->controller->createUrl("import/delete", array("id"=>$data->id))',
					'visible'=>'$data->sessionId != 0',
				),
			),
		),
	),
)); ?>
