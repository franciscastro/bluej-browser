<?php
$this->breadcrumbs=array(
	'Classes'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Manage Classes', 'url'=>array('index')),
	array('label'=>'Add Class', 'url'=>array('create')),
	array('label'=>'Change Teachers', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Class', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1><?php echo $model->name; ?></h1>

<h2>Teachers</h2>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'teacher-grid',
	'dataProvider'=>new CArrayDataProvider($model->teachers),
	'columns'=>array(
		'name:raw:Name',
	),
)); ?>

<h2>Students</h2>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'student-grid',
	'dataProvider'=>new CArrayDataProvider($model->students),
	'columns'=>array(
		'name:raw:Name',
		'computer:raw:Computer',
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array(
				'view'=>array(
					'url'=>'Yii::app()->controller->createUrl("user/view",array("id"=>$data->id))',
				),
				'update'=>array(
					'url'=>'Yii::app()->controller->createUrl("user/update",array("id"=>$data->id))',
				),
				'delete'=>array(
					'url'=>'Yii::app()->controller->createUrl("user/delete",array("id"=>$data->id))',
				),
			),
		),
	),
)); ?>
