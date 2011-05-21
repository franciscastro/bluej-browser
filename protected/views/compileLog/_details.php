<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'deltaSequenceNumber:raw:Id',
		'timestamp:datetime',
		'fileName',
		array(
			'label'=>'Compile Successful',
			'type'=>'raw',
			'value'=>$model->compileSuccessful ? 'Yes' : 'No',
		),
		'messageText',
		'messageLineNumber',
		'messageColumnNumber',
	),
)); ?>

<?php
$this->widget('zii.widgets.jui.CJuiAccordion', array(
		'panels'=>array(
				'More Details'=>$this->renderPartial('_moreDetails', array('model'=>$model), true),
		),
		// additional javascript options for the accordion plugin
		'options'=>array(
				'animated'=>'bounceslide',
				'collapsible'=>'true',
				'active'=>'false',
		),
));
?>


<?php  ?>

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
				if($model->messageColumnNumber > 0) {
					$arrow = "\n";
					for($j = 0; $j < $model->messageColumnNumber; $j++) {
						$arrow .= ' ';
					}
					$arrow .= '^</pre>';
					$toPrint = substr_replace($toPrint, $arrow, strpos($toPrint, '</pre>', $pc+1), 6);
				}
			}
			if(isset($diff[$i]) && $diff[$i] != '')
			{
				$class .= "diff ";
			}
			if($class != "") {
				$toPrint = substr_replace($toPrint, "<li class='$class'>", $pc, 4);
			}
		}
	echo $toPrint;
?>
