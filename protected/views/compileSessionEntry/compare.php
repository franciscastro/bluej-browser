<?php
$this->breadcrumbs=Yii::app()->user->getState('a_Breadcrumbs');
$this->breadcrumbs[] = 'Log #'.$model->deltaSequenceNumber . ' vs Log #' . $model2->deltaSequenceNumber;
?>

<div class='navigation'>
<?php $this->widget('CLinkPager', array(
	'header'=>'&nbsp;',
	'pages'=>$pages,
));?>
</div>

<h1>Comparing Logs #<?php echo $model->deltaSequenceNumber; ?> and #<?php echo $model2->deltaSequenceNumber; ?> </h1>

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
