<?php
$this->breadcrumbs=array(
	'Help',
);
$this->menu=array(
	array('label'=>'About', 'url'=>array('site/page', 'view'=>'index')),
	array('label'=>'Logs', 'url'=>array('site/page', 'view'=>'logs'), 'items'=>array(
		array('label'=>'Compile Logs', 'url'=>array('site/page', 'view'=>'compile')),
		array('label'=>'Invocation Logs', 'url'=>array('site/page', 'view'=>'invocation')),
	)),
	array('label'=>'Tags and Classes', 'url'=>array('site/page', 'view'=>'tags')),
	array('label'=>'Reports', 'url'=>array('site/page', 'view'=>'reports'), 'items'=>array(
		array('label'=>'EQ', 'url'=>array('site/page', 'view'=>'eq')),
		array('label'=>'Confusion', 'url'=>array('site/page', 'view'=>'confusion')),
	)),
	array('label'=>'Users', 'url'=>array('site/page', 'view'=>'users')),
);
?>
