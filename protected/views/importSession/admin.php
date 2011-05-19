<?php
$this->breadcrumbs=array(
	'Logs',
);

$this->menu=array(
	array('label'=>'Upload Log Files', 'url'=>array('create')),
	array('label'=>'Start Live Session', 'url'=>array('createLive')),
	array('label'=>'Export All', 'url'=>isset($_GET['tags']) ? array('exportAll', 'tags'=>$_GET['tags']) : array('exportAll')),
	array('label'=>'Generate Report', 'url'=>array('report/summary', 'tags'=>(isset($_GET['tags']) ? $_GET['tags'] : ''))),
);

$this->contextHelp = <<<CNH
<p>
The search finds all records which have all tags that you specify.
You can use this to, for example, see a list of all logs from CS21a
lab A regardless of section.
</p>
<p>
Also note that exporting logs and generating reports will be limited
to the currently displayed logs.
</p>
CNH;
/*
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('import-session-grid', {
		data: $(this).serialize()
	});
	return false;
});
");*/
?>

<h1>Log Sessions</h1>

<?php $this->renderPartial('_search'); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'import-session-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		'id',
		array(
			'name' => 'Tags',
			'type' => 'raw',
			'value' => 'Term::displayTerms($data->terms)',
		),
		'start:datetime',
		'end:datetime',
		'remarks',
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array(
				'export'=>array(
					'label'=>'Export',
					'url'=>'Yii::app()->controller->createUrl("export",array("id"=>$row+1))',
					'imageUrl'=>Yii::app()->baseURL . "/images/disk.png",
				),
			),
			'template'=>'{view} {update} {export}',
		),
	),
)); ?>
