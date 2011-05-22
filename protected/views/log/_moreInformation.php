<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'deltaVersion',
		'extensionVersion',
		'systemUser',
		'home',
		'osName',
		'osVersion',
		'osArch',
		'ipAddress',
		'hostName',
		'locationId',
		'projectId',
		'logId',
		'projectPath',
		'packagePath',
	),
)); ?>
