<div class="centercontent tables">
    
        <div class="pageheader notab">
            <h1 class="pagetitle">View Projects</h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
        
        <div id="contentwrapper" class="contentwrapper">
                       
              	<div class="contenttitle2">
              		<?php echo heading("View All Project List",3); ?>
                </div><!--contenttitle-->
                <div class="notibar" style="display:none">
                    <a class="close"></a>
                    <p></p>
                </div>
                <div class="tableoptions">
                	<button class="deletebutton radius3" title="Delete Selected" name="dyntable_projectlist" id="#/admin.php/projects/delete_projects">Delete Selected</button> &nbsp;
                	Projects Sector:&nbsp;
                	
                	<?php 
					$member_sector_main_attr	= 'id="tbl_project_sector_main"';
					$sector_option = array();
					$sector_opt =array();
					foreach(sectors() as $key=>$value)
					{
						$sector_options[$value] = $value;
						$sector_opt[$value] 	= 'class="sector_main_'.$key.'"';
					}
					$sector_first			= array('class'=>'hardcode','text'=>'- Select A Sector -','value'=>'');
					$sector_last			= array();
					
					echo form_custom_dropdown('tbl_project_sector_main', $sector_options,'',$member_sector_main_attr,$sector_opt,$sector_first,$sector_last);
					?>
					&nbsp;&nbsp;
					Project Owner:&nbsp;&nbsp;
					<?php 
					$proj_uid = get_project_owner_dropdown();
					
					$project_owner_attr		= 'id="tbl_project_owner"';
					$project_owner_option 	= $proj_uid;
					$project_owner_opt		= $proj_uid;
					$project_owner_first	= array('class'=>'hardcode','text'=>'- Select Project Owner -','value'=>'');
					$project_owner_last		= array();
					
					echo form_custom_dropdown('tbl_project_owner', $project_owner_option,'',$project_owner_attr,$project_owner_opt,$project_owner_first,$project_owner_last);
					?>
				
                	
                </div><!--tableoptions-->
                <table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_projectlist">
                    <colgroup>
                        <col class="con0" style="width: 4%" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" />
                    </colgroup>
                    <thead>
                        <tr>
                          <th class="head0 nosort" align="center"><?php echo form_checkbox(array("id"=>"select_all_header","name"=>"select_all_header","class"=>"checkall")); ?></th>
                          	<th class="head1">ID</th>
                            <th class="head0">Project</th>
                            <th class="head1">Project Owner</th>
                            <th class="head0">Country</th>
                            <th class="head1">Sector</th>
                            <th class="head0">Stage</th>
                            <th class="head1 nosort">Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
                            <th class="head1">ID</th>
                            <th class="head0">Project</th>
                            <th class="head1">Project Owner</th>
                            <th class="head0">Country</th>
                            <th class="head1">Sector</th>
                            <th class="head0">Stage</th>
                            <th class="head1 nosort">Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    	<?php 
		                $i = 0;
							foreach($proj as $proj)
							{
						?>
						<tr>
                          	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$proj["pid"]."","name"=>"select_".$proj["pid"]."","value"=>$proj["pid"])); ?></span></td>
                            <td><?php echo $proj["pid"]; ?></td>
                            <td>
                            <div style="float:left;width:60px;">
                            <img alt="<?php echo $proj["projectname"];?>" style="float:left;" src="<?php echo project_image($proj["projectphoto"], 50);?>" width="50" >
							</div>
							<span style="float:left;padding-left:5px;">
								<a href="/<?php echo index_page(); ?>/projects/edit/<?php echo $proj["slug"];?>"><?php echo $proj["projectname"];?></a>
							</span>
                            	
                            </td>
                            <td><?php echo $proj['country']; ?></td>
                            <td><?php echo $proj["sector"]; ?></td>
                            <td><?php echo $proj["stage"]; ?></td>
                        </tr>

						<?php
							}
						?>
                    </tbody>
                </table>
                
        </div><!--contentwrapper-->
        
	</div>

