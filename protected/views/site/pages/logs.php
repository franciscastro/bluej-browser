<?php
$this->pageTitle=Yii::app()->name . ' - Help - Logs';
$this->renderPartial('pages/_navigation');
?>

<h1>Logs</h1>
<p>
A log session usually corresponds to 1 lab session.
It contains all the logs collected from all students
in a particular class period. There are 2 ways to make
log sessions: uploading log files, collecting logs live.
</p>
<h4>Uploading log files</h4>
<p>
Old log files from the previous data collection server
may be put in a zip file and uploaded here. This can be done
from the Logs page via <b>Upload Log Files</b>.
</p>
<h4>Collecting logs live</h4>
<p>
You may opt to collect logs while a class is going on.
This can be created from the logs page via
<b>Start Live Session</b> in the navigation menu. The
server will then proceed to collect any logs sent to it
while it is running. A log can then be stopped to
stop further collection of logs.
</p>
<p>
There is also an optional filter
which will collect only logs whose location begins with
the filter. This is useful when collecting logs from two
different labs at the same time, say F227 and F228. In
that instance, we only need to start two live logs
with one whose filter is F227, and the others as F228.
</p>
<p>
It is also important to note that whichever log session
created first takes precedence if ever there is a conflict.
If you first create a log session without a filter (and thus collects
all logs) and then another with a filter, only the first
one will ever collect logs if both run at the same time even if
the log would pass the filter of the second one.
</p>
<p>
Also, any logs that are sent to the server while no log
is running (or does not pass any filter) will be discarded.
</p>
<h4>Exporting logs</h4>
<p>
Logs may also be exported as collections of CSV files.
This is also done from the Logs page which exports all
current logs. Individual logs may also be exported by
clicking the save icon in its row in the table, or via
the navigation menu when viewing an individual log session.
</p>