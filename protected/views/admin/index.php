<?php
$this->breadcrumbs=array(
	'Administration',
);
?>
<h1>Administration</h1>

<?php $this->widget('zii.widgets.CMenu', array(
	'items' => array(
		array('label'=>'Tags', 'items'=>array(
			array('label'=>'Manage Tags', 'url'=>array('term/index')),
			array('label'=>'Add Tag', 'url'=>array('term/create')),
		)),
		array('label'=>'Users', 'items'=>array(
			array('label'=>'Manage Users', 'url'=>array('user/index')),
			array('label'=>'Add User', 'url'=>array('user/create')),
		)),
	),
)); ?>