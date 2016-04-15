  <div class="clearfix" id="content">
    <div style="width:965px;" class="center_col white_box" id="col5">
        <h1 class="col_top gradient" style="height:100px;"><?php echo lang('ExpertAttendees')?></h1>
        <div class="project_filter clearfix">
	
            <?php $m = 'forums/experts/'.$iduser;

             echo form_open($m, array('id' => 'expertise_search_form', 'name' => 'search_form', 'method' => 'get'));?>

            <div style="float:right;">
               
                <div class="filter_option">
					<p><?php echo lang('Filterby')?>:</p>
				</div>
               
                 <div class="filter_option">
                    <?php
                   echo form_dropdown('country', country_dropdown(), $filter['country'], 'style="width:170px;"') //id="member_country" ?>
                </div>
                   <div class="filter_option" >
                    <?php
                     echo form_dropdown('sector', sector_dropdown(), $filter['sector'], 'style="width:170px;"') //id="member_sectors" ?>
                </div>
                 <div class="filter_option">
                    <?php echo form_dropdown('subsector', subsector_dropdown($filter['sector']), $filter['subsector'], 'style="width:170px;"') ?>
                </div>
                 <div class="filter_option">
    				<?php echo form_dropdown('discipline', discipline_dropdown(), $filter['discipline'], 'style="width:170px;"') //'id="member_discipline"' ?>
				</div>
             </div>
                <div style="float:right; padding-right:10px;">
               
				
                    <div class="filter_option">
                        <p><?php echo lang('Search')?>:</p>
                    </div>
                     <div class="filter_option">
                    <?php echo form_input('searchtext', $filter['searchtext'], 'placeholder="'. lang('ExpertTextSearchTip').'"') //'id="search_text"' ?>
                </div>
                    <div class="filter_option">
                        <?php echo form_submit('search', lang('Search'), 'class = "light_green"');?>
                    </div>
                </div>               
        <input type="hidden" name="limit" value="<?php echo $limit ?>">       
                <?php echo form_close();?>
                 </div>		
         <div class="inner clearfix">
            <div style="float: right; padding-right: 10px;">
                <div class="filter_option">
                <?php 
                     $m = 'forums/experts/'.$iduser;				
        			echo form_dropdown('limit_options', view_limit_options(), $limit) 
                 ?>
                </div> 
                <div class="filter_option">
                    <p><?php echo lang('PerPage')?></p>
                </div>
               </div>
          
                 <?php echo form_paging(true, $page_from, $page_to, $filter_total, lang('Experts'), $paging);?>
                   </div>
        <div class="inner clearfix">
            <?php
            $index = 0;
            if (count($users) > 0) {
                foreach($users as $key=> $val) {

                    $fullname = $val['firstname'] . ' ' . $val['lastname'];

                    $data = array(
                        'url' => base_url() . 'expertise/' . $val['uid'],
                        'image' => array(
                            'url' => expert_image($val['userphoto'], 198),
                            'alt' => $fullname . ' image'
                        ),
                        'title' => '<strong>' . $fullname . '</strong><br>' .$val['title'] . '<br>' . $val['organization'],
                        'properties' => array(
                            array(lang('Country'), $val['country'],  1),
                            array(lang('Sector'),  $val['sector'], 1),
                            array(lang('Discipline'),  $val['discipline'], 1)
                        ),
                        'last' => ($index == 3)
                    );
                    $this->load->view('templates/_list_block', $data);
                    $index = ($index == 3) ? 0 : $index + 1;
                }
            } else {
                echo form_list_empty(lang('NoExpertisedplay'));
            }
            ?>
            
            <?php
            echo form_paging(false, $page_from, $page_to, $filter_total, lang('Experts'), $paging);
            ?>
        </div><!-- end .inner -->
    </div><!-- end #col5 -->
</div><!-- end #content -->
<div id="dialog-message"></div> 
 <script> 
 var subsectors = <?php echo json_encode($all_subsectors) ?>;
</script>