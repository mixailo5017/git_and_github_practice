<div class="centercontent tables">
        <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $headertitle; ?></h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
        
        <div id="contentwrapper" class="contentwrapper">
                       
              	<div class="contenttitle2">
              		<?php echo heading("View All Members Groups",3); ?>
                </div><!--contenttitle-->
                <div class="notibar" style="display:none">
                    <a class="close"></a>
                    <p></p>
                </div>
                
                <?php echo form_open('/members/update_member_group/'.$group_data['typeid'],array('id'=>'update_member_group_form','class'=>'stdform stdform2','method'=>'POST','name'=>'update_member_group_form'));?>
                	
                	<p style="border-top:1px #dddddd solid;">
                    	<?php echo form_label('Group Title:', 'group_title','');
                    	
                    	($group_data['typename'])?$grouptitle = $group_data['typename']:$grouptitle = '';
                    	?> 
                        <span class="field"><?php echo form_input(array('id'=>'group_title','name'=>'group_title','class'=>'longinput','value'=>$grouptitle));?></span>
                    </p>
 
                    <p class="stdformbutton">
 	                   	<?php echo form_submit('submit', 'Update Group','class = "submit radius2"');?>
                    </p>
                    
            <?php echo form_close();?>
                
                
        </div><!--contentwrapper-->
        
	</div>