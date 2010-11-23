<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deltaVersion')); ?>:</b>
	<?php echo CHtml::encode($data->deltaVersion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('extensionVersion')); ?>:</b>
	<?php echo CHtml::encode($data->extensionVersion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('systemUser')); ?>:</b>
	<?php echo CHtml::encode($data->systemUser); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('home')); ?>:</b>
	<?php echo CHtml::encode($data->home); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('osName')); ?>:</b>
	<?php echo CHtml::encode($data->osName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('osVersion')); ?>:</b>
	<?php echo CHtml::encode($data->osVersion); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('osArch')); ?>:</b>
	<?php echo CHtml::encode($data->osArch); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ipAddress')); ?>:</b>
	<?php echo CHtml::encode($data->ipAddress); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hostName')); ?>:</b>
	<?php echo CHtml::encode($data->hostName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('locationId')); ?>:</b>
	<?php echo CHtml::encode($data->locationId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('projectId')); ?>:</b>
	<?php echo CHtml::encode($data->projectId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sessionId')); ?>:</b>
	<?php echo CHtml::encode($data->sessionId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('projectPath')); ?>:</b>
	<?php echo CHtml::encode($data->projectPath); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('packagePath')); ?>:</b>
	<?php echo CHtml::encode($data->packagePath); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deltaName')); ?>:</b>
	<?php echo CHtml::encode($data->deltaName); ?>
	<br />

	*/ ?>

</div>