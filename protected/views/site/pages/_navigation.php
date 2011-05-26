<?php
$this->breadcrumbs=array(
	'Help',
);
$this->menu=array(
	array('label'=>'About', 'url'=>array('site/page', 'view'=>'index')),
	array('label'=>'Logs', 'url'=>array('site/page', 'view'=>'logs'), 'items'=>array(
		array('label'=>'Log Types', 'url'=>array('site/page', 'view'=>'logtype')),
		array('label'=>'Timeline', 'url'=>array('site/page', 'view'=>'timeline')),
	)),
	array('label'=>'Tags and Classes', 'url'=>array('site/page', 'view'=>'tags')),
	array('label'=>'Reports', 'url'=>array('site/page', 'view'=>'reports')),
	array('label'=>'Users', 'url'=>array('site/page', 'view'=>'users')),
	array('label'=>'License', 'url'=>array('site/page', 'view'=>'license')),
	array('label'=>'Source code', 'url'=>'https://github.com/thatsmydoing/bluej-browser'),
);
?>
