<?php
$this->breadcrumbs=array(
	'Administration',
);
?>
<h1>Administration</h1>

<?php $this->widget('zii.widgets.CMenu', array(
	'items' => array(
		array('label'=>'Tags', 'items'=>array(
			array('label'=>'Manage Tags', 'url'=>array('tag/index')),
			array('label'=>'Add Tag', 'url'=>array('tag/create')),
			array('label'=>'Merge Tags', 'url'=>array('tag/merge')),
		)),
		array('label'=>'Users', 'items'=>array(
			array('label'=>'Manage Users', 'url'=>array('user/index')),
			array('label'=>'Add User', 'url'=>array('user/create')),
		)),
	),
)); ?>