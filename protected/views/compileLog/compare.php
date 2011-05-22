<?php
$logSessionId = $model->log->log->logSessionId;
$this->breadcrumbs=array(
	'Logs'=>array('logSession/index'),
	'Log Session #'.$logSessionId=>array('logSession/view', 'id'=>$logSessionId),
	'Log #'.$model->logId=>array('log/view', 'id'=>$model->logId),
	'Compile Log Entry #'.$model->deltaSequenceNumber,
);
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . '/css/compare.css');
?>

<div class='navigation'>
<?php $this->widget('CLinkPager', array(
	'header'=>'&nbsp;',
	'pages'=>$pages,
));?>
</div>

<h1>Comparing Entries #<?php echo $model->deltaSequenceNumber; ?> and #<?php echo $model2->deltaSequenceNumber; ?> </h1>

<table id='compare-container'>
	<tr>
		<td>
		<?php $this->renderPartial('_details', array(
				'model'=>$model,
				'diff'=>$diff,
		)); ?>
		</td>
		<td>
		<?php $this->renderPartial('_details', array(
				'model'=>$model2,
				'diff'=>$diff,
		)); ?>
		</td>
	</tr>
</table>

<div class='navigation'>
<?php $this->widget('CLinkPager', array(
	'header'=>'&nbsp;',
	'pages'=>$pages,
));?>
</div>
