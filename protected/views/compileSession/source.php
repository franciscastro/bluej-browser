<?php
$this->breadcrumbs=Yii::app()->user->getState('compileSession_breadcrumbs');
$this->breadcrumbs[] = 'Entry #'.$model->deltaSequenceNumber;
?>
<div class='navigation'>
<?php $this->widget('CLinkPager', array(
	'pages'=>$pages,
));?>
</div>

<h1>Viewing Entry #<?php echo $model->deltaSequenceNumber; ?></h1>

<?php $this->renderPartial('_details', array(
		'model'=>$model,
)); ?>

<div class='navigation'>
<?php $this->widget('CLinkPager', array(
	'pages'=>$pages,
));?>
</div>
