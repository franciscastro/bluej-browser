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
	'success'=>'replotStuff',
));

$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->assetManager->publish('protected/vendors/highcharts/js/highcharts.js'));
$cs->registerScript('plotStuffHead', <<<EOF
Highcharts.setOptions({
	global: {
		useUTC: false,
		enableMouseTracking: false,
	}
});
var charts = Array();

function plotStuff(data, status, xhr) {
	plotChart('errorChart', 'Count', data.errors);
	plotChart('eqChart', 'EQ', data.eq);
	plotChart('timeDeltaChart', 'Frequency', data.timeDeltas);
	plotChart('confusionChart', 'Confusion Rate', data.confusion);
	if(typeof(data.histogram) != 'undefined') plotHistogram('histogramChart', data.histogram);
}

function replotStuff(data, status, xhr) {
	replotChart('errorChart', data.errors);
	replotChart('eqChart', data.eq);
	replotChart('timeDeltaChart', data.timeDeltas);
	replotChart('confusionChart', data.confusion);
	replotHistogram('histogramChart', data.histogram);
	setTimeout('autorefresh()', 5000);
}

function plotChart(divId, name, data) {
	var options = {
		chart: {
			renderTo: divId,
			defaultSeriesType: 'bar',
			inverted: true,
		},
		title: {
			text: null
		},
		xAxis: {
			categories: data.x,
			labels: {
				align: 'right',
				style: {
					fontSize: '10px',
					lineHeight: '10px',
					width: '200px'
				}
			},
		},
		yAxis: {
			maxPadding: 0.1,
			min: 0,
			title: {
				text: null
			}
		},
		legend: {
			enabled: false
		},
		credits: {
			enabled: false
		},
		tooltip: {
			enabled: false
		},
		plotOptions: {
			column: {
				borderWidth:0.5,
				borderColor: 'black',
				pointWidth:40
			},
		},
		series: [{
			name: name,
			data: data.y,
			dataLabels: {
				enabled: true,
				color: '#000000',
				align: 'left'
			}
		}]
	}
	if(divId == 'eqChart' || divId == 'confusionChart') {
		options.series[0].dataLabels.formatter = function() {
			return Highcharts.numberFormat(this.y, 5);
		}
		options.xAxis.labels.formatter = function() {
			return '<a href="'+this.value.url+'">'+this.value.name+'</a>';
		}
	}
	charts[divId] = new Highcharts.Chart(options);
}

function plotHistogram(divId, data) {
	charts[divId] = new Highcharts.Chart({
		chart: {
			renderTo: divId,
		},
		title: {
			text: null
		},
		xAxis: {
			type: 'datetime',
		},
		yAxis: {
			maxPadding: 0.1,
			min: 0,
			title: {
				text: null
			}
		},
		legend: {
			enabled: false
		},
		credits: {
			enabled: false
		},
		plotOptions: {
			column: {
				borderWidth:0.5,
				borderColor: 'black',
				pointWidth:40
			},
		},
		series: [{
			name: 'Compile Frequency',
			data: data
		}]
	});
}

function replotHistogram(divId, data) {
	charts[divId].series[0].setData(data);
}

function replotChart(divId, data) {
	charts[divId].xAxis[0].setCategories(data.x, false);
	charts[divId].series[0].setData(data.y);
}

function autorefresh() {
	$ajax
}
EOF
, CClientScript::POS_HEAD);
$cs->registerScript('plotStuffInitial', 'plotStuff('.CJavaScript::jsonEncode($data).', null, null)');
if($isSingle) $cs->registerScript('plotStuff', 'setTimeout("autorefresh()", 5000);');
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
			<div id="errorChart" style="height:300px;width:420px; "></div>
		</td>
		<td>
			<h2>EQ ( <?php echo make_link('details', 'eq'); ?> )</h2>
			<div id="eqChart" style="height:300px;width:420px; "></div>
		</td>
	</tr>
	<tr>
		<td>
			<h2>Time between Compiles ( <?php echo make_link('details', 'timeDelta'); ?> )</h2>
			<div id="timeDeltaChart" style="height:300px;width:420px; "></div>
		</td>
		<td>
			<h2>Confusion Rate ( <?php echo make_link('details', 'confusion'); ?> )</h2>
			<div id="confusionChart" style="height:300px;width:420px; "></div>
		</td>
	</tr>
	<?php if($isSingle): ?>
	<tr><td colspan=2>
	<h2>Compile Frequency Timeline</h2>
	<div id="histogramChart" style="height:300px;"></div>
	</td></tr>
	<?php endif; ?>
</table>
</div>
