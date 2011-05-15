<?php
$importSessionId = $model->session->import->importSessionId;
$this->breadcrumbs=array(
			'Imports'=>array('importSession/index'),
			'Import #'.$importSessionId=>array('importSession/view', 'id'=>$importSessionId),
			'Session #'.$_GET['id'],
		);
?>

<h1>View CompileSession #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'session.user.name',
		'session.date:date',
		array(
			'label'=>'EQ',
			'type'=>'raw',
			'value'=>($eqModel = EqCalculation::model()->findByAttributes(array(
				'compileSessionId'=>$model->id,
			))) != null ? ($eqModel->eq >= 0) ? $eqModel->eq : 'n/a' : 'n/a',
		),
	),
));
?>
<?php
$this->widget('zii.widgets.jui.CJuiAccordion', array(
		'panels'=>array(
				'More Details'=>$this->renderPartial('_moreInformation', array('model'=>$model), true),
		),
		// additional javascript options for the accordion plugin
		'options'=>array(
				'animated'=>'bounceslide',
				'collapsible'=>'true',
				'active'=>'false',
		),
));
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'compile-session-entry-grid',
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		'deltaSequenceNumber:raw:Id',
		'fileName',
		'timestamp:time:Time',
		'messageText',
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array(
				'view'=>array(
					'label'=>'View',
					'url'=>'Yii::app()->controller->createUrl("source",array("page"=>$row+1))',
				),
				'compare'=>array(
					'label'=>'Compare with next',
					'url'=>'Yii::app()->controller->createUrl("compare",array("page"=>$row+1))',
					'imageUrl'=>Yii::app()->baseURL . "/images/doc_convert.png",
					'visible'=>'$row < ' . ($dataProvider->totalItemCount-1),
				),
				'delete'=>array(
					'label'=>'Delete',
					'url'=>'Yii::app()->controller->createUrl("deleteEntry",array("id"=>$data->id))',
					'visible'=>'Yii::app()->user->hasRole(array("Administrator", "Researcher"))',
				),
			),
			'template'=>'{view} {compare} {delete}'
		),
	),
));
?>
