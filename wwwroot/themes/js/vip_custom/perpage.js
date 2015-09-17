///// SHOW/HIDE USERDATA WHEN USERINFO IS CLICKED ///// 
var hosturl = location.protocol+'//'+location.hostname;

function edit_myeducation(uid,entry_id,loadtype)
{
	var url = hosturl+'/admin.php/myaccount/form_load/'+loadtype+'/'+uid+'/'+entry_id;
	
	jQuery.ajax({
		url: url,
		type: "GET",
		data: {entry_id : entry_id},
		dataType: "html",
		success: function(data) {
			//log( data );
			jQuery('#education_add').html(data);
			validation();
		}
	});
	
}


function edit_sector(uid,entry_id,loadtype)
{

	var url = hosturl+'/admin.php/myaccount/form_load/'+loadtype+'/'+uid+'/'+entry_id;
	
	jQuery.ajax({
		url: url,
		type: "GET",
		data: {entry_id : entry_id},
		dataType: "html",
		success: function(data) {
			//log( data );
			jQuery('#sector_add').html(data);
			validation();
			//jQuery('#project_sector_main').bind('change',function(){sectorbind(uid,entry_id);});
		}
	});
}

function sectorbind(userid)
{

		 selectedid = jQuery('#project_sector_main').find('option:selected').attr('class').replace('sector_main_','');

		var link = hosturl+'/admin.php/myaccount/form_load/get_subsector_ddl/'+userid+'/'+selectedid;
		jQuery('#dynamicSubsector').load(link);
}


function sectorbind_proj(userid)
{
		 selectedid = jQuery('#project_sector_main').find('option:selected').attr('class').replace('sector_main_','');

		var link = hosturl+'/admin.php/projects/form_load/get_subsector_proj_ddl/'+userid+'/'+selectedid;
		jQuery('#dynamicSubsector').load(link);
}



function load_project_edit_from(slug,entry_id,loadtype,resultdiv)
{
	var url = hosturl+'/admin.php/projects/form_load/'+loadtype+'/'+slug+'/'+entry_id;
	
	jQuery.ajax({
		url: url,
		type: "GET",
		data: {entry_id : entry_id},
		dataType: "html",
		success: function(data) {
			//log( data );
			jQuery('#'+resultdiv).html(data);
			change_filename();
			validation();
			ajax_add_form_init();
			//jQuery('#project_sector_main').bind('change',function(){sectorbind(uid,entry_id);});
		}
	});
}


function ajax_add_form_init() {

		jQuery('form.ajax_add_form').unbind('submit');

		// bind submit handler to form
		jQuery('form.ajax_add_form').submit(function(e) {
			// prevent native submit
			e.preventDefault(); 
			
			if(jQuery(this).validate().form() == true)
			{
				var name = jQuery(this).attr('id');
				var target = '#' + name.substr(1, name.length);
				
				jQuery(this).ajaxSubmit({
					dataType: "json",
					beforeSubmit: function(){
						//jQuery(this).validate();
					 },
					success: function(response){
						
						if(response.status != "") {

							if( response.dump ){
								log( response.dump );
							}
							
							// Success
						
							if(response.status != "success") 
							{
								jQuery.each(response.message , function(formelement,errormsg){
									
									if(jQuery("#"+name+" #"+formelement).parent().hasClass("uploader")) {
										jQuery("#"+name+" #"+formelement).parent().next(".errormsg").html(errormsg);
									} else {
										jQuery("#"+name+" #"+formelement).next(".errormsg").html(errormsg);
									}
								   
								});
								return false;
							}
							if(response.imgpath && response.imgpath != "")
							{
								jQuery(".uploaded_img").attr("src",response.imgpath);
								jQuery("#photo_filename").val("");
								if(jQuery("#without_photo")) {jQuery("#without_photo").hide();}
								if(jQuery("#with_photo")) {jQuery("#with_photo").show();}
							}
							
							if(response.isreset && response.isreset == 'yes')
							{
								resetForm(jQuery('#'+name));			
							}
							if(response.redirect && response.redirect.length > 0)
							{
								window.location.href=response.redirect;
							}
							if(response.innermsg && response.innermsg != '')
							{
								var mynoti2 = jQuery('#'+name).parent().parent().find('.notibar_add');

								mynoti2.addClass("msg"+response.status+"");
								mynoti2.find("p").text(response.message);
								mynoti2.fadeIn();
								jQuery('html, body').animate({scrollTop: '0px'}, 300);
								jQuery(".errormsg").html("");
								jQuery('errormsg').hide();
								setTimeout(function(){jQuery(".notibar_add").fadeOut();},5000);
							
							}
						
						}
					}
					});
			}

		});

}
function resetForm($form) {
    $form.find('input:text, input:password, input:file, select, textarea').val('');
    $form.find('input:radio, input:checkbox')
         .removeAttr('checked').removeAttr('selected');
}


function change_filename()
{

	jQuery('input[type="file"]').change(function(){
		if( jQuery(this).val() ){
			file_input_name = jQuery(this).attr('name');
			hidden_input_name = file_input_name.substring(0, file_input_name.length - 1) + '_hidden';
			
			jQuery('input[name="' + hidden_input_name + '"]').val('');

		}
	})

	// Matix file replace
	jQuery('#project_form input[type="file"]').change(function(){
		if( jQuery(this).val() ){
			file_input_name = jQuery(this).attr('name');
			hidden_input_name = file_input_name + '_hidden';
			
			jQuery('input[name="' + hidden_input_name + '"]').val(jQuery(this).val());
			
		}
	})
	
}

jQuery(document).ready(function(){
//activate tabs
	// project date pickers
	jQuery('.datepicker_month_year').datepicker({
		beforeShow: function() {
			var date_picker = jQuery('#ui-datepicker-div');
			if( ! date_picker.parent().hasClass('jqui')){
				date_picker.wrap( jQuery('<div/>').addClass('jqui') );
			}
		},
		changeMonth: true, 
		changeYear: true, 
		yearRange: '1950:2500',
		dateFormat: "mm/dd/yy" 
	}).change(function(){
		jQuery('#'+this.id.replace('_picker','')).val( jQuery(this).val() + ' 00:00' );
	}).each(function(){
		 jQuery(this).val( jQuery(this).val().substring(0,10) );
	});
	
	jQuery('#profile_tabs, #project_tabs').tabs();
	change_filename();
	ajax_add_form_init();
});	
	
	
