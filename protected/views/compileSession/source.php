<?php
$this->breadcrumbs=Yii::app()->user->getState('compileSession_breadcrumbs');
$this->breadcrumbs[] = 'Log #'.$model->deltaSequenceNumber;
?>
<div class='navigation'>
<?php $this->widget('CLinkPager', array(
	'pages'=>$pages,
));?>
</div>

<h1>Viewing Log #<?php echo $model->deltaSequenceNumber; ?></h1>

<?php $this->renderPartial('_details', array(
		'model'=>$model,
)); ?>

<div class='navigation'>
<?php $this->widget('CLinkPager', array(
	'pages'=>$pages,
));?>
</div>
