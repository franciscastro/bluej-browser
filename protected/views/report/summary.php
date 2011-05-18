<?php
if(isset($_GET['id'])) {
	$this->breadcrumbs=array(
		'Logs' => array('importSession/index'),
		'Log Session #' . $_GET['id'] => array('importSession/view', 'id'=>$_GET['id']),
		'Summary'
	);
}
else if(isset($_GET['tags'])){
	$this->breadcrumbs=array(
		'Logs' => array('importSession/index', 'tags'=>$_GET['tags']),
		'General Summary'
	);
}
else {
	$this->breadcrumbs=array(
		'Logs' => array('importSession/index'),
		'General Summary'
	);
}

$ajax = CHtml::ajax(array(
	'update'=>'#general-summary',
));

$script = <<<EOS
function autorefresh() {
	$ajax
	setTimeout("autorefresh()", 5000);
}

autorefresh();
EOS;

echo CHtml::script($script);
?>

<h1><?php echo isset($_GET['id']) ? "Summary of Log Session #" . $_GET['id'] : "General Summary" ?></h1>
<div id="general-summary">
<?php $this->renderPartial('_summary', array(
	'topEqData'=>$topEqData,
	'topErrorsData'=>$topErrorsData,
	'timeDeltaData'=>$timeDeltaData,
	'topConfusedData'=>$topConfusedData,
));
?>
</div>
