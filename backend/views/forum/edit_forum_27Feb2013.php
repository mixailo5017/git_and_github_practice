<div class="centercontent">

	<div class="pageheader notab">

        <h1 class="pagetitle">Edit Forum</h1>

        <span class="pagedesc">&nbsp;</span>

	</div>

	<div id="contentwrapper" class="contentwrapper">

		 <div class="floatleft clearfix" style="position:relative; width:100%;">
                <div class="contenttitle2">
                    <h3>Upload Banner</h3>
                </div>
                <br/>											
                <div style="width:100%;" class="clearfix floatleft"><div class="div_resize_img150">
                    <?php
					// don't think this template is being used anymore                    
                    ?>
                </div>
                <div class="floatleft" style="padding-left:10px;">
                    <div class="comment no_margin_top">Select an image from your computer (5MB max):</div>
                    <?php echo form_open_multipart('forum/upload_banner/',array('id'=>'forum_banner_form','name'=>'forum_banner_form','method'=>'post','class'=>'ajax_form'));?>
                    <?php 
					$opt['forum_banner_form'] = array('banner_filename' => array('name' => 'banner_filename','id' => 'banner_filename'));
					?>
                    
                    <div class='hiddenFields'>
                        <?php echo form_hidden("RET",current_url()); ?>
                    </div>
    
                    <?php echo form_upload($opt['forum_banner_form']['banner_filename']);?>
                        <?php echo form_submit('submit', 'Upload Banner Image','class = "light_green no_margin_left"',array('title'=>'Upload Banner Image'));?>
                        <div id="err_banner_filename" class="errormsg"></div>
                        <div class="comment">Compatible file types: JPEG, GIF, PNG</div>
                    <?php echo form_close();?>
                </div>
        </div>
                

		<?php echo form_open_multipart("forum/update_forum",array("id"=>"update_forum")); ?>

			<div style="clear:both;"></div>
            <div class="contenttitle2">
                <h3>Forum Description</h3>
            </div>
            <div style="clear:both;float:left;margin:0px 0px 20px 0px;width:100%;">
			<?php echo form_textarea(array('type'=>'text','class'=>'tinymce','id'=>'forum_description','name'=>'forum_description','value'=>$forum_detail['content'])); ?>
			</div>
            
            <div style="width:900px;">
                <div style="position:relative;width:50%;" class="floatleft clearfix">
                    <div class="contenttitle2">
                        <h3>Expert List</h3>
                    </div>
                    <?php
			
			echo '<div style="height:300px; overflow-x:auto;" id="div_general_photo_form" class="clearfix">';
			
			if(count($forum_experties) > 0)
			{
				foreach($forum_experties as $forum_ekey=>$forum_evalue)
				{
						 
						 $checked = '';
						 
						 $expID = explode(",",$forum_detail['ExpID']);
						 if(count($expID) > 0)
						 {
								if(in_array($forum_ekey,$expID)){$checked = 'CHECKED';}else {$checked = '';}
						 	 
						 }
						 else
						 {
							$checked = 'CHECKED';
						 }
						 
						 
						 $chkexp = array(
									'name'        => 'chkMembers[]',
									'id'          => 'chkMembers_'.$forum_ekey,
									'value'       => $forum_ekey,
									'checked'     => $checked
									);
						 echo "<div style=' height: 27px;overflow: hidden;'>";
						 echo form_checkbox($chkexp);
						 echo form_label($forum_evalue, 'chkMembers_'.$forum_ekey,array('style'=>'float:right;width:90%;','class'=>'lblmulticheck'));
						 echo "</div>";
				}
			}
			echo "</div>";
			?>
	            </div>
                <div style="position:relative;width:50%;" class="floatright clearfix">
                    <div class="contenttitle2">
                        <h3>Project List</h3>
                    </div>
                    <?php
					echo '<div style="height:300px; overflow-x:auto;" id="div_general_photo_form" class="clearfix">';
					if(count($forum_projects) > 0)
					{
						foreach($forum_projects as $forum_pkey=>$forum_pvalue)
						{
							 $pchecked = '';
							 
							 $projID = explode(",",$forum_detail['ProjID']);
							 if(count($projID) > 0)
							 {
									if(in_array($forum_pkey,$projID)){$pchecked = 'CHECKED';}else {$pchecked = '';}
								 
							 }
							 else
							 {
								$pchecked = '';
							 }
						 
						 $chkprj = array(
									'name'        => 'chkProjects[]',
									'id'          => 'chkProjects_'.$forum_pkey,
									'value'       => $forum_pkey,
									'checked'     => $pchecked
									);
						 echo "<div style=' height: 27px;overflow: hidden;'>";
						 echo form_checkbox($chkprj);
						 echo form_label($forum_pvalue, 'chkProjects_'.$forum_pkey,array('style'=>'float:right;width:90%;','class'		  => 'lblmulticheck'));
						 echo "</div>";
						}
					}
					echo "</div>";
					?>
	            </div>
            </div>
            
			

			<?php

				$opt = array(
						
						'submit' => array(

			        	'name' => 'Update Forum',

			        	'value' => 'Update Forum',

			        	'class' => 'light_green left mt',
						'title'		  => 'Update Forum'

			        ),
					
					'chkProjects' =>
					 array(
						'name'        => 'chkProjects',
						'id'          => 'chkProjects',
						'value'       => 'accept',
						'checked'     => TRUE,
						'style'       => 'margin:10px',
						),

			        'cancel' => array(

			        	'name' => 'cancel',

			        	'class' => 'light_gray left mt lmol',

			        	'onclick' => "window.location.href='/admin.php/dashboard'",

			        	'content' => 'Cancel',
						
						'title' => 'Cancel'						

			        )

				);

			?>

			<div>


			<div style="margin-top:20px;">

				
                <?php //echo form_checkbox($opt["chkProjects"]); ?>
				<?php echo form_submit($opt["submit"]); ?>

				<?php echo form_button($opt["cancel"]); ?>

			</div>

		<?php echo form_close(); ?>
        
	</div>
<br/>
<br/>
<br/>
</div>