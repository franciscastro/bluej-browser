<?php
if(isset($_GET['id'])) {
	$this->breadcrumbs=array(
		'Logs' => array('logSession/index'),
		'Log Session #' . $_GET['id'] => array('logSession/view', 'id'=>$_GET['id']),
		'Summary'
	);
}
else if(isset($_GET['tags'])){
	$this->breadcrumbs=array(
		'Logs' => array('logSession/index', 'tags'=>$_GET['tags']),
		'General Summary'
	);
}
else {
	$this->breadcrumbs=array(
		'Logs' => array('logSession/index'),
		'General Summary'
	);
}
function make_link($name, $route) {
	if(isset($_GET['id'])) {
		return CHtml::link($name, array($route, 'id'=>$_GET['id']));
	}
	else if(isset($_GET['tags'])) {
		return CHtml::link($name, array($route, 'tags'=>$_GET['tags']));
	}
	else {
		return CHtml::link($name, array($route));
	}
}

$ajax = CHtml::ajax(array(
	'dataType'=>'json',
	'success'=>'plotStuff',
));

$cs = Yii::app()->clientScript;
$cs->registerScriptFile('js/jqplot/jquery.jqplot.min.js');
$cs->registerCSSFile('js/jqplot/jquery.jqplot.min.css');
$cs->registerScriptFile('js/jqplot/plugins/jqplot.barRenderer.min.js');
$cs->registerScriptFile('js/jqplot/plugins/jqplot.categoryAxisRenderer.min.js');
$cs->registerScriptFile('js/jqplot/plugins/jqplot.dateAxisRenderer.min.js');
$cs->registerScriptFile('js/jqplot/plugins/jqplot.pointLabels.min.js');
$cs->registerScript('plotStuffHead', "
function plot(divId, data) {
	if(divId == 'eqChart' || divId == 'confusionChart') {
		xaxis = {min: 0, max: 1.2}
		pointLabels = { show: true, location: 'e', edgeTolerance: 0, formatString: '%.3f' }
	}
	else {
		xaxis = {padMin: 0}
		pointLabels = { show: true, location: 'e', edgeTolerance: 0 }
	}
	$('#'+divId).empty();
	jqplot = $.jqplot(divId,  [data], {
		seriesDefaults:{
			renderer:$.jqplot.BarRenderer,
			pointLabels: pointLabels,
			shadowAngle: 135,
			rendererOptions:{
				barDirection: 'horizontal',
				barMargin: 3
			}
		},
		axes: {
			xaxis: xaxis,
			yaxis: {
				renderer: $.jqplot.CategoryAxisRenderer
			},
		}
	});
}

function plotHistogram(data) {
	if(typeof(data) == 'undefined') return;
	$('#histogramChart').empty();
	$.jqplot('histogramChart', [data], {
		axes: {
			xaxis: {
				renderer: $.jqplot.DateAxisRenderer,
				tickOptions: {
					formatString: '%T'
				}
			},
			yaxis: {
				padMin: 0
			},
		}
	});
}

function plotStuff(data, status, xhr) {
	plot('errorChart', data.errors);
	plot('eqChart', data.eq);
	plot('timeDeltaChart', data.timeDeltas);
	plot('confusionChart', data.confusion);
	plotHistogram(data.histogram);
	$('.jqplot-yaxis-tick').css('z-index', 1);
}

function autorefresh() {
	$ajax
	setTimeout('autorefresh()', 10000);
}
", CClientScript::POS_HEAD);
$cs->registerScript('plotStuffInitial', 'plotStuff('.CJavaScript::jsonEncode($data).', null, null)');
if($isSingle) $cs->registerScript('plotStuff', 'autorefresh()');
?>


<h1><?php echo $isSingle ? "Summary of Log Session #" . $_GET['id'] : "General Summary" ?></h1>
<div id="general-summary">
<table class='report'>
	<tr>
		<td>
			<h2>Errors (
				<?php echo make_link('by class', 'errorClass'); ?> |
				<?php echo make_link('details', 'error'); ?>
			) </h2>
			<div id="errorChart" style="height:300px;width:400px; "></div>
		</td>
		<td>
			<h2>EQ ( <?php echo make_link('details', 'eq'); ?> )</h2>
			<div id="eqChart" style="height:300px;width:400px; "></div>
		</td>
	</tr>
	<tr>
		<td>
			<h2>Time between Compiles ( <?php echo make_link('details', 'timeDelta'); ?> )</h2>
			<div id="timeDeltaChart" style="height:300px;width:400px; "></div>
		</td>
		<td>
			<h2>Confusion Rate ( <?php echo make_link('details', 'confusion'); ?> )</h2>
			<div id="confusionChart" style="height:300px;width:400px; "></div>
		</td>
	</tr>
	<?php if($isSingle): ?>
	<tr><td colspan=2>
	<h2>Compile Frequency Timeline</h2>
	<div id="histogramChart"></div>
	</td></tr>
	<?php endif; ?>
</table>
</div>
