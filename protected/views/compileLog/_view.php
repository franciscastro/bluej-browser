<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'label'=>'EQ',
			'type'=>'raw',
			'value'=>($eqModel = EqCalculation::model()->findByAttributes(array(
				'logId'=>$model->id,
			))) != null ? ($eqModel->eq >= 0) ? $eqModel->eq : 'n/a' : 'n/a',
		),
		array(
			'label'=>'Confusion Rate',
			'type'=>'raw',
			'value'=>($confusionModel = Confusion::model()->findByAttributes(array(
				'logId'=>$model->id,
			))) != null ? ($confusionModel->clips >= 0) ? sprintf("%.2f (Clips: %d)", $confusionModel->confusion, $confusionModel->clips) : 'n/a' : 'n/a',
		),
	),
));
?>
<?php
$dataProvider =  new CActiveDataProvider('CompileLogEntry', array(
	'criteria'=> array(
		'condition'=>'logId='.$model->id,
	),
	'sort'=>array(
		'sortVar'=>'sort',
	),
	'pagination'=>false,
));
$dataProvider->sort->defaultOrder = 'timestamp';
$deleted =  new CActiveDataProvider('CompileLogEntry', array(
	'criteria'=> array(
		'condition'=>'logId=-'.$model->id,
	),
	'pagination'=>false,
));
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'compile-log-entry-grid',
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
					'url'=>'Yii::app()->controller->createUrl("compileLog/source",array("page"=>$row+1, "logId"=>'.$model->id.', "sort"=>isset($_GET["sort"]) ? $_GET["sort"] : ""))',
				),
				'compare'=>array(
					'label'=>'Compare with next',
					'url'=>'Yii::app()->controller->createUrl("compileLog/compare",array("page"=>$row+1, "logId"=>'.$model->id.', "sort"=>isset($_GET["sort"]) ? $_GET["sort"] : ""))',
					'imageUrl'=>Yii::app()->baseURL . "/images/doc_convert.png",
					'visible'=>'$row < ' . ($dataProvider->totalItemCount-1),
				),
				'update'=>array(
					'url'=>'Yii::app()->controller->createUrl("compileLog/updateEntry",array("id"=>$data->id))',
					'visible'=>'Yii::app()->user->hasRole(array("Administrator", "Researcher"))',
				),
				'delete'=>array(
					'url'=>'Yii::app()->controller->createUrl("compileLog/deleteEntry",array("id"=>$data->id))',
					'visible'=>'Yii::app()->user->hasRole(array("Administrator", "Researcher"))',
				),
			),
			'template'=>'{view} {compare} {update} {delete}'
		),
	),
));
?>

<?php if($deleted->totalItemCount > 0): ?>
<br>
<h3>Deleted entries</h3>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'deleted-compile-log-entry-grid',
	'dataProvider'=>$deleted,
	'columns'=>array(
		'deltaSequenceNumber:raw:Id',
		'fileName',
		'timestamp:time:Time',
		'messageText',
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array(
				'view'=>array(
					'url'=>'Yii::app()->controller->createUrl("compileLog/source",array("id"=>$data->id))',
				),
				'update'=>array(
					'url'=>'Yii::app()->controller->createUrl("compileLog/updateEntry",array("id"=>$data->id))',
					'visible'=>'Yii::app()->user->hasRole(array("Administrator", "Researcher"))',
				),
			),
			'template'=>'{view} {update}'
		),
	),
));
?>
<?php endif; ?>
