<?php
if ($user['membertype'] == MEMBER_TYPE_MEMBER)  {
    $src = expert_image($user['userphoto'], 150);
    $fullname = $user['firstname'] . ' ' . $user['lastname'];
} else {
    $src = company_image($user['userphoto'], 150);
    $fullname = $user['organization'];
}
?>
<div id="content" class="clearfix">
	<div id="welcome_first_time">
		<div id="without_photo">
			<?php echo form_open_multipart('', array(
                'id' => 'general_photo_form',
                'name' => 'general_photo_form',
                'method' => 'post',
            )) ?>
			<h1><?php echo lang('WelcometoVIP') . "!" ?></h1>
			<div class="inner clearfix">
				<p class="intro"><?php echo lang('uploadFirstPhoto') ?></p>
				<div class="fld">
                    <img src="<?php echo $src ?>" alt="<?php echo $fullname ?>'s photo" class="uploaded_img left">
				</div>
				<div class="fld">
					<p><?php echo lang('SelectImage') ?> (5MB max):</p>
					<div>
						<?php echo form_upload(array('name' => 'photo_filename', 'id' => 'photo_filename')); ?>
						<div id="err_photo_filename" class="errormsg"><?php echo $error ? : '' ?></div>
					</div>
					<p><em><?php echo lang('Compatiblefiletypes') ?>: JPEG, GIF, PNG</em></p>

                    <a href="/profile/account_settings"><?php echo lang('Skip') ?></a>
                    &nbsp;&nbsp;
                    <?php echo form_submit('welcome_submit', lang('UploadProfileImage'), 'class = "light_green"'); ?>
				</div>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>