/* Author: Vim Interactive, Inc.

*/
$(function() {

	
	$( ".accordion" ).accordion({
		autoHeight: false,
		navigation: true,
		change: show_stage_status,
		create: show_stage_status			
	});

	function show_stage_status() {
		$('#stage_accordion .ui-accordion-content.ui-accordion-content-active').css('overflow','visible');
	}


	// only allow one open Project Stage
	$('.stage_status_select').change(function(){
		
		if( $('option:selected', this).val() == 'Open' ){
			
			$('#stage_accordion h3.ui-accordion-header a span').remove();

			$(this).parents('div.ui-accordion-content').prev('h3').find('a').append( '<span>open</span>' );

			$('.stage_status_select').val('Closed');
			$(this).val('Open');
		}

	}).each(function(){
		if( $('option:selected', this).val() == 'Open' ){
			
			$('#stage_accordion h3.ui-accordion-header a span').remove();

			$(this).parents('div.ui-accordion-content').prev('h3').find('a').append( '<span>open</span>' );

			$('.stage_status_select').val('Closed');
			$(this).val('Open');
		}
	});

	// start inits
	edu_listing_init();
	ajax_form_init();
	
	// Project Sub-Sector Dynamic List Population
	var $project_sector_sub = $('#project_sector_sub option').not('.hardcode');
	var $project_sector_sub_holder = $project_sector_sub.clone();

	$project_sector_sub.remove().not('.hardcode');

	$('#project_sector_main').change(function() {
	
		$('#project_sector_sub').removeAttr('disabled');
		$('#project_sector_sub').focus();
		
		var thisClass = $(this).find('option:selected').attr('class').replace('sector_main','project_sector_sub');

		$('#project_sector_sub option').not('.hardcode').remove()

		$('#project_sector_sub option:first').after( $project_sector_sub_holder.filter('.' + thisClass) );
		
	}).trigger('change');
	

	// Sub-Sector "Other" Text Input
	// included in profile/_general_info_form to reset
	$('#project_sector_sub').change(function() {
		var $other = $('#project_sector_sub_other');
		if($('option:selected', this).val()==='Other') {$other.parent().show().end().removeAttr('disabled').focus();}
		else {
			$other.parent().hide().end().val('').attr('disabled', 'disabled');
		}
	}).trigger('change');
	
	// Project Budget Slider
	if( $( "#project_budget_slider" ).size() ){
		$( "#project_budget_slider" ).slider({
				value: $( "#project_budget_max" ).val().replace('$','').replace(',',''),
				min: 0,
				max: 50000,
				step: 10,
				slide: function( event, ui ) {
					var num = new String( ui.value );
				$( "#project_budget_max" ).val( "$" + num );
				}
			});
		$( "#project_budget_max" ).val( "$" + $( "#project_budget_slider" ).slider( "value" ) );
	}
	
	// Financial Structure "Other" Text Input
	$('#project_financial').change(function() {
		var $other = $('#project_fs_other');
		if($('option:selected', this).val()==='Other') {$other.parent().show().end().removeAttr('disabled').focus();}
		else {
			$other.parent().hide().end().val('').attr('disabled', 'disabled');
		}
		
	}).trigger('change');
	
	/*
	$('.target').change(function() {
	  alert('Handler for .change() called.');
	});
	*/
	
	// save return value
	$ret = $('input[name="return"]');
	ret_val = $ret.val();

	//remove last arrow from header bread crumb
	$('#header_bread_crumb li a:last').css('background', 'none');

	//activate tabs
	$('#profile_tabs, #project_tabs').tabs({ fx: { opacity: 'toggle', duration: 100}, select: add_tab_to_submit, create: show_tabs });
	
	$('.edit_project').click(function() {
	
		$("#profile_tabs").tabs({ selected: 2 });
		
	})
	
	
	
	// add_tab_to_submit - append tab hash to return value
	function add_tab_to_submit(event, ui) {
		
		$ret.val( ret_val + ui.tab.hash );

	}

	 // show tabs after init
	function show_tabs(event, ui){
		$('#profile_tabs, #project_tabs').fadeIn();
		var tab = $('#profile_tabs .ui-tabs-panel, #project_tabs .ui-tabs-panel').filter(':not(".ui-tabs-hide")').attr('id');
		$ret.val( ret_val + '#' + tab );
	}

	// submit for on update profile button click
	$('#update_project').click(function(e){
		formsubmit = $("#project_name_form").submit();
		
		e.preventDefault();
	})

	// project member select
	$(".chzn-select").chosen({no_results_text: "No results matched"});

	
	
	
	// disable the submit function on the create a new project button
	// this will be changed in future releases
	// changed for cancel button click to go on project list page
	//$("#new_project .lmol").click(function(e) {e.preventDefault();});

	//clean up profile_actions portlet
	$('#profile_actions a:last').css('border-bottom', 'none');

	// Submit create profile form from anchor
	$('form#profile_upload_image a#submit_upload').click(function(){$('form#profile_upload_image').submit();})

	/* Profile Edit Submit Button */

	// Submit update profile from from anchor
	$('a#update_profile').click(function(){
		//$('.ui-tabs-panel:visible form').submit();
		// log($('.ui-tabs-panel:visible form'));
	})

	// project date pickers
	$('.datepicker_month_year').datepicker({
		beforeShow: function() {
			var $date_picker = $('#ui-datepicker-div');
			if( ! $date_picker.parent().hasClass('jqui')){
				$date_picker.wrap( $('<div/>').addClass('jqui') );
			}
		},
		changeMonth: true, 
		changeYear: true, 
		yearRange: '1950:2500',
		dateFormat: "mm/dd/yy" 
	}).change(function(){
		$('#'+this.id.replace('_picker','')).val( $(this).val() + ' 00:00' );
	}).each(function(){
		 $(this).val( $(this).val().substring(0,10) );
	});
	

	$('.education_edit .education_edit_cancel').on('click',function(){
		//log( 'yay' );
	});
	
	
	
	/*$('a.edit').on('click',function(e){
		//log( 'yay' );
		e.preventDefault();
		var $edit_div = $(this).parent().next('div.edit');
		console.log($edit_div);
		$edit_div.slideToggle();

		if( $(this).hasClass('project_row_add')){
		//	log( 'project_row_add' );
			$edit_div.find('.project_new_row').removeAttr('disabled');
		}

	});*/


	// project edit matrix dropdowns
	/*$('a.edit').click(function(e){
		e.preventDefault();
		var $edit_div = $(this).parent().next('div.edit');
		console.log($edit_div);
		$edit_div.slideToggle();

		if( $(this).hasClass('project_row_add')){
		//	log( 'project_row_add' );
			$edit_div.find('.project_new_row').removeAttr('disabled');
		}
	});*/

	$('.matrix_dropdown a.upload_new').click(function(e){
		e.preventDefault();

		$(this).parent().parent().next().next('div.new_version').slideToggle();

	});

	$('#project_form').submit(function(){
		
		// disable unused file inputs
		$('input[type="file"]').each(function(){
			if( ! $(this).val() ){$(this).attr('disabled','disabled');}
			//log( $(this) );
		});
 
		$('select.chzn-select').each(function(){
			field_name =  '#default_' + $(this).attr('name').replace('[]','');
			$more = $(field_name).val().split('|');
			//log( $field );
			if( $arr = $(this).val() ){
				 $(this).val( $.unique($arr.concat($more)) );
			}
		});
		//return false;
	})

	// Matix file replace
	$('input[type="file"]').change(function(){
		if( $(this).val() ){
			file_input_name = $(this).attr('name');
			hidden_input_name = file_input_name.substring(0, file_input_name.length - 1) + '_hidden';
			
			$hidden = $('input[name="' + hidden_input_name + '"]').val('');
			

		}
	})

	// Matix file replace
	$('#project_form input[type="file"]').change(function(){
		if( $(this).val() ){
			file_input_name = $(this).attr('name');
			hidden_input_name = file_input_name + '_hidden';
			
			$hidden = $('input[name="' + hidden_input_name + '"]').val($(this).val());
			

		}
	})


	// delete matrix row
	$('.matrix_dropdown a.delete').click(function(e){

		$li_row 	= $(this).parents('li');
		li_row_id 	= $li_row.attr('id');
		row_id 		= $li_row.attr('id').replace('row_id_','');
		link 		= $(this).attr('href');
		
		$message 	= 'Are you sure? ';

		var buttons = {
				"Yes": function() {
					delete_maxtrix_action(link, row_id, li_row_id);
					$( this ).dialog( "close" );
				},
				"No": function() {
					$( this ).dialog( "close" );
				}
			}
		
		create_message($message, { buttons: buttons, title: "Delete" });
		
	});

	$('div.edit input[type="reset"]').click(function(e){
		e.preventDefault();
		$(this).parent().parent('div.edit').slideToggle();
	})

	// Form Validation
	$('#member_form').validate({
		rules: {
			member_first_name: 'required',
			//member_last_name: 'required',
			email: {required: true, email: true},
			member_organization: 'required',
			register_password: {required: true,minlength: 6,maxlength: 32},
            password_confirm: {required: true, minlength: 4,maxlength:32, equalTo: "#register_password" }
		},
		messages: {
			member_first_name: "First Name is Required.",
			//member_last_name: "Last Name is Required.",
			email: {required:'Email is Required.', email:'Email is Not valid.'},
			member_organization: "Organization Name is Required",
			register_password: {required: "Password is Required",minlength: "Password must be at least 6 characters."},
			password_confirm: {required: "Confirm password is Required",minlength: "Password must be at least 6 characters.", equalTo: "Enter the same password as above." }

		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg") );
		}
	});


// Form Validation
	$('#email_settings_form').validate({
		rules: {
			es_username: {required: true, email: true},
			register_password: {required: true,minlength: 6,maxlength: 32},
        },
		messages: {
			es_username: {required:'Email is Required.', email:'Email is Not valid.'},
			es_password: {required: "Password is Required",minlength: "Password must be at least 6 characters."},
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg") );
		}
	});
	
// Form Validation
	$('#password_settings_form').validate({
		rules: {
			ps_currentpass : {required: true,minlength: 6,maxlength: 32},
			ps_newpassword : {required: true,minlength: 6,maxlength: 32},
		 	ps_confpassword: {required: true, minlength: 4,maxlength:32, equalTo: "#ps_newpassword" }
        },
		messages: {
			es_username: {required:'Email is Required.', email:'Email is Not valid.'},
			es_password: {required: "Password is Required",minlength: "Password must be at least 6 characters."},
			ps_confpassword: {required: "Confirm password is Required",minlength: "Password must be at least 6 characters.", equalTo: "Enter the same password as above." }
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg") );
		}
	});
	
	
	
	$('#login_form').validate({
		rules: {
			email: {required: true, email: true},
			password: 'required'
		},
		messages: {
			email: { required:"Email is Required.",email:"Email is not valid"},
			password: "Password is Required."
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg") );
		}
	});
	
	$('#new_project').validate({
		rules: {
			title: 'required'
		},
		messages: {
			title: "The Title field is required."
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg") );
		}		
		
	});
	
	$('#general_photo_form').validate({
		rules: {
			photo_filename: 'required'
		},
		messages: {
			photo_filename: "The Photo field is required."
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg") );
		}
	
	});

	
	$('#general_video_form').validate({
		rules: {
			member_video: { required: true, url: true }
		},
		messages: {
			member_video: {required:"The Video field is required.",url:"The Video Url is not Valid"}
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg") );
		}
	
	});
	
	$('#general_info_form').validate({
		rules: {
			member_first_name: { required: true},
			member_last_name: { required: true},
			member_title: { required: true},
			member_organization: { required: true},
		},
		messages: {
			member_first_name: {required:"First Name field is required."},
			member_last_name: {required:"Last Name field is required."},
			member_title: {required:"The Title field is required."},
			member_organization: {required:"Organization field is required."},

		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg") );
		}
	
	});


	$('#expertise_education_form').validate({
		rules: {
			education_university: { required: true},
			education_degree: { required: true},
			education_major: { required: true},
		},
		messages: {
			education_university: { required: 'University Name field is required.'},
			education_degree: { required: 'Degree field is required.'},
			education_major: { required: 'Major field is required.'},
		
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg") );
		}
	
	});
	
	$('#project_name_form').validate({
		rules: {
			title_input: { required: true},
		},
		messages: {
			title_input: { required: '*'},
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg") );
		}
	});

	
	$('#project_form').validate({
		rules: {
			project_overview: { required: true},
			project_keywords: { required: true},
			project_country: { required: true},
			project_location: { required: true},
			project_sector_main: { required: true },
			project_sector_sub: { required: true },
			project_budget_max: { required: true },
			project_financial: { required: true }
		},
		messages: {
			project_overview: { required: 'Description is required.'},
			project_keywords: { required: 'Keywords is required.' },
			project_country: { required: 'Country is required.' },
			project_location: { required: 'Location is required.' },
			project_sector_main: {required: 'Sector is required.' },
			project_sector_sub: { required: 'Sub-Sector is required.' },
			project_budget_max: { required: 'Total Budget is required.' },
			project_financial: { required: 'Financial Structure is required.' }

		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg") );
		}
	});
	
	$("#executive_form").validate({
		rules: {
			project_executives_name: { required: true},
			project_executives_company: { required: true},
			project_executives_role: { required: true},
			project_executives_email: { required: true,email: true},
		},
		messages: {
			project_executives_name: { required: 'Name is required.'},
			project_executives_company: { required: 'Company is required.' },
			project_executives_role: { required: 'Role is required.' },
			project_executives_email: { required: 'Email is required.', email: 'Email is not valid.' },
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg") );
		}

	});



});
	
	// DELETE MATRIX ROW FUNCTIONS
	function delete_maxtrix_action(link, row_id, li_row_id) {
			
		new_link = 'http://vip.concept.com/' + link.replace('#','')+'/'+row_id;
/*
		if( $('#'+li_row_id).hasClass('member_delete') ){
			new_link = 'http://globalvipprojects.com/' + link.replace('#?ACT','?ACT');

		}
*/
		$.ajax({
			url: new_link,
			type: "GET",
			data: {},
			dataType: "json",
			success: function(data) {
				//log( data );
				if( data.remove ){$('#'+li_row_id).fadeOut();}
				
  			}
		});

	}

	
	// DELETE EDUCATION ENTRY FUNCTIONS
	function delete_education_action(link, entry_id) {
		
		var ACT = 'http://vip.concept.com' + link;
		
		$.get(link, function(loaddata) {
			if( loaddata.remove ){$('#education_'+entry_id).fadeOut();}

		});

	}
	
	/*function delete_education_action(link,name) {

		var entry_id = link.replace('/profile/form_load/'+name+'/delete/','');
		var url = 'http://vip.concept.com' + link;

		$.get(link, function(loaddata) {
			if( data.remove ){$('#education_'+entry_id).fadeOut();}
				ajax_form_init();

		});
		

		/*$.ajax({
			url: url,
			type: "GET",
			data: {entry_id : entry_id},
			dataType: "html",
			success: function(data) {
				//log( data );
				$('#education_'+entry_id).append(data);	

				ajax_form_init();
  			}
		});*/

	/*}*/


	// EDIT EDUCATION ENTRY FUNCTIONS
	function edit_education_action(link, entry_id) {

		//var entry_id = link.replace('/profile/form_load/'+name+'/edit/','');
		var url = 'http://vip.concept.com' + link;

		$.get(link, function(loaddata) {
			if($('.education_edit'))
			{
				$('.education_edit').remove();
			}
			$('#education_'+entry_id).append(loaddata);	
			

				ajax_form_init();

		});
		


		/*$.ajax({
			url: url,
			type: "GET",
			data: {entry_id : entry_id},
			dataType: "html",
			success: function(data) {
				//log( data );
				$('#education_'+entry_id).append(data);	

				ajax_form_init();
  			}
		});*/

	}

	// Wrapper for ajax form to call on reloads
	function ajax_form_init() {

		$('form.ajax_form').unbind('submit');

		// bind submit handler to form
		$('form.ajax_form').submit(function(e) {
			
			// prevent native submit
			e.preventDefault(); 
			
			
			if($(this).validate().form() == true)
			{
				// define spinner
				var $spinner = $('<div/>')
					.html( $('<img class="spinner" src="/images/site/loader.gif" alt="spinner" width="34" />') )
					.css({'display':'inline'});
	
				// prevent multiple submits
				var $btn = $('input[type="submit"]', this);
				var text = $btn.val();
				$btn.val('Please Wait').removeClass('light_green').addClass('light_gray').attr('disabled', true).after( $spinner );
	
				// get form id and div id
				var name = $(this).attr('id');
				var target = '#' + name.substr(1, name.length);
				
				$(this).ajaxSubmit({
					beforeSubmit: function(){ $(this).validate();
					 },
					success: function( page, status){
	
						//$return_page = $(page);
						//$message = $return_page.find('body#alert_message');
						$message = page.message;
						
						
	
						// Success
						if( page.message || name == 'education_list' || name == 'expertise_education_form' ){
	
	
							if(page.status == "success") 
							{
								message = page.message || 'Profile Updated';
								
								create_message( message );
		
								ajax_form_init();
								edu_listing_init();
								
							}
							else
							{
								$.each( page.message , function(formelement,errormsg){
								   $("#"+formelement).parent().find(".errormsg").html(errormsg);
								});
							}
							$btn.val( text )
								.removeClass('light_gray')
								.addClass('light_green')
								.removeAttr('disabled');
	
							$('img.spinner').remove();
	
							/*$.get('/profile/' + name, function(data) {
								$(target).replaceWith(data);
	
								create_message( message );
	
								ajax_form_init();
								edu_listing_init();
								
							});*/
							if(page.isload == "yes" && page.loadurl != "")
							{
								$.get(page.loadurl, function(loaddata) {
									$('#load_'+name).html(loaddata);
								});
							}
						} else if(page.issubmit) {
							$("#title_input_hidden").val($("#title_input").val());
							$('#'+page.formname+'').submit();
						} else {
	
							$return_page = $(page);
	
							$error = $return_page.find('#col5 .inner ul');
	
							$('body').append( create_message( $error.html() , { close: true } ) );
	
							$btn.val( text )
								.removeClass('light_gray')
								.addClass('light_green')
								.removeAttr('disabled');
	
							$('img.spinner').remove();
							
	
						}
					}
				})
			}
		});
	}

	// Jquery Dialog box
	function create_message(message, options){

		var options = options || {};
		var close = options.close || false;
		var title = options.title || 'Message';
		var buttons = options.buttons || { Ok: function() { $( this ).dialog( "close" ); } }
		/*
		$close = $('<a/>')
			.html('close')
			.attr('href','javascript: $(\'#full_page\').remove()')
			.css({'position':'absolute','top':'5px','right':'5px'});

		$message_block 	= $('<div/>')
			.attr('id','full_page_message')
			.html( $('<p/>').html( message ) )
			.css({'width':'500px','height':'300px','margin':'200px auto 0 auto', 'background':'gray','position':'relative'});

		if( close ) $message_block.append($close);

		$full_page 	= $('<div/>')	
			.attr('id','full_page')
			.css({'z-index':999,'position':'fixed','top':0,'left':0,'width':'100%','height':'100%','background':'rgba(0,0,0,0.3)'})
			.html( $message_block );

		//return $full_page;
		*/

		$dialog = $('#dialog-message')
					.attr('title',title)
					.html( message )

		$( "#dialog-message" ).dialog({
			modal: true,
			buttons: buttons				
		});

	}

	//change style on click for deleting education
	function edu_listing_init(){
		
		//log( 'edu_listing_init' );

		$('.edu_listing .delete, .edu_listing .edit').unbind('click');

		$('.edu_listing .delete, .edu_listing .edit').click(function(event) {
			
			event.preventDefault();
			
			var target = $(this).parents('.edu_listing');
			var btn = $(this);
			
			if (btn.hasClass('delete')) {
				//clicked delete or yes
				
				if (btn.html() == 'Yes') {
					// run ajax delete

					delete_education_action( btn.attr('href'), target.attr('id').replace('education_','') );

					//window.location = '';
					return false;
				}
				
				btn.html('Yes');
				target.find('.edit').html('No');
				target.addClass('active');
				
			} else {
				//clicked edit or no
				
				if (btn.html() == "Edit") {
					if( ! $('.education_edit', target).size() ){
						edit_education_action( btn.attr('href'), target.attr('id').replace('education_',''));	
						$('.education_edit_cancel').click(function(){log('yay');});
					}
					
					//window.location = btn.attr('href');
					return false;
				}
				else if(btn.html() == "No") {
					if($('.education_edit'))
					{
						$('.education_edit').remove();
					}
				}
							
				btn.html('Edit');
				target.find('.delete').html('Delete');
				target.removeClass('active');
				
			}
			
		});

	}
	
	
function changestage(el)
{
	if(el.value == "Open")
	{
		stagearr = el.name.split("_");
		$("#select_stage").val(stagearr[1]);
	}
	else
	{
		$("#select_stage").val("");
	}
}


function rowtoggle(id)
{
		var $edit_div = $('#'+id).parent().next('div.edit');
		console.log($edit_div);
		$edit_div.slideToggle();

		if( $('#'+id).hasClass('project_row_add')){
		//	log( 'project_row_add' );
			$edit_div.find('.project_new_row').removeAttr('disabled');
		}
}
