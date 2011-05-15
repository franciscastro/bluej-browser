<?php
$this->breadcrumbs=array(
	'Imports',
);

$this->menu=array(
	array('label'=>'Upload Log Files', 'url'=>array('create')),
	array('label'=>'Start Live Session', 'url'=>array('createLive')),
	array('label'=>'Export All', 'url'=>isset($_GET['tags']) ? array('exportAll', 'tags'=>$_GET['tags']) : array('exportAll')),
);
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

<h1>Manage Imports</h1>

<?php /*
<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
*/ ?>

<p>
You may search by tags as well. The search finds all records which have all tags that you specify.
</p>
<?php $this->renderPartial('../term/_search'); ?>

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
				),
			),
			'template'=>'{view} {update} {export}',
		),
	),
)); ?>
