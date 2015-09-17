<div id="content" class="clearfix">
	<div id="welcome_first_time">
		<div id="forgot_password" >
		
			<?php echo heading('Account Created',1); ?>
			<div class="inner clearfix">
				<div class="fld">
					<?php if($result["stage"] == "verify") { ?>
						<div class="<?php echo $result['data']["status"]; ?>">
							<?php echo $result['data']["msg"]; ?>
						</div>
					<?php } else { ?>
						<?php if(count($result["data"]) > 0) { ?>
							<div class="<?php echo $result["data"]["status"]; ?>"><?php echo $result["data"]["msg"]; ?></div>
							<div class="clear" style="height:10px;">&nbsp;</div>
						<?php } ?>
						
					<?php } ?>
				</div>
				<div class="clear">&nbsp;</div>
				<a href="/"><?php echo lang('BacktoLogin');?></a>
			</div>

		</div>
	</div>
</div>