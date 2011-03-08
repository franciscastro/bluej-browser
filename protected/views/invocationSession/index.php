<?php
$this->breadcrumbs=array(
	'Invocation Sessions',
);

$this->menu=array(
	array('label'=>'Create InvocationSession', 'url'=>array('create')),
	array('label'=>'Manage InvocationSession', 'url'=>array('admin')),
);
?>

<h1>Invocation Sessions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
