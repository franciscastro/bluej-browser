<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'deltaStartTime',
		'deltaEndTime',
		'filePath',
		'fileEncoding',
		'compilesPerFile',
		'totalCompiles',
	),
)); ?>
