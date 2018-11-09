<div class="centercontent">
	
	<div class="pageheader notab">
		<h1 class="pagetitle"><?php echo $headertitle; ?></h1>
		<span class="pagedesc">&nbsp;</span>
		
	</div><!--pageheader-->
		
		
	<div id="contentwrapper" class="contentwrapper">
		<div class="contenttitle2">
			<h3>Overall</h3>
		</div>
		<div>Average days since projects last updated: <h2><?= $averageRecency ?></h2></div>
		
		<div class="contenttitle2">
			<h3>Segments</h3>
		</div>
		<table class="stdtable">
			<thead>
				<tr>
					<th>Time since last update</th>
					<th>Number of projects</th>
					<th>Percentage</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($recencyBuckets as $recencyBucket) { ?>
					<tr>
						<td><?= $recencyBucket->agegrouping ?></td>
						<td><?= $recencyBucket->count ?></td>
						<td><?= $recencyBucket->percentage ?></td>
					</tr>

				<?php } ?>
			</tbody>
		</table>
		
		
	</div><!--contentwrapper-->
			
</div>