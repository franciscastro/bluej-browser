<?php
$logSessionId = $model->logSessionId;
$this->breadcrumbs=array(
	'Logs'=>array('logSession/index'),
	'Log Session #'.$logSessionId=>array('logSession/view', 'id'=>$logSessionId),
	'Log #'.$model->id,
);

$cs = Yii::app()->clientScript;
$cs->registerScriptFile('js/simile-ajax/simile-ajax-api.js?bundle=true');
$cs->registerScriptFile('js/timeline/timeline-api.js?bundle=true');
$cs->registerCSSFile('js/timeline/timeline-bundle.css');
$timeline = $model->getTimeline();
$data = CJavaScript::jsonEncode($timeline);
$date = $timeline['events'][0]['start'];
$timezone = date('Z')/3600;
if($timezone > 0) $timezone = '+'.$timezone;
$cs->registerScript('timeline', <<<QQQ
	var event_data = $data;
	var eventSource = new Timeline.DefaultEventSource();
	Timeline.getDefaultTheme().mouseWheel = "zoom";
	var bandInfos = [
		Timeline.createBandInfo({
			eventSource:    eventSource,
			date:           "$date",
			timeZone:       "$timezone",
			width:          "80%",
			intervalUnit:   Timeline.DateTime.MINUTE,
			intervalPixels: 200,
			zoomIndex:      2,
			zoomSteps:      new Array(
				{pixelsPerInterval:  800,  unit: Timeline.DateTime.MINUTE},
				{pixelsPerInterval:  400,  unit: Timeline.DateTime.MINUTE},
				{pixelsPerInterval:  200,  unit: Timeline.DateTime.MINUTE},
				{pixelsPerInterval:  100,  unit: Timeline.DateTime.MINUTE}
			)
		}),
		Timeline.createBandInfo({
			eventSource:    eventSource,
			date:           "$date",
			timeZone:       "$timezone",
			overview:       true,
			width:          "20%",
			intervalUnit:   Timeline.DateTime.HOUR,
			intervalPixels: 2000
		})
	];
	bandInfos[1].syncWith = 0;
	bandInfos[1].highlight = true;
	Timeline.create(document.getElementById("timeline"), bandInfos, Timeline.HORIZONTAL);
	eventSource.loadJSON(event_data, '');
QQQ
, CClientScript::POS_LOAD);
?>
<h1>Log #<?php echo $model->id ?></h1>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'label'=>'Name',
			'type'=>'raw',
			'value'=>CHtml::link($model->user->name, array('user/view', 'id'=>$model->userId)),
		),
		'date:date'
	),
)); ?>
<?php
$infoModel = null;
if($model->compileLog != null) $infoModel = $model->compileLog;
if($model->invocationLog != null) $infoModel = $model->invocationLog;
if($infoModel != null)
$this->widget('zii.widgets.jui.CJuiAccordion', array(
		'panels'=>array(
				'More Details'=>$this->renderPartial('_moreInformation', array('model'=>$infoModel), true),
		),
		// additional javascript options for the accordion plugin
		'options'=>array(
				'animated'=>'bounceslide',
				'collapsible'=>'true',
				'active'=>'false',
		),
));
?>
<br>
<h3>Timeline</h3>
<div id="timeline"></div>

<?php if($model->compileLog != null): ?>
<h3>Compile Log Details</h3>
<?php $this->renderPartial('../compileLog/_view', array('model'=>$model->compileLog)) ?>
<?php endif; ?>