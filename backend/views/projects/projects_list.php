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
                </div><!--tableoptions-->

                    	<?php
							foreach($proj as $proj)
							{
							    ?>
                          	<p align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$proj["pid"]."","name"=>"select_".$proj["pid"]."","value"=>$proj["pid"])); ?></span></p>
                            <p><?php echo $proj["pid"]; ?></p>
                            <p>
							<span style="float:left;padding-left:5px;">
								<a href="/<?php echo index_page(); ?>/projects/edit/<?php echo $proj["slug"];?>"><?php echo $proj["projectname"];?></a>
							</span>

                            </p>

						<?php
							}
						?>
        </div><!--contentwrapper-->
        
	</div>
