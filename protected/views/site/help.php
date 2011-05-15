<?php $this->pageTitle=Yii::app()->name; ?>

<h1>Help</h1>

<h2>Logs/Imports</h3>
<p>
A log/import usually corresponds to 1 lab session.
It contains all the logs collected from all students
in a particular class period. There are 2 ways to make
lab sessions: uploading log files, collecting logs live.
</p>
<h4>Uploading log files</h4>
<p>
Old log files from the previous data collection server
may be put in a zip file and uploaded here. This can be done
from the Logs page. The logs will be tagged automatically
based on the folder hierarchy inside the zip file.
</p>
<h4>Collecting logs live</h4>
<p>
You may opt to collect logs while a class is going on.
This can be created from the logs page. Just click the
"Start Live Session" link.
</p>

<h2>Tagging</h2>
<p>
To organize the log files, there is a tagging mechanism.
One or more tags are assigned to a log. These can then be
used to filter logs with that tag. It is also possible to
search with multiple tags. In this case, a log with all
the specified tags will be the only ones to show up.
</p>
<h4>Sections</h4>
<p>
Sections are like bunches of tags corresponding to the year,
course and section. These are used to limit
what tags a teacher may use, as well as for simplifying the
interface. Teachers may only view logs which are tagged with
their section, and tag new logs with only their section.
</p>

<h2>Reports</h2>
<p>
You can generate reports for logs by either going to the log
and clicking "View Report" or going to the Reports page and
typing the tags you want reports on. The tagging filter works
exactly the same as in the Logs page.
</p>
<h4>Error Quotient (EQ)</h4>
<p>
EQ is one of the reports that are generated. It is a measure
created by Matt Jadud which attempts to show how good or bad
a student is coping with compilation errors. A high value
means the student is not doing well, while a low value means
the student is doing ok. It is also possible that there is
insufficient data to calculate EQ in which case, N/A will
be displayed.
</p>
<h4>Errors</h4>
<p>
Another report generated is the list of errors and how many
times they have occurred for the session.
</p>
<h4>Time Deltas</h4>
<p>
Time deltas are actually a summary of how many times there
were compilations that happened within 0-20 seconds of each
other, 20-40 seconds of each other, etc.
</p>
<h4>Confusion</h4>
<p>
This is how confused the person is.
</p>
<h2>Users</h2>
<p>
There are 4 kinds of users, Adminstrators, Researchers,
Teachers, and Students. As of now, Students cannot do
anything. Teachers may create and view logs of their own
sections. Researchers may view anything. Administrators
may create new users as needed.
</p>