<?php
$this->breadcrumbs=Yii::app()->user->getState('compileSession_breadcrumbs');
$this->breadcrumbs[] = 'Entry #'.$model->deltaSequenceNumber . ' vs Entry #' . $model2->deltaSequenceNumber;
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
