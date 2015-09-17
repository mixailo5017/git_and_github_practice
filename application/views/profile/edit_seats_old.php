<div id="content" class="clearfix">
<div id="col4">
		<ul id="profile_nav">
			<li><a href="/profile/account_settings">Profile Information</a></li>
			<!-- ExpertAdverts Start-->
			<?php if($usertype == '8')
			{?>
				<li class="here"><a href="/profile/edit_seats">Edit Seats</a></li>
				<li><a href="/profile/edit_case_studies">Edit Case Studies</a></li>
				<li><a href="javascript:void(0);">Store Purchase History</a></li>
				<li><a href="javascript:void(0);">License Information</a></li>
			<?php
			}
			?>
			<!-- ExpertAdverts End-->
			<li><a href="/profile/account_settings_email">Email &amp; Password</a></li>
		</ul>
	</div>
	
	<div id="col5">
		<a class="bolt" href="#">Why promote your experts?</a>
		<h1 class="col_top gradient">Have a seat, <?php echo $users['organization'];?>.!</h1>
		<div class="profile_links">
			<div id="form_submit">
				<a href="/expertise/<?php echo $users['uid'];?>" class="light_gray">View My Profile</a>
			</div>
		</div>
	
		
		<div class="inner">
			<div class="seat_portlets">
			<?php
				$totalassigned  = 0;
				$j = 0;
				$provided_seat = $users['numberofseat'];
				$totalassigned  = count($seats['pending'])+ count($seats['approved']);
				$availableseat = ($provided_seat - $totalassigned);
			
			?>
				<p class="callout"><strong>You have <?php echo $availableseat;?> spots available!</strong> 
				Invite, remove, or view the experts in your organization. <a href="#">
				Get more seats!</a></p>

				<?php 
				if($totalassigned > 0)
				{
					if(count($seats['pending']) > 0)
					{
						for($j=0;$j<count($seats['pending']);$j++)
						{?>
							<div class="portlet">
								<div class="top">
									
									<a href="javascript:void(0);">
									<?php
									// Old Template
									?>
									</a>
									<h2><?php echo $seats['pending'][$j]['firstname'].' '.$seats['pending'][$j]['lastname']; ?></h2>
									<a href="javascript:void(0);"><?php echo $seats['pending'][$j]['email']; ?></a>
									<a href="javascript:void(0);" style="margin:10px 20px 5px 20px;float:left; color:red;" onclick="remove_seat('/profile/remove_seats/',<?php echo $seats['pending'][$j]['uid']; ?>);">Remove Expert</a>
								</div>
								<a class="button light_blue" href="javascript:void(0)" onclick="resend_invite_seat('/profile/resend_invite_seats/',<?php echo $seats['pending'][$j]['uid']?>)">Resend Invite</a>
								<span class="pending">Invitation Pending</span>
							</div>
						
					<?php 
						}
					}
					if(count($seats['approved']) > 0)
					{	
					   for($k=0;$k<count($seats['approved']);$k++)
						{?>
							<div class="portlet">
								<div class="top">
										<a href="/expertise/<?php echo $seats['approved'][$k]['uid'];?>">
											<?php
											// Old Template
											?>
										</a>
										<h2><?php echo $seats['approved'][$k]['firstname'].' '.$seats['approved'][$k]['lastname']; ?></h2>
										<a href="javascript:void(0);"><?php echo $seats['approved'][$k]['email']; ?></a>
										<a href="javascript:void(0);" style="margin:10px 20px 5px 20px;float:left; color:red;" onclick="remove_seat('/profile/remove_seats/',<?php echo $seats['approved'][$k]['uid']; ?>);">Remove Expert</a>
								</div>
								<a href="/expertise/<?php echo $seats['approved'][$k]['uid'];?>"  class="button light_green"> View Profile</a>
								<label><input type="checkbox">Promote Expert</label>
							</div>
					<?php 
						}
					}
				?>			
				<?php } ?>
				
				<?php for($i=$totalassigned;$i<$provided_seat;$i++)
				{?>
				<div class="portlet">
					<div class="invite">
						<div class="top">
							<img alt="Alt Text" src="../images/site/seat_empty.png">
							<h2 class="empty"><?php echo $i+1 ?></h2>
						</div>
						<a class="button light_orange" href="#">Invite</a>
					</div>
					<div class="invite_form">
					<?php echo form_open_multipart("profile/send_invite_seats/".$users['uid']."",array("id"=>"invite_seats_form",'class'=>'ajax_form')); ?>

						<?php echo form_hidden_custom("hdn_inviteno",$i,FALSE,"id='hdn_inviteno'"); ?>

						<div class="top">
							<h3>Invite Expert</h3>
							
						<div class="fld">
							<?php echo form_input(array('type'=>'text','id'=>'first_name_'.$i,'name'=>'first_name_'.$i,'placeholder'=>'First Name')); ?>
							<div class="errormsg" id="err_invite_firstname"><?php echo form_error("first_name_".$i); ?></div>
						</div>
						<?php echo br();  ?>
						
						<div class="fld">
							<?php echo form_input(array('type'=>'text','id'=>'last_name_'.$i,'name'=>'last_name_'.$i,'placeholder'=>'Last Name')); ?>
							<div class="errormsg" id="err_invite_lastname"><?php echo form_error("last_name_".$i); ?></div>
						</div>
						<?php echo br();  ?>
						
						<div class="fld">
							<?php echo form_input(array('type'=>'text','id'=>'email_'.$i,'name'=>'email_'.$i,'placeholder'=>'Email')); ?>
							<div class="errormsg" id="err_invite_email"><?php echo form_error("email_".$i); ?></div>
						</div>
						<?php echo br();  ?>
							<span class="message">An email will be sent to this	expert.</span>
						</div>
						<?php echo form_submit(array('name'	=> 'invite_seat','value' => 'Send Invite','class' => 'button light_green','style'=>'margin:0px!important;'));  ?>
						<a class="cancel" href="#">Cancel</a>
					</div>
					<?php echo form_close(); ?>
				</div>
				<?php } ?>
				


			</div><!-- seat_portlets -->
		</div><!-- end .inner -->

<div aria-labelledby="ui-dialog-title-dialog-message" class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable ui-resizable" role="dialog" style="display: none; z-index: 1002; outline: 0px none; position: absolute; height: auto; width: 300px; top: 1050px; left: 558px;" tabindex="-1">
		<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
			<span id="ui-dialog-title-dialog-message" class="ui-dialog-title">Message</span>
			<a class="ui-dialog-titlebar-close ui-corner-all" href=javascript:void(0); role="button">
				<span class="ui-icon ui-icon-closethick">close</span>
			</a>
		</div>
		<div id="dialog-message" class="ui-dialog-content ui-widget-content" scrollleft="0" scrolltop="0" style="width: auto; min-height: 12.8px; height: auto;">
			Your profile has been successfully updated</div>
		<div class="ui-resizable-handle ui-resizable-n"></div>
		<div class="ui-resizable-handle ui-resizable-e"></div>
		<div class="ui-resizable-handle ui-resizable-s"></div>
		<div class="ui-resizable-handle ui-resizable-w"></div>
		<div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se ui-icon-grip-diagonal-se" style="z-index: 1001;"></div>
		<div class="ui-resizable-handle ui-resizable-sw" style="z-index: 1002;"></div>
		<div class="ui-resizable-handle ui-resizable-ne" style="z-index: 1003;"></div>
		<div class="ui-resizable-handle ui-resizable-nw" style="z-index: 1004;"></div>
		<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
			<div class="ui-dialog-buttonset">
				<button aria-disabled="false" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" type="button">
				<span class="ui-button-text">Ok</span></button>
			</div>
		</div>
	</div>

	</div>
</div>
