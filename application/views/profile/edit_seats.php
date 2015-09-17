<div id="content" class="clearfix">
<div id="col4">
		<ul id="profile_nav">
			<li><a href="/profile/account_settings"><?php echo lang('ProfileInformation');?></a></li>
			<!-- ExpertAdverts Start-->
			<?php if($usertype == '8')
			{?>
				<li class="here"><a href="/profile/edit_seats"><?php echo lang('EditSeats');?></a></li>
				<li><a href="/profile/edit_case_studies"><?php echo lang('EditCaseStudies');?></a></li>
				<li><a href="javascript:void(0);"><?php echo lang('StorePurchaseHistory');?></a></li>
				<li><a href="javascript:void(0);"><?php echo lang('LicenseInformation');?></a></li>
			<?php
			}
			?>
			<!-- ExpertAdverts End-->
			<li><a href="/profile/account_settings_email"><?php echo lang('EmailPassword');?></a></li>
		</ul>
	</div>
	
	<div id="col5">
		<a class="bolt" href="#"><?php echo lang('Whypromote');?></a>
		<h1 class="col_top gradient"><?php echo lang('Haveaseat').', '.$users['organization'];?>.!</h1>
		<div class="profile_links">
			<div id="form_submit">
				<a href="/expertise/<?php echo $users['uid'];?>" class="light_gray"><?php echo lang('ViewMyProfile'); ?></a>
			</div>
		</div>
	
		
		<div class="inner">
			<div class="seat_portlets seat_portlets2">
			<?php
				$totalassigned  = 0;
				$j = 0;
				$provided_seat = $users['numberofseat'];
				$totalassigned  = count($seats['pending'])+ count($seats['approved']);
				$availableseat = ($provided_seat - $totalassigned);
			
			?>
				<p class="callout"><strong><?php echo lang('Youhave');?> <?php echo $availableseat;?> <?php echo lang('spotsavailable');?></strong> 
				<?php echo lang('Invite,remove');?>. <a href="#"><?php echo lang('Getmoreseats');?></a></p>

				<?php 
				if ($totalassigned > 0) {
					if (count($seats['pending']) > 0) {
						for ($j = 0; $j < count($seats['pending']); $j++) { ?>
						<div class="portlet">
							<?php
                            $src = expert_image($seats['pending'][$j]['userphoto'], 60, array('rounded_corners' => array(array('bl', 1), array('tl', 1))));
                            $alt = $seats['pending'][$j]['firstname'].' '.$seats['pending'][$j]['lastname']." 's photo";
							?>
                            <img src="<?php echo $src ?>" alt="<?php echo $alt ?>" style="margin:0px">
							<div class="middle">
								<h2><?php echo $seats['pending'][$j]['firstname'].' '.$seats['pending'][$j]['lastname']; ?></h2>
								<a href="javascript:void(0);" style="float: left; width: 160px;"><?php echo $seats['pending'][$j]['email']; ?></a>
								<a href="javascript:void(0);" style="color:red;" onclick="remove_seat('/profile/remove_seats/',<?php echo $seats['pending'][$j]['uid']; ?>);"><?php echo lang('RemoveExpert');?></a>
							</div>
							<a class="button light_blue" href="javascript:void(0)" onclick="resend_invite_seat('/profile/resend_invite_seats/',<?php echo $seats['pending'][$j]['uid']?>)"><?php echo lang('ResendInvite');?></a>
							<span class="pending"><?php echo lang('InvitationPending');?></span>
						</div>
						
					<?php 
						}
					}
					if (count($seats['approved']) > 0) {
					    for ($k=0;$k<count($seats['approved']);$k++) {
				        ?>
							<div class="portlet">
								<a href="/expertise/<?php echo $seats['approved'][$k]['uid'];?>">
									<?php
                                    $src = expert_image($seats['approved'][$k]['userphoto'], 60, array('rounded_corners' => array(array('bl', 1), array('tl', 1))));
                                    $alt = $seats['approved'][$k]['firstname'].' '.$seats['approved'][$k]['lastname']." 's photo";
									?>
                                    <img src="<?php echo $src ?>" alt="<?php echo $alt ?>" style="margin:0px">
                                </a>
								<div class="middle">
										<h2><?php echo $seats['approved'][$k]['firstname'].' '.$seats['approved'][$k]['lastname']; ?></h2>
										<a href="javascript:void(0);"style="float: left; width: 160px;"><?php echo $seats['approved'][$k]['email']; ?></a>
										<a href="javascript:void(0);" style=" color:red;" onclick="remove_seat('/profile/remove_seats/',<?php echo $seats['approved'][$k]['uid']; ?>);"><?php echo lang('RemoveExpert');?></a>
								</div>
								<a href="/expertise/<?php echo $seats['approved'][$k]['uid'];?>"  class="button light_green"> <?php echo lang('ViewProfile');?> </a>
								<label><input type="checkbox"><?php echo lang('PromoteExpert');?></label>
							</div>
					<?php 
						}
					}
				    ?>
				<?php } ?>
				
				
				<?php for ($i = $totalassigned; $i < $provided_seat; $i++) {?>
				<div class="portlet">
					<div class="invite">
                        <?php
                        $src = safe_image(USER_NO_IMAGE_PATH, 'seat_empty_new.png', null, array('max' => 60, 'rounded_corners' => array(array('bl', 1), array('tl', 1))));
                        $alt = lang("emptyseat");
                        ?>
                        <img src="<?php echo $src ?>" alt="<?php echo $alt ?>" style="margin:0px">

                        <div class="middle">
							<h2 class="empty"><?php echo lang('Seat')." ".($i+1); ?></h2>
						</div>
						<a class="button light_orange" href="#"><?php echo lang('Invite');?></a>
					</div>
					
					
					<div class="invite_form">
					<?php echo form_open_multipart("profile/send_invite_seats/".$users['uid']."",array("id"=>"invite_seats_form",'class'=>'ajax_form')); ?>

						<?php echo form_hidden_custom("hdn_inviteno",$i,FALSE,"id='hdn_inviteno'"); ?>

						<div class="top">
							<a class="cancel" href="#"><?php echo lang('Cancel');?></a>
														
							<?php echo form_input(array('type'=>'text','id'=>'first_name_'.$i,'name'=>'first_name_'.$i,'placeholder'=>lang('FirstName'))); ?>
							<div class="errormsg" style="float:left;"  id="err_invite_firstname"><?php echo form_error("first_name_".$i); ?></div>
						
							<?php echo form_input(array('type'=>'text','id'=>'last_name_'.$i,'name'=>'last_name_'.$i,'placeholder'=>lang('LastName'))); ?>
							<div class="errormsg" style="float:left;" id="err_invite_lastname"><?php echo form_error("last_name_".$i); ?></div>
						
							<?php echo form_input(array('type'=>'text','id'=>'email_'.$i,'name'=>'email_'.$i,'placeholder'=>lang('Email'))); ?>
							<div class="errormsg" style="float:left;" id="err_invite_email"><?php echo form_error("email_".$i); ?></div>
						
						</div>
						<?php echo form_submit(array('name'	=> 'invite_seat','value' => lang('SendInvite'),'class' => 'button light_green langEditSeat','style'=>'margin:0px!important;'));  ?>
						
					</div>
					<?php echo form_close(); ?>
				</div>
				<?php } ?>
				


			</div><!-- seat_portlets -->
		</div><!-- end .inner -->

<div aria-labelledby="ui-dialog-title-dialog-message" class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable ui-resizable" role="dialog" style="display: none; z-index: 1002; outline: 0px none; position: absolute; height: auto; width: 300px; top: 1050px; left: 558px;" tabindex="-1">
		<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
			<span id="ui-dialog-title-dialog-message" class="ui-dialog-title"><?php echo lang('Message');?></span>
			<a class="ui-dialog-titlebar-close ui-corner-all" href=javascript:void(0); role="button">
				<span class="ui-icon ui-icon-closethick"><?php echo lang('close');?></span>
			</a>
		</div>
		<div id="dialog-message" class="ui-dialog-content ui-widget-content" scrollleft="0" scrolltop="0" style="width: auto; min-height: 12.8px; height: auto;">
			<?php echo lang('SuccessUpdate');?></div>
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
				<span class="ui-button-text"><?php echo lang('Ok');?></span></button>
			</div>
		</div>
	</div>

	</div>
</div>
