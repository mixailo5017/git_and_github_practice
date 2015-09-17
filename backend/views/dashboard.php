<div class="centercontent">

	<div class="pageheader">
		<h1 class="pagetitle">Dashboard</h1>
<!--		<span class="pagedesc">Welcome to VIP Admin Panel</span>-->
       		<span class="pagedesc">&nbsp;</span>

		<ul class="hornav">
			<li class="current"><a href="#updates">Updates</a></li>
			<li><a href="#activities">Activities</a></li>
		</ul>
	</div><!--pageheader-->

	<div id="contentwrapper" class="contentwrapper">

		<div id="updates" class="subcontent">

				<div class="two_third dashboard_left">

					<ul class="shortcuts">
						<li><a href="" class="settings"><span>Settings</span></a></li>
						<li><a href="/admin.php/members" class="users"><span>Members</span></a></li>
						<li><a href="" class="analytics"><span>Analytics</span></a></li>
					</ul>

					<br clear="all" />

					<div class="contenttitle2">
						<h3>Online Users</h3>
					</div>


					<ul class="listfile">
					<?php
						$onlineusers = get_onlineuser();

						if(count($onlineusers)>0):
							foreach($onlineusers as $key=>$online):


								$img = expert_image( $online["userphoto"], 90, array('save_type'=>'jpg') );
								//echo USER_IMAGE_PATH . $online["userphoto"];

					?>
								<li class="">
									<a href="/admin.php/myaccount/<?php echo $online['uid'];?>" class="image" style="float:left;">
									<span class="img" style="float:left;">
										<img src="<?php echo $img; ?>" alt="<?php echo fullname($online);?>" style="float:left;" />
										</span>
										<span class="filename" style="float:left;clear:both;"><?php echo fullname($online);?></span></a>
								</li>
					<?php
							endforeach;
						endif;
					?>
					</ul>

				</div><!--two_third dashboard_left -->


				<div class="one_third last dashboard_right">

					<div class="widgetbox">
						<div class="title"><h3>Newly Registered User (limit to 10 users)</h3></div>
						<div class="widgetoptions">
							<div class="right"><a href="/admin.php/members">View All Users</a></div>
							<a href="/admin.php/members/new_member">Add User</a>
						</div>
						<div class="widgetcontent userlistwidget nopadding">
							<ul>
								<?php
								if($totalmembers > 0)
								{
									foreach($data as $user)
									{
								?>
									<li>
										<div class="info">
										<div style="float:left;width:60px;">
										<?php // expert_image($img = '', $size = false, $options = array() )
											$img = expert_image( $user["userphoto"], 39);
										?>
										<img src="<?php echo $img; ?>" alt="<?php echo fullname($user); ?>" style="float:left" />
										</div>
											<a href="myaccount/<?php echo $user["uid"]; ?>"><?php echo fullname($user); ?></a> &nbsp;&nbsp;|&nbsp;&nbsp;
											<?php echo $user["email"]; ?> <?php echo br(); ?> <?php echo DateDiffernece(date("Y-m-d H:i:s"),$user["registerdate"]); ?> ago
										</div><!--info-->
									</li>
								<?php
									}
								}
								else
								{
								?>
								<li>
									<div class="info">
										No User(s) found to display
									</div><!--info-->
								</li>
								<?php
								}
								?>

							</ul>
							<a class="more" href="/admin.php/members">View More Users</a>
						</div><!--widgetcontent-->
					</div>

				</div><!--one_third last-->

		</div><!-- #updates -->

		<div id="activities" class="subcontent" style="display: none;">

			<div class="one_half">
				<div class="widgetbox">
					<div class="title"><h3>Recently Updated Profiles</h3></div>

					<div class="widgetcontent userlistwidget nopadding">
						<ul>
						<?php if( $member_updates): foreach ( $member_updates as $member ):

							$img = expert_image($member->userphoto, 39);
						?>
							<li>
								<div class="avatar"><img src="<?php echo $img; ?>" alt="" /></div>
								<div class="info">
									<a href="/admin.php/myaccount/<?php echo $member->uid;?>"><?php echo $member->name;?></a> <br />
									<?php echo $member->title;?> <br /> <?php echo $member->timeago;?> ago
								</div><!--info-->
							</li>
						<?php endforeach; else: ?>
							<li>No Results</li>
						<?php endif; ?>

						</ul>
					</div><!--widgetcontent-->
				</div><!--widgetbox-->
			</div>

			<div class="one_half last">
				<div class="widgetbox">

					<div class="title"><h3>New Users</h3></div>
					<div class="widgetcontent userlistwidget nopadding">
						<ul>
						<?php
							if($totalmembers > 0):
								foreach($data as $user):
									$img = expert_image( $user["userphoto"], 39 );
						?>

							<li>
								<div class="avatar"><img src="<?php echo $img; ?>" alt="" /></div>
								<div class="info">
									<a href="/admin.php/myaccount/<?php echo $user['uid'];?>"><?php echo fullname($user); ?></a> <br />
									<?php echo $user['firstname'];?> <br /> <?php echo DateDiffernece(date("Y-m-d H:i:s"),$user["registerdate"]); ?> ago
								</div><!--info-->
							</li>
						<?php
								endforeach;
							endif;
						?>
						</ul>
					</div><!--widgetcontent-->
				</div><!--widgetbox-->

			</div>

			<div style="clear:both"></div>

			<div class="two_third">
				<div class="contenttitle2">
					<h3>Recently Updated Projects</h3>
				</div>

				<table cellpadding="0" cellspacing="0" border="0" class="stdtable">
					<colgroup>
						<col class="con0">
						<col class="con1">
						<col class="con0">
					</colgroup>
					<thead>
						<tr>
							<th class="head0">Project</th>
							<th class="head1">Fields Updated</th>
							<th class="head0">Updated</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th class="head0">Project</th>
							<th class="head1">Fields Updated</th>
							<th class="head0">Updated</th>
						</tr>
					</tfoot>
					<tbody>
					<?php if( $project_updates): foreach( $project_updates as $project ): ?>
						<tr>
							<td><a href="/admin.php/projects/edit/<?php echo $project->slug; ?>"><?php echo $project->projectname; ?></a></td>
							<td><?php echo $project->fields; ?></td>
							<td class="center"><?php echo date('F j, Y',$project->updated); ?></td>
						</tr>
					<?php endforeach;  else: ?>
						<tr><td colspan="3">No Results</td></tr>
					<?php endif; ?>
					</tbody>
				</table>
			</div>

			<div class="one_third last">
				<div class="widgetbox">

					<div class="title"><h3>New Projects</h3></div>
					<div class="widgetcontent userlistwidget nopadding">
						<ul>
						<?php
							if($new_projects):
								foreach($new_projects as $project):
									$img = project_image( $project->projectphoto, 39 );
						?>

							<li>
								<div class="avatar"><img src="<?php echo $img; ?>" alt="" /></div>
								<div class="info">
									<a href="/admin.php/projects/edit/<?php echo $project->slug; ?>"><?php echo $project->projectname; ?></a> <br />
									<?php echo DateDiffernece(date("Y-m-d H:i:s"),date("Y-m-d H:i:s",$project->entry_date),' ago'); ?>
								</div><!--info-->
							</li>
						<?php
								endforeach;
							endif;
						?>
						</ul>
					</div><!--widgetcontent-->
				</div><!--widgetbox-->
			</div>

		</div><!-- #activities -->

	</div><!--contentwrapper-->

	<br clear="all" />

</div><!-- centercontent -->
