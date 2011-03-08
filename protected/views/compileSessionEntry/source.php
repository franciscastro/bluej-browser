<?php
$this->breadcrumbs=Yii::app()->user->getState('a_Breadcrumbs');
$this->breadcrumbs[] = 'Log #'.$model->deltaSequenceNumber;
?>
<div class='navigation'>
<?php $this->widget('CLinkPager', array(
	'header'=>'&nbsp;',
	'pages'=>$pages,
));?>
</div>

<h1>Viewing Log #<?php echo $model->deltaSequenceNumber; ?></h1>

<?php $this->renderPartial('_details', array(
		'model'=>$model,
)); ?>

<div class='navigation'>
<?php $this->widget('CLinkPager', array(
	'header'=>'&nbsp;',
	'pages'=>$pages,
));?>
</div>
