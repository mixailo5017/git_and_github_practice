<?php
	$opt["project_form"] = array(
			'title'	=> array(
        		'id'	=> 'title_input',
	            'value' => $project["projectname"],
	            'name'	=> 'title_input'
	        )
	 );
?>
<div id="content" class="clearfix">
<div id="project_form">
		<div id="col4">
			<?php
				$leftnavlist = array(
					'<a href="/projects/edit/'.$slug.'">'.lang('ProjectInformation').'</a>',
					'<a href="/projects/edit_fundamentals/'.$slug.'">'.lang('Fundamentals').'</a>',
					'<a href="/projects/edit_financial/'.$slug.'">'.lang('Financial').'</a>',
					'<a href="/projects/edit_regulatory/'.$slug.'">'.lang('Regulatory').'</a>',
					'<a href="/projects/edit_participants/'.$slug.'">'.lang('Participants').'</a>',
					'<a href="/projects/edit_procurement/'.$slug.'">'.lang('Procurement').'</a>',
					'<a href="/projects/edit_files/'.$slug.'">'.lang('Files').'</a>'
				);
				/*
				$leftnavlist = array(
					'<a onclick="tabload(\'/projects/edit/'.$slug.'\');" href="javascript:void(0);">Project Information</a>',
					'<a onclick="tabload(\'/projects/load_tab/'.$slug.'\');" href="javascript:void(0);">Fundamentals</a>',
					'<a onclick="tabload(\'/projects/edit_financial/'.$slug.'\');" href="javascript:void(0);">Financial</a>',
					'<a onclick="tabload(\'/projects/edit_regulatory/'.$slug.'\');" href="javascript:void(0);">Regulatory</a>',
					'<a onclick="tabload(\'/projects/edit_participants/'.$slug.'\');" href="javascript:void(0);">Participants</a>',
					'<a onclick="tabload(\'/projects/edit_procurement/'.$slug.'\');" href="javascript:void(0);">Procurement</a>',
					'<a onclick="tabload(\'/projects/edit_files/'.$slug.'\');" href="javascript:void(0);">Files</a>'
				);

				*/
				$leftnavattrib = array(
					'id' => 'profile_nav'
				);
				
				for($i=0;$i<count($leftnavlist);$i++)
				{
					if($i == $vtab_position)
					{
						$listattributes[]='class="here"';
					}
					else
					{
						$listattributes[]='';
					}
				}
				echo ul_custom($leftnavlist,$leftnavattrib,$listattributes);
			?>
		</div><!-- end #col4 -->

		<div id="col5">
			<?php echo form_open_multipart("projects/updatename/".$slug."",array("id"=>"project_name_form","class"=>"ajax_form")); ?>
			<?php echo heading(lang("ProjectName").": ".form_input($opt["project_form"]["title"])."<label class='errormsg' id='err_title_input'></label>",1,"class='col_top gradient'"); ?>
			<?php echo form_close(); ?>
		<div class="profile_links">

			<div id="form_submit">
			
				<a href="/projects/<?php echo $slug; ?>"><?php echo lang('ViewMyProject');?></a>
				
				<a href="#" id="update_project" class="light_green"><?php echo lang('UpdateProject');?></a>
				
			</div>
		
		</div>
			
				<?php $this->load->view($main_content,$project); ?>
				<!-- end #tabs -->

		</div><!-- end #col5 -->
</div>
	</div><!-- end #content -->

	<div id="dialog-message"></div>