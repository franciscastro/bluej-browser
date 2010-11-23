<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'deltaSequenceNumber:raw:Id',
		'compileSessionId',
		'timestamp:datetime',
		'deltaStartTime',
		'deltaEndTime',
		'filePath',
		'fileName',
		'fileEncoding',
		array(
			'label'=>'Compile Successful',
			'type'=>'raw',
			'value'=>$model->compileSuccessful ? 'Yes' : 'No',
		),
		'messageType',
		'messageText',
		'messageLineNumber',
		'compilesPerFile',
		'totalCompiles',
	),
)); ?>

<?php
	$cthHighlighter = new CTextHighlighter;
	$cthHighlighter->language = 'JAVA';
	$cthHighlighter->showLineNumbers = 'true';
	$cthHighlighter->cssFile = Yii::app()->baseUrl . '/css/java-hl.css';
	$toPrint = $cthHighlighter->highlight($model->fileContents);
		$pc = 0;
		for($i = 0;; $i++) {
			$pc = strpos($toPrint, '<li>', $pc+1);
			if($pc === FALSE) break;
			$class = "";
			if($i == $model->messageLineNumber-1)
			{
				$class .= "error ";
			}
			if(isset($diff[$i]) && $diff[$i] != '')
			{
				$class .= "diff ";
			}
			$toPrint = substr_replace($toPrint, $class!='' ? "<li class='$class'>" : '<li>', $pc, 4);
		}      
	echo $toPrint;
?>
