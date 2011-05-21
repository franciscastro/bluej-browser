<?php
$this->pageTitle=Yii::app()->name . ' - Help - About';
$this->renderPartial('pages/_navigation');
?>

<h1>Reports</h1>
<p>
You can generate reports for logs by either going to the log
and clicking <b>View Report</b> or by <b>Generate Report</b>
from the Logs page.
</p>
<h4>Error Quotient (EQ)</h4>
<p>
EQ is a measure which attempts to show how good or bad
a student is coping with compilation errors. A high value
means the student is not doing well, while a low value means
the student is doing ok. It is also possible that there is
insufficient data to calculate EQ in which case, N/A will
be displayed.
</p>
<p>
This measure is the work of Matt Jadud.
</p>
<h4>Errors</h4>
<p>
This is a list of errors and how many times they have occurred
for the log. There are two different displays for this, one
is the normal error display, which aggregates errors as is. The
other one aggregates errors based on their error class. For example,
<tt>cannot find symbol - variable</tt> errors typically specify the variable
name missing. In the normal error display, these would each count
as different errors but in the error class display, these would all be
just <tt>unknown-variable</tt>.
</p>
<p>
The error display in the summary page displays the one based on error class.
</p>
</p>
<h4>Time Deltas</h4>
<p>
Time deltas are actually a summary of how many times there
were compilations that happened within 0-20 seconds of each
other, 20-40 seconds of each other, etc.
</p>
<p>
The interval can be customized in the details page.
</p>
<h4>Confusion</h4>
<p>
This is a student's rate of confusion. This works
by dividing a student's compilation log entries into groups
of 8 which have the same filename. Each clip of 8 is then labeled
as confused or not confused. The percentage of confused clips
is then the confusion rate.
</p>
<p>
It is logant to note that the confusion rate is heavily reliant
on the number of clips available for calculation. As such, they
are displayed alongside the confusion rate.
</p>
<p>
The automated labeling is the work of Diane Marie Lee.
</p>