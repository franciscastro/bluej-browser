<?php
$this->pageTitle=Yii::app()->name . ' - Help - About';
$this->renderPartial('pages/_navigation');
?>

<h1>Compilation Logs</h1>
<p>
Compilation logs are received for every compilation a student
makes. Among the data collected are:
<ul>
<li>Source code</li>
<li>
First error message
<ul>
<li>Line number</li>
<li>Column number (for patched versions of BlueJ)</li>
</ul>
</li>
<li>Time of compilation</li>
</ul>
</p>
<p>
<p>
Viewing individual compile logs gives a view of the source code.
The error line will be highlighted and the column indicated, where
applicable. Compile logs may also be compared against each other,
in which case, the different lines are also highlighted.
</p>
<p>
Note that you may also change the sorting of the compilation logs
and these affect the viewing and comparing of the source code.
</p>
<p>
Entries may also be deleted. These will then show up in a separate
table, and then EQ and Confusion rate will be recalculated. You
may also move entries between logs if necessary, but this requires
manually inputting the id number of the target log. Again, this
forces a recalculation of EQ and Confusion rate.
</p>

<h1>Invocation Logs</h1>
<p>
Invocation logs refer to BlueJ's GUI for object instantiation
and method calling. Every time a student makes an object or
calls a method, these are logged.
</p>