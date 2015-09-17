	// Form Validation
	jQuery('#add_member_form').validate({
		rules: {
			member_first_name: 'required',
			//member_last_name: 'required',
			email: {required: true, email: true},
			member_organization: 'required',
			register_password: {required: true,minlength: 6,maxlength: 32},
			agree: 'required'
        },
		messages: {
			member_first_name: "First Name is Required.",
			//member_last_name: "Last Name is Required.",
			email: {required:'Email is Required.', email:'Email is Not valid.'},
			member_organization: "Organization Name is Required",
			register_password: {required: "Password is Required",minlength: "Password must be at least 6 characters."},
			agree: "You must agree to the terms and conditions."
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.parent().find(".errormsg") );
		}
	});
