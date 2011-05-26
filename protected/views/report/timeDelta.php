<?php
$this->makeDetailBreadcrumbs('Time Delta Report');
?>

<h1>Time Delta Report</h1>

<?php echo CHtml::beginForm(($isSingle ? array('', 'id'=>$_GET['id']) : array('', 'tags'=>$_GET['tags'])), 'get'); ?>
	<?php echo CHtml::label('Interval ', 'interval'); ?>
	<?php echo CHtml::textField('interval', $interval); ?>
<?php echo CHtml::endForm(); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
		'columns'=>array(
			array(
				'name' => 'delta',
				'value' => 'sprintf("%d - %d", $data["delta"] * '.$interval.', ($data["delta"]+1) * '.$interval.')',
			),
			'count',
		),
)); ?>
