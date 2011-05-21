<?php
$this->pageTitle=Yii::app()->name . ' - Help - Tags';
$this->renderPartial('pages/_navigation');
?>
<h1>Tags</h1>
<p>
To organize the log logs, there is a tagging mechanism.
One or more tags are assigned to a log. These can then be used
to filter logs via the search in the <b>Logs</b> page. It is
also possible to search with multiple tags. In this case, a
log with all the specified tags will be the only ones to show up.
</p>
<p>
Also, searching in the <b>Logs</b> page also serves to limit the
scope of log exporting and report generation. Only those logs
that have the tags specified will be exported or be included
in the report.
</p>
<p>
Tags may also be merged if necessary by the administrator.
</p>
<h4>Classes</h4>
<p>
Classes are like bunches of tags corresponding to the year,
course and section. These are used to limit
what tags a teacher may use, as well as for simplifying the
interface. Teachers may only view logs which are tagged with
their section, and tag new logs with only their section.
</p>
<p>
They also serve to identify students. When a live log
is ongoing, only the computer name is received from the lab
computers. As many students may share computers in a single lab,
the class option in the log log serves to identify students
in addition to the computer number.
</p>