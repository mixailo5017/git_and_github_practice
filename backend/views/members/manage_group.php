<div class="centercontent tables">
    
        <div class="pageheader notab">
            <h1 class="pagetitle">Member Groups</h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
        
        <div id="contentwrapper" class="contentwrapper">
                       
              	<div class="contenttitle2">
              		<?php echo heading("Member Group List",3); ?>
                </div><!--contenttitle-->
                <div class="notibar" style="display:none">
                    <a class="close"></a>
                    <p></p>
                </div>
                <div class="tableoptions">
                	<button class="deletebutton radius3" title="Delete Selected" name="dyntable2" id="#/admin.php/members/delete_group">Delete Selected</button> &nbsp;
                	Group Status&nbsp;
                	<?php
                		$group_attr = "class='radius3' id='member_group_status'";	
                		$group_options = array(
                			"Enabled" => "Enabled",
                			"Disabled" => "Disabled"
                		);
                		$group_first = array('class'=>'','text'=>'All','value'=>'');
                		echo form_custom_dropdown("member_group_status",$group_options,'',$group_attr,array(),$group_first);
                	?>
                </div><!--tableoptions-->
                <table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable2">
                    <colgroup>
                        <col class="con0" style="width: 4%" />
                        <col class="con1" style="width: 10%"/>
                        <col class="con0" />
                        <col class="con1" />
                        <col class="con0" />
                        <col class="con1" style="width: 20%" />
                    </colgroup>
                    <thead>
                        <tr>
                          <th class="head0 nosort" align="center"><?php echo form_checkbox(array("id"=>"select_all_header","name"=>"select_all_header","class"=>"checkall")); ?></th>
                          	<th class="head1">Group ID</th>
                            <th class="head0">Group Title</th>
                            <th class="head1">Members</th>
                            <th class="head0">Status</th>
                            <th class="head1 nosort">Options</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
                            <th class="head1">Group ID</th>
                            <th class="head0">Group Title</th>
                            <th class="head1">Members</th>
                            <th class="head0">Status</th>
                            <th class="head1">Options</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    	<?php 
						if($member_group["totalgroups"] > 0) 
						{
							foreach($member_group as $member_group)
							{
								if($member_group["typeid"] != "")
								{
						?>
						<tr>
                          	<td align="center"><span class="center"><?php if($member_group["typeid"] != "1") { echo form_checkbox(array("id"=>"select_".$member_group["typeid"]."","name"=>"select_".$member_group["typeid"]."","value"=>$member_group["typeid"])); } else {  } ?></span></td>
                            <td><?php echo $member_group["typeid"]; ?></td>
                            <td><?php echo $member_group["typename"]; ?></td>
                            <td>(<?php echo $member_group["members"]; ?>) <a href="/admin.php/members<?php echo $member_group["typeid"]; ?>" title="View">View</a></td>
                            <td><?php echo $member_group["status"]==1?"Enabled":"Disabled"; ?></td>
                            <td>
                            	<a class="edit" href="/admin.php/members/edit_member_group/<?php echo $member_group["typeid"]; ?>">Edit Group</a>&nbsp;&nbsp;
                            	<?php if($member_group["typeid"] != "1") { ?>
                            	<a class="delete" href="" name="<?php echo $member_group["typeid"]; ?>" id="#/admin.php/members/delete_group">Delete</a>
                            	<?php } ?>
                            </td>
                        </tr>

						<?php
								}
							}
						}
						?>
                    </tbody>
                </table>
                
        </div><!--contentwrapper-->
        
	</div>