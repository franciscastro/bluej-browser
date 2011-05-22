<?php
if($model->logId < 0) $model->logId = -$model->logId;
$logSessionId = $model->log->log->logSessionId;
$this->breadcrumbs=array(
	'Logs'=>array('logSession/index'),
	'Log Session #'.$logSessionId=>array('logSession/view', 'id'=>$logSessionId),
	'Log #'.$model->logId=>array('log/view', 'id'=>$model->logId),
	'Compile Log Entry #'.$model->deltaSequenceNumber,
);
?>
<div class='navigation'>
<?php if(isset($pages)) $this->widget('CLinkPager', array(
	'header'=>'&nbsp;',
	'pages'=>$pages,
));?>
</div>

<h1>Viewing Entry #<?php echo $model->deltaSequenceNumber; ?></h1>

<?php $this->renderPartial('_details', array(
		'model'=>$model,
)); ?>

<div class='navigation'>
<?php if(isset($pages)) $this->widget('CLinkPager', array(
	'header'=>'&nbsp;',
	'pages'=>$pages,
));?>
</div>
