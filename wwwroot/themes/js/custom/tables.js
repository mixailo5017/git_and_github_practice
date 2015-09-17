/*
 * 	Additional function for tables.html
 *	Written by ThemePixels	
 *	http://themepixels.com/
 *
 *	Copyright (c) 2012 ThemePixels (http://themepixels.com)
 *	
 *	Built for Amanda Premium Responsive Admin Template
 *  http://themeforest.net/category/site-templates/admin-templates
 */

function ucfirst(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}

var GVIP = GVIP || {};
GVIP.Admin = GVIP.Admin || {};

GVIP.Admin.memberDeleteRestoreCallback = function ($this, action, id, data) {
        var newAction = (action == "restore") ? "delete" : "restore",
            newStatus = (action == "restore") ? "Active" : "Inactive";

        $this.attr("data-action", newAction); // change action
        $this.text(ucfirst(newAction)); // change link's title
        $this.parent().parent().find(".status").html(newStatus); // change status

        var $access = $this.parent().parent().find(".access a");
        if ($access.length > 0) {
            $access.toggle();
        }
};

GVIP.Admin.updateDeleteRestoreCallback = function ($this, action, id, data) {
    var newAction = (action == "restore") ? "delete" : "restore",
        newStatus = (action == "restore") ? "Active" : "Inactive";

    $this.attr("data-action", newAction); // change action
    $this.text(ucfirst(newAction)); // change link's title
    $this.parent().parent().find(".status").html(newStatus); // change status
};

GVIP.Admin.discussionMemberDeleteRestoreCallback = function ($this, action, id, data) {
    var newAction = (action == "allow") ? "deny" : "allow",
        newStatus = (action == "allow") ? "Allowed" : "Denied";

    $this.attr("data-action", newAction); // change action
    $this.text(ucfirst(newAction)); // change link's title
    $this.parent().parent().find(".status").html(newStatus); // change status
};

jQuery(document).ready(function(){

	jQuery('.stdtable .checkall').click(function() {
		var parentTable = jQuery(this).parents('table');										   
		var ch = parentTable.find('tbody input[type=checkbox]');										 
		if(jQuery(this).is(':checked')) {
		
			//check all rows in table
			ch.each(function(){ 
				jQuery(this).attr('checked',true);
				jQuery(this).parent().addClass('checked');	//used for the custom checkbox style
				jQuery(this).parents('tr').addClass('selected');
			});
						
			//check both table header and footer
			parentTable.find('.checkall').each(function(){ jQuery(this).attr('checked',true); });
		
		} else {
			
			//uncheck all rows in table
			ch.each(function(){ 
				jQuery(this).attr('checked',false); 
				jQuery(this).parent().removeClass('checked');	//used for the custom checkbox style
				jQuery(this).parents('tr').removeClass('selected');
			});	
			
			//uncheck both table header and footer
			parentTable.find('.checkall').each(function(){ jQuery(this).attr('checked',false); });
		}
	});
	
	
	///// PERFORMS CHECK/UNCHECK BOX /////
	jQuery('.stdtable tbody input[type=checkbox]').click(function(){
		if(jQuery(this).is(':checked')) {
			jQuery(this).parents('tr').addClass('selected');	
		} else {
			jQuery(this).parents('tr').removeClass('selected');
		}
	});

    // A helper function to deal with notification bar
    function notify($context, message, type, timeout) {
        // Find notification bar
        //var notibar = jQuery(context).parent().parent().find('.notibar');
        var notibar = $context.parents().siblings(".notibar").first();
        if (! notibar) {
            return;
        }
        // Set default message type to msginfo
        type = typeof type !== 'undefined' ? type : 'msginfo';

        // Remove class for all known message types
        var types = ["msginfo", "msgalert", "msgsuccess", "msgerror"];
        for (var index = 0; index < types.length; index++) {
            notibar.removeClass(types[index]);
        }
        // And add the class we need
        notibar.addClass(type);

        // If the message has not been set hide the notification bar
        if (! message) {
            notibar.find("p").text("");
            notibar.fadeOut();
            return;
        }
        notibar.find("p").text(message);
        // Show the notification bar
        notibar.fadeIn();
        // If timeout value is set and is greater than zero
        // then set a timer to automaticaly hide the notification bar
        if (timeout && timeout > 0) {
            setTimeout(function() {
                notibar.fadeOut();
            }, timeout);
        }

    }

    // A helper function that makes an AJAX call to delete id(s)
    // and remove row(s) from the table
    // Is used by
    function deleteRows($context, url, ids, $elements) {
        jQuery.ajax({
            type: "GET",
            url: url,
            data: { "delids":ids },
            dataType: "json"
        }).done(function( response ) {
            // Remove items from the table
            $elements.each(function() {
                jQuery(this).parents('tr').fadeOut(function() {
                    jQuery(this).remove(); //remove row when animation is finished
                });
            });
            // Show message if received with response
            if (response.status != "") {
                notify($context, response.msg, "msg" + response.msgtype + "", 5000);
            }
        }).fail(function() {
            notify($context, "Error occured while trying to delete selected items.", "msgerror", 0);
        });
    }

    ///// DELETE SELECTED ROW(S) IN THE TABLE /////
	jQuery('.deletebutton').click(function() {
        var $this = jQuery(this);

        // Hide notification bar
        //notify($this);

        var tableName = $this.attr('name');							// get target id of table
		var checkboxes = jQuery('#' + tableName).find('tbody input[type=checkbox]:checked');		//get checked items
        // If no items have been selected show error message and exit function
        if (checkboxes.length == 0) {
            notify($this, "No items have been selected.", "msgalert", 5000);
            return false;
        }
        // Ask for confirmation
        if (! confirm('Continue to delete ' + checkboxes.length + ' items?')) {
            return false;
        }

		// Create an array with ids of selected items
		var ids = new Array();
		checkboxes.each(function() {
            ids.push(jQuery(this).val());
		});
		
        var url = $this.attr('id'); // get ajax call url from id
        if (! url) {
            return false;
        }
        
        deleteRows($this, url.replace("#", ''), ids, checkboxes);

        return false;
	});
	
	///// DELETE AN INDIVIDUAL ROW IN A TABLE /////
	jQuery('.stdtable a.delete').click(function() {

        if (! confirm(confirmMessage)) {
            return false;
        }

        var $this = jQuery(this);

		var ids = new Array();
		ids.push($this.attr('name')); // name attribute of Delete link contains an id of the record to be deleted

		var url =  $this.attr('id');
        if (! url) {
            return false;
        }

        deleteRows($this, url.replace("#", ''), ids, $this);

		return false;
	});


    // Soft delete / restore
    // Can be repurposed to be more generic
    jQuery('.stdtable a.soft_delete').click(function() {
        var $this = jQuery(this),
            action = $this.attr("data-action"),
            actionName = ucfirst(action),
            id = parseInt($this.attr("data-id")),
            url = $this.attr("data-url") + action + "/" + id,
            type = $this.attr("data-type"),
            confirmMessage = "Continue to " + action + "?"; // default message

        if (type == "member" && action == "delete") {
            // Check for projects
            var projectUrl = $this.attr("data-url") + "projects/" + id;
            jQuery.ajax({
                url: projectUrl,
                async: false, // make a syncronous call
                success: function (data) {
                    if (data && data.project_count && data.project_count > 0) {
                        confirmMessage =
                            "The member you're about to delete have " + data.project_count + " projects associated with it." +
                            "They won't be shown on GViP if you delete the member without reassigning them.\n\n" +
                            confirmMessage;
                    }
                }
            });

            //confirmMessage = "The member you're about to delete may have projects associated with it.\n" +
            //                 "They will not be shown on GViP if you delete the member without reassigning them.\n\n" +
            //                 confirmMessage;
        }

        if (! confirm(confirmMessage)) {
            return false;
        }

        var postData = {},
            posting = jQuery.post(url, postData, "json");

        posting.done(function(data) {
            if (data && data.status && data.status == "success") {

                var callbackName = type + "DeleteRestoreCallback";
                // Check if a callback function exists and execute it
                if (typeof GVIP.Admin[callbackName] === "function") {
                    GVIP.Admin[callbackName]($this, action, id, data);
                }

                // Display notification if the message has been provided
                if (data.msg && data.msg != "") {
                    notify($this, data.msg, "msg" + data.msgtype + "", 5000);
                }
            }
        }).fail(function() {
            notify($this, "Error occured while trying to " + action + ".", "msgerror", 0);
        }).always(function(e) {
            //
        });

        return false;
    });

    ///// GET DATA FROM THE SERVER AND INJECT IT RIGHT NEXT TO THE ROW SELECTED /////
	jQuery('.stdtable a.toggle').click(function(){
												
		//this is to hide current open quick view in a table 
		jQuery(this).parents('table').find('tr').each(function(){
			jQuery(this).removeClass('hiderow');
			if(jQuery(this).hasClass('togglerow'))
				jQuery(this).remove();
		});
		
		var parentRow = jQuery(this).parents('tr');
		var numcols = parentRow.find('td').length + 1;				//get the number of columns in a table. Added 1 for new row to be inserted				
		var url = jQuery(this).attr('href');
		
		//this will insert a new row next to this element's row parent
		parentRow.after('<tr class="togglerow"><td colspan="'+numcols+'"><div class="toggledata"></div></td></tr>');
		
		var toggleData = parentRow.next().find('.toggledata');
		
		parentRow.next().hide();
		
		//get data from server
		jQuery.post(url,function(data){
			toggleData.append(data);						//inject data read from server
			parentRow.next().fadeIn();						//show inserted new row
			parentRow.addClass('hiderow');					//hide this row to look like replacing the newly inserted row
			jQuery('input,select').uniform();
		});
				
		return false;
	});
		
		
	///// REMOVE TOGGLED QUICK VIEW WHEN CLICKING SUBMIT/CANCEL BUTTON /////	
	jQuery('.toggledata button.cancel, .toggledata button.submit').live('click',function(){
		jQuery(this).parents('.toggledata').animate({height: 0},200, function(){
			jQuery(this).parents('tr').prev().removeClass('hiderow');															 
			jQuery(this).parents('tr').remove();
		});
		return false;
	});

    // Discussion experts
    var $discussionMembersList = jQuery("#dyntable_discussion_members");
    if ($discussionMembersList.length > 0) {
        var discussionMemberslist = $discussionMembersList.dataTable({
            "sPaginationType": "full_numbers",
            "aaSortingFixed": [[0,'asc']],
            "fnDrawCallback": function(oSettings) {
                jQuery('input:checkbox,input:radio').uniform();
            }
        });

        var $discussionMembersStatusFilter = jQuery("select#discussion_members_status_filter");
        $discussionMembersStatusFilter.change(function() {
            discussionMemberslist.fnFilter(jQuery(this).val(), 5);
        });
        $discussionMembersStatusFilter.val('Allowed').change();
    }

    // Discussions
    var $discussionslist = jQuery("#dyntable_discussions");
    if ($discussionslist.length > 0) {
        var discussionslist = $discussionslist.dataTable({
            "sPaginationType": "full_numbers",
            "aaSortingFixed": [[0,'asc']],
            "fnDrawCallback": function(oSettings) {
                jQuery('input:checkbox,input:radio').uniform();
            }
        });
        discussionslist.fnSetColumnVis(10, false);

        jQuery("select#discussion_project_filter").change(function() {
            discussionslist.fnFilter(jQuery(this).val(), 10);
        });
    }

    // FORUMS filters
    var forumlist = jQuery('#dyntable_forums').dataTable({
        "sPaginationType": "full_numbers",
        "aaSortingFixed": [[0,'asc']],
        "fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
        }
    });

    jQuery('select#forum_category_filter').change( function() { forumlist.fnFilter( jQuery(this).val(), 5 ); } );
    jQuery('select#forum_status_filter').change( function() { forumlist.fnFilter( jQuery(this).val(), 6 ); } );

    // MEMBERS filters
	var memberlist = jQuery('#dyntable2').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
	
	jQuery('select#member_group_filter').change( function() { memberlist.fnFilter( jQuery(this).val() ); } );
	jQuery('select#member_group_status').change( function() { memberlist.fnFilter( jQuery(this).val() ); } );

	var educationlist = jQuery('#dyntable3').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
	
	var projectlist = jQuery('#dyntable_projectlist').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
	
	jQuery('select#tbl_project_sector_main').change( function() { jQuery('select#tbl_project_owner').val('');projectlist.fnFilter( jQuery(this).val() ); } );
	jQuery('select#tbl_project_owner').change( function() {  jQuery('select#tbl_project_sector_main').val('');projectlist.fnFilter( jQuery(this).val() ); } );

	
	var conciergelist = jQuery('#dyntable_concierge_list').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});

	if ( conciergelist.length ) {
		conciergelist.fnFilter( 'unarchived' );
	}

	jQuery('input#show_archived').change( function() {
		if( jQuery(this).is(":checked")) {
			conciergelist.fnFilter( 'archived' ); 
		} else {
			conciergelist.fnFilter( 'unarchived' ); 
		}
	});

	var executivelist = jQuery('#dyntable_executive').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
	
	var organizationlist = jQuery('#dyntable_organization').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
	
	var engineeringlist = jQuery('#dyntable_engineering').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});

	var mappointlist = jQuery('#dyntable_mappoint').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
	
	var designissue = jQuery('#dyntable_design_issue').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
	
	var environmentlist = jQuery('#dyntable_environment').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
	
	var studylist = jQuery('#dyntable_studies').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
	
	var fundsourcelist = jQuery('#dyntable_fundsources').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
	
	var roilist = jQuery('#dyntable_roi').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});

	var criticalparticipant = jQuery('#dyntable_criticalparticipant').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
	
	var criticalregulatory = jQuery('#dyntable_regulatory').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
	
	var publiclist = jQuery('#dyntable_public').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
	
	var politicallist = jQuery('#dyntable_political').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
	
	var companylist = jQuery('#dyntable_company').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});

	var ownerlist = jQuery('#dyntable_owner').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
	
	var machinerylist = jQuery('#dyntable_machinery').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
	
	var keyteclist = jQuery('#dyntable_keytech').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
	
	var keyservices = jQuery('#dyntable_keyservices').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});

	var filelist = jQuery('#dyntable_file').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});

	///// TRANSFORM CHECKBOX AND RADIO BOX USING UNIFORM PLUGIN /////
	jQuery('input:checkbox,input:radio').uniform();
});