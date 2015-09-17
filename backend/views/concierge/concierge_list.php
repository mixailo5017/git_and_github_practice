
<div class="centercontent">

	<div class="pageheader notab">

		<h1 class="pagetitle">Concierge Questions</h1>

		<span class="pagedesc">&nbsp;</span>

	</div>

	<div id="contentwrapper" class="contentwrapper">

		<div id="inbox" class="subcontent">

<!-- 			<div class="msghead">
				<ul class="msghead_menu">
					<li><a>Archive</a></li>
					<li class="marginleft10" style="border: 1px solid #ccc; border-radius: 2px; padding: 4px 10px 5px; font-weight: bold; box-shadow: 0 1px 0 #FFFFFF inset;"><label style=""><input type="checkbox"> Show Archived Messages</label></li>
					<li class="right"><a class="next"></a></li>
					<li class="right"><a class="prev prev_disabled"></a></li>
					<li class="right"><span class="pageinfo">1-10 of 2,139</span></li>
				</ul>
				<span class="clearall"></span>
			</div><!- -msghead- -> -->
			<div class="notibar" style="display:none">
				<a class="close"></a>
				<p></p>
			</div>
			
			<div class="tableoptions">
					<button class="deletebutton radius3" title="Archive Selected" name="dyntable_concierge_list" id="#/admin.php/concierge/archive_questions">Archive Selected</button> &nbsp;

					<label style="border: 1px solid #ccc; border-radius: 2px; padding: 4px 10px 5px; font-weight: bold; box-shadow: 0 1px 0 #FFFFFF inset;"><input id="show_archived" value="archived" type="checkbox"> Show Archived Messages</label>

<!-- 					Projects Sector:&nbsp;
					<select name="" id=""></select>

					&nbsp;&nbsp;
					Project Owner:&nbsp;&nbsp;

					<select name="" id=""></select>
 -->

			</div><!--tableoptions-->

			<table cellpadding="0" cellspacing="0" border="0" id="dyntable_concierge_list" class="stdtable mailinbox">
				<colgroup>
					<col class="con1" width="4%"/>
					<col class="con1" width="19%"/>
					<col class="con0" width="67%"/>
					<col class="con1" width="10%"/>
				</colgroup>
				<thead>
				<tr>
					<th width="20" class="head1 aligncenter"><input type="checkbox" name="checkall" class="checkall" /></th>
					<th class="head1">Sender</th>
					<th class="head0">Subject</th>
					<th class="head0">Date</th>
				</tr>
				</thead>
				<tfoot>
					<tr>
						<th class="head1 aligncenter"><input type="checkbox" name="checkall" class="checkall2" /></th>
						<th class="head1">Sender</th>
						<th class="head0">Subject</th>
						<th class="head0">Date</th>
					</tr>
				</tfoot>
				<tbody>
					<?php foreach($questions as $question): ?>
					<tr>
						<td class="aligncenter">
							<?php if( $question->archive == 0 ): ?>
							<input type="checkbox" name="select_" value="<?php echo $question->id ?>" />
							<?php endif; ?>
							<span style="display:none"><?php echo $question->archive == 1 ? 'archived' : 'unarchived'; ?></span>
							<span style="display:none"><?php echo $question->read == 1 ? 'read' : 'unread'; ?></span>
						</td>
						<td><?php echo $question->name ?></td>
						<td><a href="/admin.php/concierge/question/<?php echo $question->id; ?>" class="title"><?php echo word_limiter($question->message,9); ?></a></td>
						<td class="date"><?php echo date('M d',$question->date); ?></td>
					</tr>
					<?php endforeach; ?>
					<!-- <tr>
						<td class="aligncenter"><input type="checkbox" name="" /></td>
						<td>themepixels</td>
						<td><a href="/admin.php/dashboard/question" class="title">Ullamco laboris nisi ut aliquip ex ea commodo consequat. </a></td>
						<td class="date">June 30</td>
					</tr>
					<tr>
						<td class="aligncenter"><input type="checkbox" name="" /></td>
						<td>Puss in Boots</td>
						<td><a href="/admin.php/dashboard/question" class="title">Sed ut perspiciatis unde omnis iste natus error</a></td>
						<td class="date">June 28</td>
					</tr>
					<tr>
						<td class="aligncenter"><input type="checkbox" name="" /></td>
						<td>Humpty Dumpty</td>
						<td><a href="/admin.php/dashboard/question" class="title">Sit voluptatem accusantium doloremque laudantium</a></td>
						<td class="date">June 20</td>
					</tr>
					<tr>
						<td class="aligncenter"><input type="checkbox" name="" /></td>
						<td>themepixels</td>
						<td><a href="/admin.php/dashboard/question" class="title">Totam rem aperiam, eaque ipsa quae ab illo inventore</a></td>
						<td class="date">June 19</td>
					</tr>
					<tr>
						<td class="aligncenter"><input type="checkbox" name="" /></td>
						<td>Hiccup Haddock</td>
						<td><a href="/admin.php/dashboard/question" class="title">Ut enim ad minim veniam, quis nostrud exercitation</a></td>
						<td class="date">July 1</td>
					</tr>
					<tr>
						<td class="aligncenter"><input type="checkbox" name="" /></td>
						<td>themepixels</td>
						<td><a href="/admin.php/dashboard/question" class="title">Ullamco laboris nisi ut aliquip ex ea commodo consequat. </a></td>
						<td class="date">June 30</td>
					</tr>
					<tr>
						<td class="aligncenter"><input type="checkbox" name="" /></td>
						<td>Puss in Boots</td>
						<td><a href="/admin.php/dashboard/question" class="title">Sed ut perspiciatis unde omnis iste natus error</a></td>
						<td class="date">June 28</td>
					</tr>
					<tr>
						<td class="aligncenter"><input type="checkbox" name="" /></td>
						<td>Humpty Dumpty</td>
						<td><a href="/admin.php/dashboard/question" class="title">Sit voluptatem accusantium doloremque laudantium</a></td>
						<td class="date">June 20</td>
					</tr>
					<tr>
						<td class="aligncenter"><input type="checkbox" name="" /></td>
						<td>themepixels</td>
						<td><a href="/admin.php/dashboard/question" class="title">Totam rem aperiam, eaque ipsa quae ab illo inventore</a></td>
						<td class="date">June 19</td>
					</tr> -->

				</tbody>
			</table>

		</div>

		<div id="compose" class="subcontent" style="display: none">&nbsp;</div>

	</div><!--contentwrapper-->

</div><!--centercontent-->

