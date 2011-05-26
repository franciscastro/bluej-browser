<?php
$this->pageTitle=Yii::app()->name . ' - Help - About';
$this->renderPartial('pages/_navigation');
?>

<h1>Timeline</h1>
<p>
Viewing an individual log displays a timeline of the logged information.
Compile logs are represented by a check in a green circle or an 'x' in
a red circle for successful compiles and compiles with errors, respectively.
Invocation logs are represented by a blue circle with a play symbol.
</p>
<p>
Using the mouse wheel to scroll zooms in the timeline in case there are
many records. The bottom timeline shows a broader overview of the timeline.
</p>