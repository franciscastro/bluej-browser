<?php
$this->breadcrumbs=array(
	'Reports',
);

?>

<h1>Report Generator</h1>

<p>
Here, you can generate reports by tags. It will summarize all data that have those tags.
</p>

<?php $this->renderPartial('../tag/_search'); ?>
