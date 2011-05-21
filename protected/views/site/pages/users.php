<?php
$this->pageTitle=Yii::app()->name . ' - Help - About';
$this->renderPartial('pages/_navigation');
?>

<h1>Users</h1>
<h4>Administrators</h4>
<p>Administrators have full access to the application. They may also create new users as needed.</p>

<h4>Researchers</h4>
<p>Researchers are allowed to view and collect logs.</p>

<h4>Teachers</h4>
<p>Teachers may create and view logs of their own sections.</p>

<h4>Students</h4>
<p>Students may view their own data. These are also automatically generated while logs are being collected but no username or password is assigned to them.</p>

<p>
Note: while it is possible to delete users via the interface, it is not advised because it may break relations in the database. A
better option would be to just remove the user's username which would disallow them from logging in.
</p>