<?php
$this->breadcrumbs=array(
	'Logs' => array('importSession/index', 'tags'=>$_GET['tags']),
	'General Summary' => array('summary', 'tags'=>$_GET['tags']),
	'Time Delta Report',
);

?>

<h1>Time Delta Report</h1>

<?php echo CHtml::beginForm(array('', 'tags'=>$_GET['tags']), 'get'); ?>
	<?php echo CHtml::label('Interval ', 'interval'); ?>
	<?php echo CHtml::textField('interval', $interval); ?>
<?php echo CHtml::endForm(); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'columns'=>array(
			'delta' => array(
				'name' => 'Range',
				'value' => 'sprintf("%d - %d", $data["delta"] * '.$interval.', ($data["delta"]+1) * '.$interval.')',
			),
			'count:raw:Count',
		),
)); ?>
