<div class="centercontent">
    
    <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $title; ?></h1>
            <a class="right goback" style="margin-right:20px;" href="/admin.php/projects/view_all_projects"><span>Back</span></a>
            <div class="pagetitle2"><?php echo $project["projectname"];?></div>
            <span class="pagedesc">&nbsp;</span>
				<div class="navoptions">
				    <?php
							$leftnavlist = array(
								'<a href="/admin.php/projects/edit/'.$slug.'">Project Information</a>',
								'<a href="/admin.php/projects/edit_fundamentals/'.$slug.'">Fundamentals</a>',
								'<a href="/admin.php/projects/edit_financial/'.$slug.'">Financial</a>',
								'<a href="/admin.php/projects/edit_regulatory/'.$slug.'">Regulatory</a>',
								'<a href="/admin.php/projects/edit_participants/'.$slug.'">Participants</a>',
                                    '<a href="/admin.php/projects/edit_procurement/'.$slug.'">Procurement</a>',
								'<a href="/admin.php/projects/edit_files/'.$slug.'">Files</a>'
							);
							
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
				</div>
			
        </div><!--pageheader-->
  
        
        <div id="contentwrapper" class="contentwrapper">
	         <div class="widgetcontent">
			 <?php $this->load->view($main_content,$project); ?>
		</div><!-- end #col5 -->
	</div>
</div><!-- end #content -->
