<table class='report'>
	<tr>
		<td>
			<h2>EQ <?php echo CHtml::link('(details)', array('eq', 'tags'=>$_GET['tags'])); ?></h2>
			<table id="eq-summary">
				<tr>
					<th>Student</th>
					<th>EQ</th>
				</tr>
				<?php if(empty($topEqData)) { ?>
					<tr><td colspan=2>Nothing to list</td></tr>
				<?php } else foreach($topEqData as $datum): ?>
					<tr>
						<td><?php echo $datum['name'] ?></td>
						<td><?php echo $datum['eq'] ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</td>
		<td>
			<h2>Confused <?php echo CHtml::link('(details)', array('confusion', 'tags'=>$_GET['tags'])); ?></h2>
			<table id="confusion-summary">        
				<tr>
					<th>Student</th>
					<th>Confusion Rate</th>
				</tr>
				<?php if(empty($topConfusedData)) { ?>
					<tr><td colspan=2>Nothing to list</td></tr>
				<?php } else foreach($topConfusedData as $datum): ?>
					<tr>
						<td><?php echo $datum['name'] ?></td>
						<td><?php printf("%.2f", $datum['confusion'] * 100) ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<h2>Errors <?php echo CHtml::link('(details)', array('error', 'tags'=>$_GET['tags'])); ?></h2>
			<table id="error-summary">        
				<tr>
					<th>Error</th>
					<th>Count</th>
				</tr>
				<?php if(empty($topErrorsData)) { ?>
					<tr><td colspan=2>Nothing to list</td></tr>
				<?php } else foreach($topErrorsData as $datum): ?>
					<tr>
						<td><?php echo $datum['messageText'] ?></td>
						<td><?php echo $datum['count'] ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</td>
		<td>
			<h2>Times</h2>
			<table id="time-summary">
				<tr>
					<th colspan=3>Time Interval</th>
					<th>Count</th>
				</tr>
				<?php foreach($timeDeltaData as $n=>$datum): ?>
					<tr>
						<?php if($n == 6): ?>
						<td colspan=3>Above 120</td>
						<?php else: ?>
						<td class="right"><?php echo $datum['from'] ?></td>
						<td>-</td>
						<td><?php echo $datum['to'] ?></td>
						<?php endif; ?>
						<td><?php echo $datum['count'] ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</td>
	</tr>
</table>
