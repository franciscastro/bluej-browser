<?php
function make_link($name, $route) {
	if(isset($_GET['id'])) {
		return CHtml::link($name, array($route, 'id'=>$_GET['id']));
	}
	else if(isset($_GET['tags'])) {
		return CHtml::link($name, array($route, 'tags'=>$_GET['tags']));
	}
	else {
		return CHtml::link($name, array($route));
	}
}
?>
<table class='report'>
	<tr>
		<td>
			<h2>EQ ( <?php echo make_link('details', 'eq'); ?> )</h2>
			<table id="eq-summary">
				<tr>
					<th>Student</th>
					<th>EQ</th>
				</tr>
				<?php if(empty($topEqData)) { ?>
					<tr><td colspan=2>Nothing to list</td></tr>
				<?php } else foreach($topEqData as $datum): ?>
					<tr>
					<?php if(isset($_GET['id'])): ?>
						<td><?php echo CHtml::link($datum['name'], array('compileSession/view', 'id'=>$datum['compileSessionId'])); ?></td>
					<?php else: ?>
						<td><?php echo CHtml::link($datum['name'], array('user/view', 'id'=>$datum['userId'])); ?></td>
					<?php endif; ?>
						<td><?php echo $datum['eq'] ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</td>
		<td>
			<h2>Confused ( <?php echo make_link('details', 'confusion'); ?> )</h2>
			<table id="confusion-summary">
				<tr>
					<th>Student</th>
					<th class="right">Confusion Rate</th>
					<th class="right">Clips</th>
				</tr>
				<?php if(empty($topConfusedData)) { ?>
					<tr><td colspan=3>Nothing to list</td></tr>
				<?php } else foreach($topConfusedData as $datum): ?>
					<tr>
					<?php if(isset($_GET['id'])): ?>
						<td><?php echo CHtml::link($datum['name'], array('compileSession/view', 'id'=>$datum['compileSessionId'])); ?></td>
					<?php else: ?>
						<td><?php echo CHtml::link($datum['name'], array('user/view', 'id'=>$datum['userId'])); ?></td>
					<?php endif; ?>
						<td class='right'><?php printf("%.2f%%", $datum['confusion'] * 100) ?></td>
						<td class='right'><?php echo $datum['clips'] ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<h2>Errors (
				<?php echo make_link('by class', 'errorClass'); ?> |
				<?php echo make_link('details', 'error'); ?>
			) </h2>
			<table id="error-summary">
				<tr>
					<th>Error</th>
					<th>Count</th>
				</tr>
				<?php if(empty($topErrorsData)) { ?>
					<tr><td colspan=2>Nothing to list</td></tr>
				<?php } else foreach($topErrorsData as $datum): ?>
					<tr>
						<td><?php echo $datum['messageText'] == '' ? '&lt;no error&gt;' : $datum['messageText'] ?></td>
						<td><?php echo $datum['count'] ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</td>
		<td>
			<h2>Times ( <?php echo make_link('details', 'timeDelta'); ?> )</h2>
			<table id="time-summary">
				<tr>
					<th colspan=3>Time Interval</th>
					<th>Count</th>
				</tr>
				<?php foreach($timeDeltaData as $n=>$datum): ?>
					<tr>
						<?php if($n == 6): ?>
						<td class="center" colspan=3>Beyond</td>
						<?php else: ?>
						<td class="right"><?php echo $datum['from'] ?></td>
						<td class="center">-</td>
						<td><?php echo $datum['to'] ?></td>
						<?php endif; ?>
						<td><?php echo $datum['count'] ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</td>
	</tr>
</table>
