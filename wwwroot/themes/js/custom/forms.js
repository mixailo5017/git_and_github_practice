/*
 * 	Additional function for forms.html
 *	Written by ThemePixels	
 *	http://themepixels.com/
 *
 *	Copyright (c) 2012 ThemePixels (http://themepixels.com)
 *	
 *	Built for Amanda Premium Responsive Admin Template
 *  http://themeforest.net/category/site-templates/admin-templates
 */

jQuery(document).ready(function(){
	
	///// FORM TRANSFORMATION /////
	jQuery('input:checkbox, input:radio, select.uniformselect, input:file').uniform();


	///// DUAL BOX /////
	var db = jQuery('#dualselect').find('.ds_arrow .arrow');	//get arrows of dual select
	var sel1 = jQuery('#dualselect select:first-child');		//get first select element
	var sel2 = jQuery('#dualselect select:last-child');			//get second select element
	
	sel2.empty(); //empty it first from dom.
	
	db.click(function(){
		var t = (jQuery(this).hasClass('ds_prev'))? 0 : 1;	// 0 if arrow prev otherwise arrow next
		if(t) {
			sel1.find('option').each(function(){
				if(jQuery(this).is(':selected')) {
					jQuery(this).attr('selected',false);
					var op = sel2.find('option:first-child');
					sel2.append(jQuery(this));
				}
			});	
		} else {
			sel2.find('option').each(function(){
				if(jQuery(this).is(':selected')) {
					jQuery(this).attr('selected',false);
					sel1.append(jQuery(this));
				}
			});		
		}
	});
	
	
	
	///// FORM VALIDATION /////
	
	
		// Form Validation
	/*jQuery('#add_member_form').validate({
		rules: {
			member_first_name: 'required',
			member_last_name: 'required',
			email: {required: true, email: true},
			member_organization: 'required',
			register_password: {required: true,minlength: 6,maxlength: 32}
        },
		messages: {
			member_first_name: "First Name is Required.",
			member_last_name: "Last Name is Required.",
			email: {required:'Email is Required.', email:'Email is Not valid.'},
			member_organization: "Organization Name is Required",
			register_password: {required: "Password is Required",minlength: "Password must be at least 6 characters."}
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg"));
		}
	});
	
	
	jQuery('#expertise_education_form').validate({
		rules: {
			education_university: { required: true},
			education_degree: { required: true},
			education_major: { required: true}
		},
		messages: {
			education_university: { required: 'University Name field is required.'},
			education_degree: { required: 'Degree field is required.'},
			education_major: { required: 'Major field is required.'}
		
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg") );
		}
	
	});*/
	
	validation();

	
	
	///// TAG INPUT /////
	
	jQuery('#tags').tagsInput();

	
	///// SPINNER /////
	
	jQuery("#spinner").spinner({min: 0, max: 100, increment: 2});
	
	
	///// CHARACTER COUNTER /////
	
	jQuery("#textarea2").charCount({
		allowed: 120,		
		warning: 20,
		counterText: 'Characters left: '	
	});
	
	
	///// SELECT WITH SEARCH /////
	jQuery(".chzn-select").chosen();
	
});


function validation()
{

	jQuery('#add_member_form').validate({
		rules: {
			member_first_name: 'required',
			member_last_name: 'required',
			email: {required: true, email: true},
			member_organization: 'required',
			register_password: {required: true,minlength: 6,maxlength: 32}
        },
		messages: {
			member_first_name: "First Name is Required.",
			member_last_name: "Last Name is Required.",
			email: {required:'Email is Required.', email:'Email is Not valid.'},
			member_organization: "Organization Name is Required",
			register_password: {required: "Password is Required",minlength: "Password must be at least 6 characters."}
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg"));
		}
	});
	
	
	jQuery('#expertise_education_form').validate({
		rules: {
			education_university: { required: true},
			education_degree: { required: true},
			education_major: { required: true}
		},
		messages: {
			education_university: { required: 'University Name field is required.'},
			education_degree: { required: 'Degree field is required.'},
			education_major: { required: 'Major field is required.'}
		
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg") );
		}
	
	});
	
/*	jQuery('#expertise_add_sector_form').validate({
		rules: {
			member_sector: { required: true},
			member_sub_sector: { required: true}
		},
		messages: {
			member_sector: { required: 'Sector field is required.'},
			member_sub_sector: { required: 'Sub Sector field is required.'}
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg") );
		}
	
	});
*/

}