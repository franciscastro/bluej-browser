<?php
$this->breadcrumbs=array(
	'Logs' => array('importSession/index', 'tags'=>$_GET['tags']),
	'General Summary',
);

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

<h1>General Summary</h1>
<div id="general-summary">
<?php $this->renderPartial('_summary', array(
	'topEqData'=>$topEqData,
	'topErrorsData'=>$topErrorsData,
	'timeDeltaData'=>$timeDeltaData,
	'topConfusedData'=>$topConfusedData,
));
?>
</div>
