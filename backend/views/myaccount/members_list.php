<div class="centercontent tables">
    
        <div class="pageheader notab">
            <h1 class="pagetitle">View Members</h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
        
        <div id="contentwrapper" class="contentwrapper">
                       
              	<div class="contenttitle2">
              		<?php echo heading("View All Members List",3); ?>
                </div><!--contenttitle-->
                <div class="notibar" style="display:none">
                    <a class="close"></a>
                    <p></p>
                </div>
                <div class="tableoptions">
                	<button class="deletebutton radius3" title="Delete Selected" name="dyntable2" id="#/admin.php/members/delete_members">Delete Selected</button> &nbsp;
                	Member Group&nbsp;
                	<?php
                		$group_attr = "class='radius3' id='member_group_filter'";	
                		$group_options = membergrouplist();
                		$group_first = array('class'=>'','text'=>'All','value'=>'');
                		echo form_custom_dropdown("member_group_filter",$group_options,$members["member_group"],$group_attr,array(),$group_first);
                	?>
                </div><!--tableoptions-->
                <table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable2">
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
                            <th class="head0">Name</th>
                            <th class="head1">Email</th>
                            <th class="head0">Join Date</th>
                            <th class="head1">Member Group</th>
                            <th class="head0">Status</th>
                            <th class="head1 nosort">Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                          <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
                            <th class="head1">ID</th>
                            <th class="head0">Name</th>
                            <th class="head1">Email</th>
                            <th class="head0">Join Date</th>
                            <th class="head1">Member Group</th>
                            <th class="head0">Status</th>
                            <th class="head1 nosort">Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    	<?php 
                    	if($members["totalmembers"] > 0) 
						{
							foreach($members["data"] as $members)
							{
								if($members["uid"] != "")
								{
									if($members["status"] == '0')
									{$status = 'Waiting';}
									elseif($members["status"] == '1')
									{$status = 'Active';}
									elseif($members["status"] == '2')
									{$status = 'Not Verify';}
						?>
						<tr>
                          	<td align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_".$members["uid"]."","name"=>"select_".$members["uid"]."","value"=>$members["uid"])); ?></span></td>
                            <td><?php echo $members["uid"]; ?></td>
                            <td><a href="/<?php echo index_page(); ?>/myaccount/<?php echo $members["uid"]; ?>"><?php echo $members["firstname"]." ".$members["lastname"]; ?></a></td>
                            <td><a href="mailto:<?php echo $members["email"]; ?>"><?php echo $members["email"]; ?></a></td>
                            <td><?php echo DateFormat($members["registerdate"],DATEFORMAT); ?></td>
                            <td><?php echo $members["typename"]; ?></td>
                            <td>
                            	<?php if($status == 'Waiting'){?>
	                          	<a href="/<?php echo index_page(); ?>/members/approve/<?php echo $members["uid"]; ?>">Approve</a> | <a href="/<?php echo index_page(); ?>/members/deny/<?php echo $members["uid"]; ?>">Deny</a> 
	                           <?php
	                            }else {echo $status;} ?>
	                        </td>
	                        <td><a class="delete" href="" name="<?php echo $members["uid"]; ?>" id="#/admin.php/members/delete_members">Delete</a></td>
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