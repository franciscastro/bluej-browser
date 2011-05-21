<?php
$this->pageTitle=Yii::app()->name . ' - Help - About';
$this->renderPartial('pages/_navigation');
?>

<h1>About</h1>

<p>The BlueJ Browser is an application used for collecting and viewing logs of students' interactions with the BlueJ IDE. Currently 2 kinds of logs are collected, compilation and invocation logs. Work has been primarily done only on compilation logs however, so this has more focus as of the moment.<p>

<p>Data collection is done via an extension installed in BlueJ that sends information for every compilation or invocation done. This information is received by this application and stores them in a central database.<p>