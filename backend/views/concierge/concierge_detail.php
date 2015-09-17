
<div class="centercontent">

	<div class="pageheader notab">

		<h1 class="pagetitle">Concierge Questions</h1>

		<span class="pagedesc">&nbsp;</span>

	</div>

	<div id="contentwrapper" class="contentwrapper">

		<div class="one_half">

			<div class="contenttitle2">
				<h3>Concierge Question Details</h3>
			</div>


			<table cellpadding="0" cellspacing="0" class="table invoicefor">
				<tbody>
					<?php if( $question->archive == 1): ?>
					<tr>
					<td colspan="2" style="text-align:center"><strong>Archived</strong></td>
					</tr>
					<?php endif; ?>

					<tr>
					<td width="30%">From:</td>
					<td width="70%"><strong><?php echo $question->name;?></strong></td>
					</tr>
					<tr>
					<td>Email:</td>
					<td><a href="mailto:<?php echo $question->email;?>"><?php echo $question->email;?></a></td>
					</tr>
					<tr>
					<td>Submitted:</td>
					<td><?php echo date('F j \a\t g:i A',$question->date);?></td>
					</tr>
					<tr>
					<td>Question:</td>
					<td><?php echo $question->message;?></td>
					</tr>
				</tbody>
			</table>
	
			<?php if( $question->archive == 0): ?>
			<p><a href="/admin.php/concierge/question/<?php echo $question->id;?>/archive" class="stdbtn">Archive Question</a></p>
			<?php else: ?>
			<p><a href="/admin.php/concierge/question/<?php echo $question->id;?>/unarchive" class="stdbtn">Un-Archive Question</a></p>
			<?php endif; ?>
		</div>

	</div><!--contentwrapper-->

</div>