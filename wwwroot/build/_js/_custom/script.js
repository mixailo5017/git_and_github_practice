var hosturl = location.protocol + '//' + location.hostname;
var GVIP = GVIP || {};
GVIP.App = GVIP.App || {};
GVIP.App.Analytics = GVIP.App.Analytics || {};
GVIP.App.Analytics.context = GVIP.App.Analytics.context || {};

$(window).load(function() {
    var $meter = $('#meter'),
        percent = parseInt($meter.data('value')) / parseInt($meter.data('max')) * 100;
    $meter.find('.progress').css('width', percent + "%");
});

$(function() {

    // Instantiate the accordion on My GViP
    $('#myvip .column_1').accordion({
        header: "h2",
        autoHeight: false,
        collapsible: true,
        icons: {
            "header": "accordion-icon-closed", 
            "headerSelected": "accordion-icon-open" 
        }
    });

    //project description toggle
    $('.project-description').on('click', '.show', function() {
        $(this).siblings('.text-cut').hide();
        $(this).hide();
        $('.project-description .overflow-text').slideToggle();
    });
    $('.project-description').on('click', '.hide', function() {
        $('.project-description .show, .project-description .text-cut').show();
        $('.project-description .overflow-text').slideToggle();
        $("html, body").animate({
            scrollTop: 0
        }, "slow");
    });

    var $meter = $("#meter");
    //progress bar dismiss
    $meter.find("button").click(function() {
        dismissPCI();
        $meter.animate({
            height: 0,
            opacity: 0
        }, "fast", function() {
            $(this).slideUp();
        });
    });

    /*
        if($('html').hasClass("lt-ie9") === true){
           // Append upgrade banner to body
        }
    */
    $('textarea.tinymce').tinymce({
        // Location of TinyMCE script
        script_url: '/js/tiny_mce/tiny_mce.js',

        // General options
        theme: "advanced",
        width: $('textarea.tinymce').data('width') || "900",
        plugins: "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
        // Allow iframe tag
        extended_valid_elements: "iframe[class|src|alt|title|width|height|align|name|frameborder|allowfullscreen]",
        // Theme options
        theme_advanced_buttons1: "bold,italic,underline,,bullist,numlist,outdent,indent,justifyleft,justifycenter,justifyright,|,link,unlink,anchor,image,media,|,formatselect,|,code",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: false,

        formats: {
            alignleft: {
                selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img',
                classes: 'left'
            },
            aligncenter: {
                selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img',
                classes: 'center'
            },
            alignright: {
                selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img',
                classes: 'right'
            },
            alignfull: {
                selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img',
                classes: 'full'
            },
            bold: {
                inline: 'span',
                'classes': 'bold'
            },
            italic: {
                inline: 'span',
                'classes': 'italic'
            },
            underline: {
                inline: 'span',
                'classes': 'underline',
                exact: true
            },
            strikethrough: {
                inline: 'del'
            },
            customformat: {
                inline: 'span',
                styles: {
                    color: '#00ff00',
                    fontSize: '20px'
                },
                attributes: {
                    title: 'My custom format'
                }
            }
        },

        // Example content CSS (should be your site CSS)
        content_css: "/css/content.css",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url: "lists/template_list.js",
        external_link_list_url: "lists/link_list.js",
        external_image_list_url: "lists/image_list.js",
        media_external_list_url: "lists/media_list.js"

    });


    // Prevent clicking on View button when the Case Study has Draft status
    $('.edit_case_studies .edit_portlet .edit_buttons a.edit_button.last').on('click', function(e) {
        var status = $(this).parent().parent().next('.add_case_study').find('.status input[type=radio][value=1]').attr('checked');
        if (status === undefined) {
            alert('A Case Study in Draft status can not be presented in public profile view.');
            e.preventDefault();
        }
    });

    // File uploads
    $('.file_upload input[type="file"]').each(function() {
        $(this).customFileInput();
    });

    // Sector Subsector filters on listviews

    function loadSubsectors($subsectors, sector) {
        $subsectors.html("");
        $subsectors.append("<option value=\"\">" + subsectors["first"] + "</option>");
        $.each(subsectors[sector], function(index, value) {
            $subsectors.append("<option value=\"" + value + "\">" + value + "</option>")
        });
    }

    $('form[name=search_form] select[name=sector]').change(function() {
        var $this = $(this),
            $subsectors = $('form[name=search_form] select[name=subsector]');

        loadSubsectors($subsectors, $this.val());
    });

    //Auto resize textarea for posting an update (comment)
    function autoresize() {
        var txt = $(".post-comment"),
            content = null;

        txt.addClass('txtstuff');

        txt.on('keyup', function() {
            content = $(this).val();
            content = content.replace(/\n/g, '');

            var hiddenDiv = $(this).parent().parent().parent().find('.hiddendiv');
            hiddenDiv.text(content + '');

            $(this).css('height', hiddenDiv.outerHeight());
        });
    }
    autoresize();

    function postUpdate($form, callback) {
        if ($form == undefined || $form.length == 0) {
            return false;
        }

        var url = $form.attr("action"),
            $content = $form.find("textarea.post-comment"),
            $errors = $form.find(".errormsg"),
            content = $content.val(),
            postData = new Object;

        // Clear the errors
        $errors.html("");

        if (!isUpdateContentValid(content)) {
            return false;
        }
        postData.author = $form.find("input[name=author]").val();
        postData.type = $form.find("input[name=type]").val();
        postData.content = cleanUpdateContent(content);

        if ($form.find("input[name=reply_to]")) {
            postData.reply_to = $form.find("input[name=reply_to]").val();
        }

        var posting = $.post(url, postData, "json");

        posting.done(function(data) {
            if (data.status == "success") {
                $content.val('');
                $content.keyup();
                $form.removeClass('data-submitting-in-progress');
                // Analitics
                if (data.analytics) {
                    segmentAnalytics(data.analytics);
                }
            }
        }).fail(function() {
            $errors.html("<label>Error occurred while trying to post an update.</label>")
                //            alert("Error while trying to post an update");
        }).always(function(e) {
            if (typeof callback == 'function') {
                callback();
            }
        });
    }

    function loadUpdateReplies($update) {
        if ($update == undefined || $update.length == 0) {
            return;
        }

        var $container = $update.find(".additional-comments ul.updates"),
            update_id = $update.attr("data-id"),
            url = $update.attr("data-replies-url") + "/" + update_id;

        var getting = $.get(url, null, "json");

        getting.done(function(data) {
            if (data.status == "success") {
                if (data.updates) {
                    $container.html(data.updates);
                }
                // Show the number of replies
                if (data.update_count) {
                    $update.find(".content .number-of-comments span").html(data.update_count);
                }
                $update.find(".comment").append("<div class='hiddendiv common'>");
                autoresize();
            }
        }).fail(function() {
            alert("Error while trying to get updates.");
        });

    }

    function loadUpdates($form, reload) {
        if ($form == undefined || $form.length == 0) {
            return false;
        }
        // Set default of reload to false
        reload = typeof reload !== 'undefined' ? reload : false;

        var url = $form.attr("action"),
            $container = $form.parents().find("ul.feed.updates"),
            $last = $container.children("li:last-child"),
            $loadMore = $form.find("input[type=submit].view-more"),
            lastId = 0,
            html = "";

        if (!reload && $last != undefined) {
            lastId = $last.attr('data-id') == undefined ? 0 : $last.attr('data-id');
        }

        var getting = $.get(url + "/" + lastId, null, "json");

        getting.done(function(data) {
            if (data.status == "success") {
                if (data.updates) {
                    if (reload) {
                        $container.html(data.updates); // Replace the content
                    } else {
                        $container.append(data.updates); // Append to the end
                    }
                }
                checkPostUpdateState($container.find("form[name=post_update]"));
                if (data.more_count && data.more_count > 0) {
                    $loadMore.show();
                } else {
                    $loadMore.hide();
                }
            }
        }).fail(function() {
            alert("Error while trying to get updates.");
        });
    }

    $(".comment:first").append("<div class='hiddendiv common'>");

    $(".comments").on("click", "li .number-of-comments a", function(e) {
        e.preventDefault();

        var $update = $(this).parents("li").first(),
            $container = $update.find(".additional-comments");

        // Do AJAX request only if there are no replies currently and replies block is invisible
        if ($container.find(".updates").children("li").length == 0 &&
            $container.css("display") == 'none') {
            loadUpdateReplies($update);
        }

        $container.slideToggle();
        return false;
    });

    $("#myvip form[name=updates_view_more]").submit(function(e) {
        e.preventDefault();
        loadUpdates($(this));
        return false;
    });
    // Load initial portion of MyVip updates on the page load
    loadUpdates($("#myvip form[name=updates_view_more]"));

    $("#projects form[name=updates_view_more]").submit(function(e) {
        e.preventDefault();
        loadUpdates($(this));
        return false;
    });
    loadUpdates($("#projects form[name=updates_view_more]"));

    function cleanUpdateContent(content) {
        if (content === undefined) {
            return '';
        }
        // Remove all vertival space and trim leading and trailing spaces
        // IE dosent support String.trim() therefore we use $.trim()
        return $.trim(content.replace(/\v+/g, ''));
    }

    function isUpdateContentValid(content) {
        content = cleanUpdateContent(content);
        var min = 6,
            max = 1024;

        if (content.length < min || content.length > max) {
            return false;
        }

        return true;
    }

    function checkPostUpdateState($form) {

        var $content = $form.find("textarea.post-comment"),
            $submit = $form.find("input[type=submit]");


        if (isUpdateContentValid($content.val()) && $form.hasClass('data-submitting-in-progress') === false) {
            $submit.removeAttr("disabled");

        } else {
            $submit.attr("disabled", "disabled");
        }
    }

    checkPostUpdateState($("form[name=post_update]"));

    $(".comments").on("keydown", "form[name=post_update] textarea.post-comment", function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            if (e.metaKey || e.ctrlKey) {
                var $this = $(this);
                //$this.closest("form[name=post_update]").submit();
                $this.closest("form[name=post_update]").find('input[type="submit"]').trigger('click');
                $this.find("input[type=submit]").attr("disabled", "disabled");
            }
            return false;
        } else {
            $(this).closest('form').removeClass("data-submitting-in-progress");
        }
    });

    $(".comments").on("keyup", "form[name=post_update] textarea.post-comment", function(e) {
        checkPostUpdateState($(this).closest("form[name=post_update]"));
    });

    $(".comments").on("change", "form[name=post_update] textarea.post-comment", function(e) {
        checkPostUpdateState($(this).closest("form[name=post_update]"));
    });

    $(".comments").on("submit", "form[name=post_update]", function(e) {
        e.preventDefault();

        var $form = $(this);

        // Prevent double submissions

        if ($form.hasClass("data-submitting-in-progress") === true) {
            return false;
        }

        $form.addClass("data-submitting-in-progress");
        $form.find("input[type=submit]").attr('disabled', 'disabled');
        postUpdate($form, function() {
            // Disable Post button
            checkPostUpdateState($form);
            // Reload updates/replies
            if ($form.find("input[name=reply_to]").length > 0) {
                var $container = $form.closest(".additional-comments").closest("li");
                loadUpdateReplies($container, true);
                autoresize();
            } else {
                loadUpdates($form.parents().find("form[name=updates_view_more]"), true);
            }
        });

        return false;
    });

    // Follow/Unfollow
    function followUnfollow($form) {
        if ($form == undefined || $form.length == 0) {
            return false;
        }

        var $action = $form.find("input[type=hidden][name=action]"),
            action = $action.val(),
            context = $form.find("input[type=hidden][name=context]").val(),
            $submit = $form.find("a[name=submit] .follow-text"),
            url = hosturl + "/" + context + "/" + action;

        var postData = new Object();
        postData.id = $form.find("input[type=hidden][name=id]").val();
        postData.return_follows = $form.find("input[type=hidden][name=return_follows]").val();

        var posting = $.post(url, postData, "json");

        posting.done(function(data) {
            if (data.status == "success") {
                $submit.text((action == "follow") ? lang["following"] : lang["follow"]);

                var unfollowText = (action == "follow") ? lang["unfollow"] : "";
                $submit.parent().attr("data-unfollow", unfollowText);

                if ($submit.parent().attr("data-unfollow") != "") {
                    $submit.parent().addClass('unfollow').addClass('just_changed').mouseleave(function() {
                        $('.just_changed').removeClass('just_changed');
                    });
                } else {
                    $submit.parent().removeClass('unfollow');
                }

                $action.val((action == "follow") ? "unfollow" : "follow");

                // Analitics
                if (data.analytics) {
                    segmentAnalytics(data.analytics);
                }
            }
        }).fail(function() {
            alert("Error while trying to (un)follow.")
        });
    }

    // Follow/Unfollow button implemented as an anchor. Therefore we need to trigger the submit event for the form
    $("form[name=follow_form] a[name=submit]").click(function(e) {
        e.preventDefault();
        $(this).parents("form[name=follow_form]").submit();
        return false;
    });

    $("form[name=follow_form]").submit(function(e) {
        e.preventDefault();
        followUnfollow($(this));
        return false;
    });

    // Submit the form if sort order has been changed for a list view (Projects, Expertise, Lightning...)
    $("select[name=sort_options]").change(function(e) {
        var $form = $('form[name=search_form]'),
            value = $(this).val();
        if ($form.length > 0) {
            $form.find('input[type=hidden][name=sort]').val(value);
            $form.submit();
        }
    })

    // Submit the form if limit (items per page) option has been changed for a list view (Projects, Expertise, Lightning...)
    $("select[name=limit_options]").change(function(e) {
        var $form = $('form[name=search_form]'),
            value = $(this).val();
        if ($form.length > 0) {
            $form.find('input[type=hidden][name=limit]').val(value);
            $form.submit();
        }
    })

    /* scroll to */
    var scroll_to = $('div.scroll_to');
    if (scroll_to.length) {
        scroll_to.find('a').click(function() {
            var where = $(this).attr('href');
            scrollIt($(where));
            return false;
        });
    }
    /* end scroll to */

    /* Edit Seats & search filter */
    var $seat_portlets = $('.seat_portlets'),
        $search_filter = $('.search_filter');

    if ($seat_portlets.length) {

        var $inviteButton = $('.invite').find('.button'),
            $cancelButton = $('.invite_form').find('a.cancel');

        $inviteButton.click(function() {
            $(this).closest('.invite').hide().next('.invite_form').show();
            return false;
        });

        $cancelButton.click(function() {
            $(this).closest('.invite_form').hide().prev('.invite').show();
            return false;
        });
    }

    if ($search_filter.length) {
        var $sfd = $('.search_filter_drop'),
            $sfh = $('input.search-filter-hidden'),
            $current = $('span.current', $search_filter);

        $sfd.find('a').click(function() {
            var data_filter = $(this).attr('data-filter-value'),
                text = $(this).text();

            $current.text(text);
            $sfh.attr('value', data_filter);
            return false;
        });
    }
    /* end Edit Seats */

    var $tooltip = $(".tooltip");
    if ($tooltip.length > 0) {
        $tooltip.poshytip({
            className: "tip-yellowsimple",
            showTimeout: 1,
            alignTo: "target",
            alignX: "center",
            offsetY: 5,
            allowTipHover: false
        });
    }

    // no entries message on project search
    if (!$('.project_entries .project_listing').size()) {
        $('.project_entries').html('<p>' + lang['SearchNoProject'] + '</p>')
    }


    // USER search form
    $('#projects_search_form, #user_search_form').submit(function() {
        $('select', this).each(function() {
            if ($(this).val() == '') {
                $(this).attr('disabled', 'disabled');
            }
        });
    })

    $(".accordion").accordion({
        autoHeight: false,
        navigation: true,
        change: show_stage_status,
        create: show_stage_status
    });

    function show_stage_status() {
        $('#stage_accordion .ui-accordion-content.ui-accordion-content-active').css('overflow', 'visible');
    }


    // only allow one open Project Stage
    $('.stage_status_select').change(function() {

        if ($('option:selected', this).val() == 'Open') {

            $('#stage_accordion h3.ui-accordion-header a span').remove();

            $(this).parents('div.ui-accordion-content').prev('h3').find('a').append('<span>open</span>');

            $('.stage_status_select').val('Closed');
            $(this).val('Open');
        }

    }).each(function() {
        if ($('option:selected', this).val() == 'Open') {

            $('#stage_accordion h3.ui-accordion-header a span').remove();

            $(this).parents('div.ui-accordion-content').prev('h3').find('a').append('<span>open</span>');

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

    $('#project_sector_main').live("change", function() {

        $('#project_sector_sub').removeAttr('disabled');
        $('#project_sector_sub').focus();

        var thisClass = $(this).find('option:selected').attr('class').replace('sector_main', 'project_sector_sub');
        $('#project_sector_sub option').not('.hardcode').remove()
        $('#project_sector_sub option:first').after($project_sector_sub_holder.filter('.' + thisClass));
        if ($('#selected_sub_sector').length > 0) {
            if ($('#selected_sub_sector').text() == "Other") {
                $('#project_sector_sub').val("Other");
            }
        }
    }).trigger("change");


    // Sub-Sector "Other" Text Input
    // included in profile/_general_info_form to reset
    $('#project_sector_sub').change(function() {
        var $other = $('#project_sector_sub_other');
        if ($('option:selected', this).val() === 'Other') {
            $other.parent().show().end().removeAttr('disabled').focus();
        } else {
            $other.parent().hide().end().val('').attr('disabled', 'disabled');
        }
    }).trigger('change');

    // Financial Structure "Other" Text Input
    $('#project_financial').change(function() {
        var $other = $('#project_fs_other');
        if ($('option:selected', this).val() === 'Other') {
            $other.parent().show().end().removeAttr('disabled').focus();
        } else {
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
    $('#profile_tabs').tabs({
        fx: {
            opacity: 'toggle',
            duration: 100
        },
        select: add_tab_to_submit,
        create: show_tabs
    });

    $('.edit_project').click(function() {

        $("#profile_tabs").tabs({
            selected: 2
        });

    })



    // add_tab_to_submit - append tab hash to return value. This code is run each time someone switches tab
    function add_tab_to_submit(event, ui) {

        $ret.val(ret_val + ui.tab.hash);

    }

    // show tabs after init
    function show_tabs(event, ui) {
        $('#profile_tabs, #project_tabs').fadeIn();
        var tab = $('#profile_tabs .ui-tabs-panel, #project_tabs .ui-tabs-panel').filter(':not(".ui-tabs-hide")').attr('id');
        $ret.val(ret_val + '#' + tab);
    }

    // submit for on update profile button click
    $('.update_project, #update_project').click(function(e) {
        formsubmit = $("#project_name_form").submit();

        e.preventDefault();
    })

    // project member select
    $(".chzn-select").chosen({
        no_results_text: lang['Noresultsmatched']
    });




    // disable the submit function on the create a new project button
    // this will be changed in future releases
    // changed for cancel button click to go on project list page
    //$("#new_project .lmol").click(function(e) {e.preventDefault();});

    //clean up profile_actions portlet
    $('#profile_actions a:last').css('border-bottom', 'none');

    // Submit create profile form from anchor
    $('form#profile_upload_image a#submit_upload').click(function() {
        $('form#profile_upload_image').submit();
    })

    /* Profile Edit Submit Button */

    // Submit update profile from from anchor
    $('a#update_profile').click(function() {
        //$('.ui-tabs-panel:visible form').submit();
        // log($('.ui-tabs-panel:visible form'));
    })

    $('.education_edit .education_edit_cancel').on('click', function() {
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

    $('.matrix_dropdown a.upload_new').click(function(e) {
        e.preventDefault();

        $(this).parent().parent().next().next('div.new_version').slideToggle();

    });

    $('#project_form').submit(function() {

        // disable unused file inputs
        $('input[type="file"]').each(function() {
            //if( ! $(this).val() ){$(this).attr('disabled','disabled');}
            //log( $(this) );
        });

        $('select.chzn-select').each(function() {
            field_name = '#default_' + $(this).attr('name').replace('[]', '');
            $more = $(field_name).val().split('|');
            //log( $field );
            if ($arr = $(this).val()) {
                $(this).val($.unique($arr.concat($more)));
            }
        });
        //return false;
    })

    // Matix file replace
    $('input[type="file"]').change(function() {
        if ($(this).val()) {
            file_input_name = $(this).attr('name');
            hidden_input_name = file_input_name.substring(0, file_input_name.length - 1) + '_hidden';

            $hidden = $('input[name="' + hidden_input_name + '"]').val('');


        }
    })

    // Matix file replace
    $('#project_form input[type="file"]').change(function() {
        if ($(this).val()) {
            file_input_name = $(this).attr('name');
            hidden_input_name = file_input_name + '_hidden';

            $hidden = $('input[name="' + hidden_input_name + '"]').val($(this).val());


        }
    })


    ajax_delete_init();

    $('div.edit input[type="reset"]').click(function(e) {
        e.preventDefault();
        $(this).parent().parent('div.edit').slideToggle();
    })

    // Form Validation
    //$('#member_form').validate({
    //	rules: {
    //		member_first_name: 'required',
    //		member_last_name: 'required',
    //		email: {required: true, email: true},
    //		member_organization: 'required',
    //		register_password: {required: true,minlength: 6,maxlength: 16},
    //       password_confirm: {required: true, minlength: 4,maxlength:16, equalTo: "#register_password" }
    //	},
    //	messages: {
    //		member_first_name: lang['FirstNameReq'],
    //		member_last_name: "Last Name is Required.",
    //		email: {required:lang['EmailReq'], email:lang['EmailNotValid']},
    //		member_organization: lang['OrganizationReq'],
    //		register_password: {required: lang['PasswordReq'],minlength: lang['Passwordatleast']},
    //		password_confirm: {required: lang['ConfPassReq'],minlength: lang['Passwordatleast'], equalTo: lang['EnterSamePass'] }
    //
    //	},
    //	errorPlacement: function(error, element) {
    //		error.appendTo( element.parent().find(".errormsg") );
    //	}
    //});


    // Form Validation
    $('#email_settings_form').validate({
        rules: {
            es_username: {
                required: true,
                email: true
            },
            register_password: {
                required: true,
                minlength: 6,
                maxlength: 16
            },
        },
        messages: {
            es_username: {
                required: lang['EmailReq'],
                email: lang['EmailNotValid']
            },
            es_password: {
                required: lang['PasswordReq'],
                minlength: lang['Passwordatleast']
            },
        },
        errorPlacement: function(error, element) {
            error.appendTo(element.parent().find(".errormsg"));
        }
    });

    // Form Validation
    $('#password_settings_form').validate({
        rules: {
            ps_currentpass: {
                required: true,
                minlength: 6,
                maxlength: 16
            },
            ps_newpassword: {
                required: true,
                minlength: 6,
                maxlength: 16
            },
            ps_confpassword: {
                required: true,
                minlength: 4,
                maxlength: 16,
                equalTo: "#ps_newpassword"
            }
        },
        messages: {
            ps_currentpass: {
                required: lang['PasswordReq'],
                minlength: lang['Passwordatleast']
            },
            ps_newpassword: {
                required: lang['PasswordReq'],
                minlength: lang['Passwordatleast']
            },
            ps_confpassword: {
                required: lang['ConfPassReq'],
                minlength: lang['Passwordatleast'],
                equalTo: lang['EnterSamePass']
            }
        },
        errorPlacement: function(error, element) {
            error.appendTo(element.parent().find(".errormsg"));
        }
    });



    $('#header_login').validate({
        rules: {
            username: {
                required: true,
                email: true
            },
            password: 'required'
        },
        messages: {
            username: {
                required: lang['EmailReq'],
                email: lang['EnterValidEmail']
            },
            password: lang['PasswordReq']
        },
        errorPlacement: function(error, element) {
            $("#u0").show();
            $("#pd0u0").hide();
            //console.log(element.parent());
            //error.appendTo( $("#pd0u0") );
            //alert(element.attr("name"));
            $("." + element.attr("name") + "_errormsg").html(error);
        }
    });

    $('#new_project').validate({
        rules: {
            title: 'required'
        },
        messages: {
            title: lang['TitleReq']
        },
        errorPlacement: function(error, element) {
            error.appendTo(element.parent().find(".errormsg"));
        }

    });

    $('#general_photo_form').validate({
        rules: {
            photo_filename: 'required'
        },
        messages: {
            photo_filename: lang['PhotoReq']
        },
        errorPlacement: function(error, element) {
            error.appendTo(element.parent().find(".errormsg"));
        }

    });


    $('#general_video_form').validate({
        rules: {
            member_video: {
                required: true,
                url: true
            }
        },
        messages: {
            member_video: {
                required: lang['VideoReq'],
                url: lang['VideoUrlNotValid']
            }
        },
        errorPlacement: function(error, element) {
            error.appendTo(element.parent().find(".errormsg"));
        }

    });

    $('#general_info_form').validate({
        rules: {
            member_first_name: {
                required: true
            },
            member_last_name: {
                required: true
            },
            member_title: {
                required: true
            },
            member_organization: {
                required: true
            },
        },
        messages: {
            member_first_name: {
                required: lang['FirstNameReq']
            },
            member_last_name: {
                required: lang['LastNameReq']
            },
            member_title: {
                required: lang['TitleReq']
            },
            member_organization: {
                required: lang['OrganizationReq']
            },

        },
        errorPlacement: function(error, element) {
            error.appendTo(element.parent().find(".errormsg"));
        }

    });


    $('#expertise_education_form').validate({
        rules: {
            education_university: {
                required: true
            },
            education_degree: {
                required: true
            },
            education_major: {
                required: true
            },
        },
        messages: {
            education_university: {
                required: lang['UniversityReq']
            },
            education_degree: {
                required: lang['DegreeReq']
            },
            education_major: {
                required: lang['MajorReq']
            },

        },
        errorPlacement: function(error, element) {
            error.appendTo(element.parent().find(".errormsg"));
        }

    });

    $('#project_name_form').validate({
        rules: {
            title_input: {
                required: true
            },
        },
        messages: {
            title_input: {
                required: '*'
            },
        },
        errorPlacement: function(error, element) {
            error.appendTo(element.parent().find(".errormsg"));
        }
    });

    $('#project_form_main').validate({
        rules: {
            //project_overview: { required: true,maxlength: 200},
            project_overview: {
                required: true
            },
            project_keywords: {
                required: true
            },
            project_country: {
                required: true
            },
            project_location: {
                required: true
            },
            project_sector_main: {
                required: true
            },
            project_sector_sub: {
                required: true
            },
            project_budget_max: {
                digits: true,
                min: 0
            },
            project_financial: {
                required: true
            }
        },
        messages: {
            //project_overview: { required: lang['DescReq'],maxlength: lang['Desc200']},
            project_overview: {
                required: lang['DescReq']
            },
            project_keywords: {
                required: lang['KeywordReq']
            },
            project_country: {
                required: lang['CountryReq']
            },
            project_location: {
                required: lang['LocationReq']
            },
            project_sector_main: {
                required: lang['SectorReq']
            },
            project_sector_sub: {
                required: lang['SubSectorReq']
            },
            //			project_budget_max: { required: lang['BudgetReq']},
            project_financial: {
                required: lang['FinancialReq']
            }

        },
        errorPlacement: function(error, element) {
            error.appendTo(element.parent().find(".errormsg"));
        }
    });

    $("#executive_form").validate({
        rules: {
            project_executives_name: {
                required: true
            },
            project_executives_company: {
                required: true
            },
            project_executives_role: {
                required: true
            },
            project_executives_email: {
                required: true,
                email: true
            },
        },
        messages: {
            project_executives_name: {
                required: lang['NameReq']
            },
            project_executives_company: {
                required: lang['CompanyReq']
            },
            project_executives_role: {
                required: lang['RoleReq']
            },
            project_executives_email: {
                required: lang['EmailReq'],
                email: lang['EmailNotValid']
            },
        },
        errorPlacement: function(error, element) {
            error.appendTo(element.parent().find(".errormsg"));
        }

    });
    $("#map_points_form").validate({
        rules: {
            project_map_points_mapname: {
                required: true
            }
        },
        messages: {
            project_map_points_mapname: {
                required: lang['NameReq']
            }
        },
        errorPlacement: function(error, element) {
            error.appendTo(element.parent().find(".errormsg"));
        }

    });

    $("#forgot_password_form").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
        },
        messages: {
            email: {
                required: lang['EmailReq'],
                email: lang['EmailNotValid']
            }
        },
        errorPlacement: function(error, element) {
            error.appendTo(element.parent().find(".errormsg"));
        }

    });

    $(".projects .more").click(function() {
        $(".hiddenproject").toggle("blind", function() {
            $(".projects .more").find("a").text($(this).is(':visible') ? lang['ShowLess'] : lang['ShowMore']);
        });
    });

    $(".frontfiles_tr").click(function() {
        var filelink = $(this).find('.frontfiles_link').attr('href');
        if (filelink) {
            window.open(filelink);
        }
    });

    // Sub-Sector "Other" Text Input
    // included in profile/_general_info_form to reset

    function member_email_dialog() {
        $("#model_email_div").dialog("open");
    }

    $("#member_send_message").click(function() {
        member_email_dialog();
    });

    $("#project_send_message").click(function() {
        member_email_dialog();
    });

    $("#model_email_div").dialog({
        autoOpen: false,
        width: 400,
        modal: true,
        buttons: {
            "Send Mail": function(data) {
                var recipient = $("#hdn_to").val(),
                    sender = $("#hdn_from").val();

                if (recipient == sender) {
                    cannot_send_message();
                    return false;
                }

                $("#model_email_form").submit();
                $("#model_esubject").val("");
                $("#model_emessage").val("");

                $(this).dialog("close");
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        }
    });

    $("#education_degree").live("change", function() {
        if ($('option:selected', this).val() === 'Other') {
            $(this).parent().parent().next().show().find("input").focus();
        } else {
            $(this).parent().parent().next().hide().find("input").val('');
        }

    });

    // Featured Forum hover effect
    var $featuredForum = $(".featured-forum");
    if ($featuredForum.length) {
        $(".forum-title a, .featured-image", $featuredForum).hover(function() {
            $(this).closest('.featured-forum').addClass('hovered');
        }, function() {
            $(this).closest('.featured-forum').removeClass('hovered');
        });
    }
    // When Register to Attend link is clicked for a forum
    // track this event with Segment Analytics
    var $attendForum = $("a.button.attend");
    if ($attendForum.length) {
        $attendForum.click(function(e) {
            var $this = $(this),
                forumId = $this.attr("data-id"),
                forumName = $this.attr("data-name");
            segmentAnalytics({
                "event": {
                    "name": "Register to Attend Clicked",
                    "properties": {
                        "Forum Id": forumId,
                        "Forum Name": forumName
                    }
                }
            });
        });
    }

    // When a recommendation link is clicked on My GViP,
    // track this event with Segment Analytics
    var $recommendation = $("a.recommendation");
    if ($recommendation.length) {
        $recommendation.click(function(e) {
            var $this = $(this),
                recommendationCategory = $this.data("recommendationCategory"),
                recommendationLocation = $this.data("recommendationLocation"),
                recommendationSection = $this.data("recommendationSection"),
                recommendationTargetId = $this.data("recommendationTargetId"),
                recommendationTargetName = $this.data("recommendationTargetName");
            segmentAnalytics({
                "event": {
                    "name": "Recommendation Clicked",
                    "properties": {
                        "Category"    : recommendationCategory,
                        "Location"    : recommendationLocation,
                        "Section"     : recommendationSection,
                        "Target Id"   : recommendationTargetId,
                        "Target Name" : recommendationTargetName
                    }
                }
            });
        });
    }

    var $ratings = $(".rating-block"),
        ratingScores = [],
        $resVote = $(".voting #responsive-vote"),
        $helpVote = $(".voting #helpful-vote"),
        $knowVote = $(".voting #knowledgeable-vote"),
        $resResult = $("#responsive_rate"),
        $helpResult = $("#helpful_rate"),
        $knowResult = $("#knowledgeable_rate"),
        $expert = $("#expert_rating"),
        $score = $('header h2 .score'),
        $resPoints = $(".results #responsive .score"),
        $helpPoints = $(".results #helpful .score"),
        $knowPoints = $(".results #knowledgeable .score"),
        // Layout
        bg_color = '#bebebe',
        expert_color = '#3ca3dd',
        summary_color = 'black',
        rate_color = '#44a6e3',
        dim = 20;
    // Scoreboard
    var mainScore = 0;
    var helpScore = 0;
    var resScore = 0;
    var knowScore = 0;

    if ($ratings.length > 0) {
        if ($score.html() != "") {
            mainScore = Number($score.html());
            helpScore = Number($helpPoints.html());
            resScore = Number($resPoints.html());
            knowScore = Number($knowPoints.html());
        }
        $(".rating-block form[name=rate_expert_form]").submit(function(e) {

            e.preventDefault();

            var $form = $(this),
                url = $form.attr('action'),
                $ratingBlock = $form.parents(".rating-block"),
                $errors = $ratingBlock.find(".errormsg");

            var posting = $.post(url, $form.serialize(), "json");
            posting.done(function(data) {

                if (data.status == "success") {
                    // Update ratings with new (recalculated) values
                    $ratingBlock.find("header h2 .score").text(data.ratings.overall.toFixed(1));
                    $ratingBlock.find("header h2 .votes").text("(" + data.ratings.unique_count + ")");
                    $ratingBlock.find(".rating-details .results p span").text(data.ratings.unique_count);
                    $ratingBlock.find("#helpful .score").text(data.ratings.helpful.toFixed(1));
                    $ratingBlock.find("#responsive .score").text(data.ratings.responsive.toFixed(1));
                    $ratingBlock.find("#knowledgeable .score").text(data.ratings.knowledgeable.toFixed(1));

                    mainScore = Number(data.ratings.overall);
                    helpScore = Number(data.ratings.helpful);
                    resScore = Number(data.ratings.responsive);
                    knowScore = Number(data.ratings.knowledgeable);

                    // Remove the voting block to prevent subsequent submission
                    setTimeout(function() {
                        $ratingBlock.find(".voting form").remove();
                        $ratingBlock.find(".voting .voting-thankyou").show();
                    }, 100);

                    // Show recalculated averages
                    rateStarz();

                    // Analitics
                    if (data.analytics) segmentAnalytics(data.analytics);
                } else {
                    if (data.error) $errors.text(data.error);
                }
            }).fail(function() {
                $errors.html("Error occurred while trying to post the ratings.")
            }).always(function(e) {});

            return false;
        });

        // jRate
        $(".rating-block header").click(function() {
            expertToggleCheck($(this).closest('.rating-block'));
        });

        function expertToggleCheck($this) {
            if ($(".rating-details", $this).is(':visible')) {
                $(".toggle", $this).addClass('icon-expand-more').removeClass('icon-expand-less');
                //$(".rating-details", $this).slideUp(200);
                $(".rating-details", $this).hide();
                $(".toggle-desc", $this).text(lang["ShowMore"] + "/" + lang["RateExpert"]);
            } else {

                $(".toggle", $this).addClass('icon-expand-less').removeClass('icon-expand-more');
                //$(".rating-details", $this).slideDown(200);
                $(".toggle-desc", $this).text(lang["ShowLess"]);
                $(".rating-details", $this).show();
            }
        }

        function rateStarz() {
            // Reset
            $expert.html("");
            $helpResult.html("");
            $resResult.html("");
            $knowResult.html("");

            // Star Defaults
            $expert.jRate({
                rating: mainScore,
                startColor: expert_color,
                endColor: expert_color,
                backgroundColor: bg_color,
                strokeColor: 'none',
                readOnly: true,
                width: dim,
                height: dim,
            });

            $helpResult.jRate({
                rating: helpScore,
                startColor: summary_color,
                endColor: summary_color,
                backgroundColor: bg_color,
                strokeColor: 'none',
                readOnly: true,
                width: dim,
                height: dim,
            });

            $resResult.jRate({
                rating: resScore,
                startColor: summary_color,
                endColor: summary_color,
                backgroundColor: bg_color,
                strokeColor: 'none',
                readOnly: true,
                width: dim,
                height: dim,
            });

            $knowResult.jRate({
                rating: knowScore,
                startColor: summary_color,
                endColor: summary_color,
                backgroundColor: bg_color,
                strokeColor: 'none',
                readOnly: true,
                width: dim,
                height: dim,
            });
        }

        function voteStarz() {

            $resVote.html("");
            $helpVote.html("");
            $knowVote.html("");

            $helpVote.jRate({
                startColor: rate_color,
                endColor: rate_color,
                backgroundColor: bg_color,
                strokeColor: 'none',
                precision: 0,
                width: dim,
                height: dim,
                onSet: function(rating) {
                    $('input[type="hidden"][name="ratings[1]"]').val(rating);
                }
            });

            $resVote.jRate({

                startColor: rate_color,
                endColor: rate_color,
                backgroundColor: bg_color,
                strokeColor: 'none',
                precision: 0,
                width: dim,
                height: dim,
                onSet: function(rating) {
                    $('input[type="hidden"][name="ratings[2]"]').val(rating);
                }
            });

            $knowVote.jRate({

                startColor: rate_color,
                endColor: rate_color,
                backgroundColor: bg_color,
                strokeColor: 'none',
                precision: 0,
                width: dim,
                height: dim,
                onSet: function(rating) {
                    $('input[type="hidden"][name="ratings[3]"]').val(rating);
                }
            });
        }

        rateStarz();
        if ($('.voting').length) {
            voteStarz();
        }

        function activateExpert() {
            var all = false;
            var $button = $('div.voting .btn');
            $.each($('div.voting input[type="hidden"]'), function() {
                if ($(this).val() === "0") {
                    $button.addClass('inactive');
                    all = false;
                    return false;
                } else {
                    all = true;
                }
            });
            if (all === true) {
                $button.removeClass('inactive');
            }
        }

        $('div.voting .inactive').on('click', function(e) {
            if ($(this).hasClass('inactive')) {
                e.preventDefault();
            }
        });

        $resVote.on('click', activateExpert);
        $helpVote.on('click', activateExpert);
        $knowVote.on('click', activateExpert);
    }

    if ($('.main-header .nav-main')) {
        var docWidth = $(window).width(),
            userTimer,
            mobileMenuState = 'closed',
            $wrapper = $('.wrapper'),
            $mainHeader = $('.main-header'),
            $userPro = $('.user-profile'),
            $userMenu = $('.user-menu'),
            $mainMenu = $('.iicon-menu'),
            $intMenu = $('.nav-main'),
            $langIcon = $('.active-language'),
            $activeLang = $('.m-language .active img').attr('src');

        $langIcon.attr('src', $activeLang);
    }
    /*
        $(window).resize(function() {
            rateStarz();
        });
    */
});

global.checkLength = function(o, n, min, max) {
    if (o.val().length > max || o.val().length < min) {
        o.addClass("ui-state-error");
        updateTips("Length of " + n + " must be between " +
            min + " and " + max + ".");
        return false;
    } else {
        return true;
    }
}

global.checkRegexp = function(o, regexp, n) {
    if (!(regexp.test(o.val()))) {
        o.addClass("ui-state-error");
        updateTips(n);
        return false;
    } else {
        return true;
    }
}


global.ajax_delete_init = function() {
    // delete matrix row
    $('.matrix_dropdown a.delete').click(function(e) {

        $li_row = $(this).parents('li');
        li_row_id = $li_row.attr('id');
        row_id = $li_row.attr('id').replace('row_id_', '');
        link = $(this).attr('href');

        $message = lang['AreYouSure'];

        var buttons = {
            "Yes": function() {
                delete_maxtrix_action(link, row_id, li_row_id);
                $(this).dialog("close");
            },
            "No": function() {
                $(this).dialog("close");
            }
        }

        create_message($message, {
            buttons: buttons,
            title: lang['Delete']
        });

    });
}

// DELETE MATRIX ROW FUNCTIONS
global.delete_maxtrix_action = function(link, row_id, li_row_id) {

    var new_link = hosturl + '/' + link.replace('#', '') + '/' + row_id;

    $.ajax({
        url: new_link,
        type: "GET",
        data: {},
        dataType: "json",
        success: function(data) {
            if (data.remove) {
                if (data.formname == 'expertise_sector_form') {
                    $('#hdn_expert_sector_number').val(Number($('#hdn_expert_sector_number').val()) - 1);
                    updatePCI();
                }
                $('#' + li_row_id).fadeOut();
            }
        }
    });
}

// DELETE EDUCATION ENTRY FUNCTIONS
global.delete_education_action = function(link, entry_id) {

    var ACT = hosturl + link;

    $.get(link, function(loaddata) {
        if (loaddata.remove) {
            $('#education_' + entry_id).fadeOut();
        }

    });
    // Update curent user's Profile Completeness Index
    updatePCI();
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
global.edit_education_action = function(link, entry_id) {
    //var entry_id = link.replace('/profile/form_load/'+name+'/edit/','');
    var url = 'http://vip.concept.com' + link;

    $.get(link, function(loaddata) {
        if ($('.education_edit')) {
            $('.education_edit').remove();
        }
        $('#education_' + entry_id).append(loaddata);


        ajax_form_init();

    });
}

// Wrapper for ajax form to call on reloads
global.ajax_form_init = function() {

    $('form.ajax_form').unbind('submit');

    // bind submit handler to form
    $('form.ajax_form').submit(function(e) {

        // prevent native submit
        e.preventDefault();


        if ($(this).validate().form() == true) {
            // define spinner
            var $spinner = $('<div/>')
                .html($('<img class="spinner" src="/images/site/loader.gif" alt="spinner" width="34" />'))
                .css({
                    'display': 'inline'
                });

            // prevent multiple submits
            var $btn = $('input[type="submit"]', this);
            var text = $btn.val();

            if ($(this).attr('id') != "comment_form") {
                $btn.val('Please Wait').removeClass('light_green').addClass('light_gray').attr('disabled', true).after($spinner);
            }

            // get form id and div id
            var name = $(this).attr('id');
            var target = '#' + name.substr(1, name.length);

            $(this).ajaxSubmit({
                dataType: "json",
                beforeSubmit: function() {
                    //$(this).validate();
                },
                success: function(page, status) {
                    var message;
                    //$return_page = $(page);
                    //$message = $return_page.find('body#alert_message');
                    $message = page.message;

                    // Success
                    if (page.message || name == 'education_list' || name == 'expertise_education_form') {
                        if (page.status == "success") {
                            // case study
                            if (page.casestudyid) {
                                var element = '#' + name + ' input[name="hdn_casestudyid"]';
                                $(element).val(page.casestudyid);

                                var cancel = '#' + name + ' button[name="case_cancel"]';
                                $(cancel).remove();
                            }

                            $(".errormsg").html("");

                            message = page.message || 'Profile Updated';

                            //after submitting invite form for available seat
                            if (name == 'invite_seats_form') {
                                //$('#invite_seats_form').closest('.invite_form').hide().prev('.invite').show();
                                create_message(message, {
                                    isredirect: true
                                });
                            } else if (page.imgpath && page.imgpath != "") {
                                //create_message( message,{isredirect:true} );

                                $(".uploaded_img").attr("src", page.imgpath);
                                $("#photo_filename").val("");
                                if ($("#without_photo")) {
                                    $("#without_photo").hide();
                                }
                                if ($("#with_photo")) {
                                    $("#with_photo").show();
                                }
                                if (page.headerimgpath && page.headerimgpath != "") {
                                    $("#header_userphoto").attr("src", page.headerimgpath);
                                }
                            } else if (page.isreload == "yes") {
                                create_message(message);
                            } else if (page.isload == "yes" && page.loadurl != "") {
                                if (name == 'expertise_sector_form') {
                                    $('#hdn_expert_sector_number').val(Number($('#hdn_expert_sector_number').val()) + 1);
                                }
                                $.get(page.loadurl, function(loaddata) {
                                    $('#load_' + page.listdiv).html(loaddata);

                                    if ($('#' + page.listdiv).parent().attr('class') == 'edit add_new') {
                                        $('#' + page.listdiv).parent().hide();
                                    }

                                    ajax_form_init();
                                    edu_listing_init();
                                    ajax_delete_init();
                                    // Is nolonger used
                                    //if ($('.comment')) {
                                    //$('.comment').CommentEditor();
                                    //}
                                });
                            } else if (page.isredirect == 'yes') {
                                create_message(message, {
                                    isredirect: true
                                });
                            } else {
                                create_message(message);
                            }

                            //ajax_form_init();
                            //edu_listing_init();
                            //$('.education_edit').remove();

                            // Analytics
                            if (page.analytics) {
                                segmentAnalytics(page.analytics);
                            }

                        } else {
                            //after submitting invite form for available seat
                            if (name == 'invite_seats_form') {
                                if (page.status == 'custom_error') {
                                    create_message(page.message, {
                                        isredirect: true
                                    });
                                } else {
                                    //create_message( page.message );
                                    window.location.href = hosturl + '/profile/edit_seats';
                                }
                            } else {
                                $.each(page.message, function(formelement, errormsg) {
                                    // $("form#"+formelement).parent().find(".errormsg").html(errormsg);
                                    $("#" + name + " #" + formelement).next(".errormsg").html(errormsg);
                                });
                            }
                        }
                        // It looks like it's no longer needed
                        if (name != "comment_form") {
                            $btn.val(text)
                                .removeClass('light_gray')
                                .addClass('light_green')
                                .removeAttr('disabled');
                        }

                        $('img.spinner').remove();

                        /*$.get('/profile/' + name, function(data) {
                        	$(target).replaceWith(data);

                        	create_message( message );

                        	ajax_form_init();
                        	edu_listing_init();

                        });*/


                    } else if (page.issubmit && page.issubmit != '') {
                        $("#title_input_hidden").val($("#title_input").val());
                        //$('#'+page.formname+'').submit();
                        $('.topupdate').submit();
                    } else {

                        $return_page = $(page);

                        $error = $return_page.find('#col5 .inner ul');

                        $('body').append(create_message($error.html(), {
                            close: true
                        }));

                        $btn.val(text)
                            .removeClass('light_gray')
                            .addClass('light_green')
                            .removeAttr('disabled');

                        $('img.spinner').remove();
                    }

                    if (page.isreset && page.isreset == 'yes') {
                        resetForm($('#' + name));
                    }

                    // Trap all updates for member (expert) profile
                    var currentPath = window.location.pathname;
                    if (currentPath.substring(0, 25) == "/profile/account_settings") {
                        updatePCI();
                    }
                }
            })
        }
    });
}

global.segmentAnalytics = function(data) {
    if (data.user_properties) {
        //var userId = parseInt(data.id, 10); // Make sure id that came is of type int
        window.analytics.identify(GVIP.App.Analytics.user_id, data.user_properties, GVIP.App.Analytics.context);
    }

    if (data.event) {
        window.analytics.track(data.event.name, data.event.properties, GVIP.App.Analytics.context);
    }
}

global.dismissPCI = function() {
    var url = hosturl + "/profile/dismiss_pci",
        postData = {
            "dismiss_pci": "dismiss_pci"
        },
        posting = $.post(url, postData, "json");
    //posting.done(function(data) {
    //}).fail(function() {
    //});
}

// Load and update current user's Profile Completeness Index
global.updatePCI = function() {
    var $pci = $("span.profile_edit_pci"),
        url = hosturl + "/profile/pci";

    if ($pci.length == 0) return;

    var getting = $.get(url, null, "json");

    getting.done(function(data) {
        if (data && data.pci && data.pci != "") {
            $pci.text(data.pci);
        }
    }).fail(function() {
        alert("Error while trying to get PCI.");
    });

    // Dismiss PCI meter whenever a user makes any change in the profile
    dismissPCI();
}

// Jquery Dialog box
global.create_message = function(message, options) {

    var options = options || {};
    var close = options.close || false;
    var isredirect = options.isredirect || false;
    var url = $(location).attr('href');
    //var pathname	= url.replace("#","");
    var pathname = url.split("#");
    var title = options.title || 'Message';
    var buttons = options.buttons || {
            Ok: function() {
                if (isredirect) {
                    window.location.href = pathname[0];
                } else {
                    $(this).dialog("close");
                }
            }
        }
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
        .attr('title', title)
        .html(message)

    $("#dialog-message").dialog({
        modal: true,
        buttons: buttons
    });

}

//change style on click for deleting education
global.edu_listing_init = function() {

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

                delete_education_action(btn.attr('href'), target.attr('id').replace('education_', ''));

                //window.location = '';
                return false;
            }

            btn.html('Yes');
            target.find('.edit').html('No');
            target.addClass('active');

        } else {
            //clicked edit or no

            if (btn.html() == "Edit") {
                if (!$('.education_edit', target).size()) {
                    edit_education_action(btn.attr('href'), target.attr('id').replace('education_', ''));
                    $('.education_edit_cancel').click(function() {
                        log('yay');
                    });
                }

                //window.location = btn.attr('href');
                return false;
            } else if (btn.html() == "No") {
                if ($('.education_edit')) {
                    $('.education_edit').hide();
                }
            }

            btn.html('Edit');
            target.find('.delete').html('Delete');
            target.removeClass('active');

        }

    });

}


global.changestage = function(el) {
    if (el.value == "Open") {
        stagearr = el.name.split("_");
        $("#select_stage").val(stagearr[1]);
    } else {
        $("#select_stage").val("");
    }
}


global.rowtoggle = function(id2) {

    var $edit_div = $('#' + id2).parent().next('div.edit');

    $edit_div.slideToggle();

    if ($('#' + id2).hasClass('project_row_add')) {
        //	log( 'project_row_add' );
        $edit_div.find('.project_new_row').removeAttr('disabled');
    }
}

global.edu_rowtoggle = function(id2) {

    var $edit_div = $('#' + id2).parent().next('div.education_edit');

    $edit_div.slideToggle();

    if ($('#' + id2).hasClass('project_row_add')) {
        //	log( 'project_row_add' );
        $edit_div.find('.project_new_row').removeAttr('disabled');
    }
}

global.tabload = function(url) {
    $('#tabContainer').load(url);
}

global.sectorbind = function(secid) {
    selectedid = $('#project_sector_main' + secid).find('option:selected').attr('class').replace('sector_main_', '');

    var link = hosturl + '/profile/form_load/get_subsector_ddl/' + selectedid;

    $('#dynamicSubsector_' + secid).load(link);
}


global.resetForm = function($form) {
    $form.find('input:text, input:password, input:file, select, textarea').val('');
    $form.find('input:radio, input:checkbox')
        .removeAttr('checked').removeAttr('selected');
}

global.show_confirmation = function(confirmid) {
    $('#' + confirmid).parent().parent().addClass('active');

    $('#' + confirmid).parent().find('a.edit').hide();
    $('#' + confirmid).parent().find('a.delete').hide();

    $('.' + confirmid).show();
}

global.reset_confirmation = function(confirmid) {
    $('#' + confirmid).parent().parent().parent().removeClass('active');
    $('#' + confirmid).parent().parent().find('a.edit').show();
    $('#' + confirmid).parent().parent().find('a.delete').show();

    $('#' + confirmid).parent().hide();
}

// resend_invite_seat
global.resend_invite_seat = function(link, entry_id) {
    var ACT = hosturl + link + entry_id;

    $.getJSON(ACT, function(loaddata) {
        create_message(loaddata.message, {
            isredirect: true
        });
    });

}

// resend_invite_seat
global.remove_seat = function(link, entry_id) {
    var ACT = hosturl + link + entry_id;

    $.getJSON(ACT, function(loaddata) {
        create_message(loaddata.message, {
            isredirect: true
        });
        //window.location.href= hosturl + '/profile/edit_seats';
    });
}

// project_executive_other
global.project_executive_other = function(ddlrole) {
    if (ddlrole.value == 'Other') {
        $(ddlrole).parent().next('.role_other').show();
    } else {
        $(ddlrole).parent().next('.role_other').hide();
    }
}


global.edit_case_studies = function(clickid) {
    //alert(clickid);
    $(clickid).closest('.edit_portlet').hide();
    $(clickid).parent().parent().next('.add_case_study').show();

}

global.delete_case_studies = function(link, clickid) {
    if (link.length > 0) {
        var ACT = hosturl + link;
        $.get(link, function(loaddata) {
            if (loaddata.remove) {
                create_message(loaddata.message, {
                    isredirect: true
                });
            }
        });
    }
}

global.accept_projExpadv_req = function(link, ownerid, projid) {
    if (link.length > 0) {
        var ACT = hosturl + link + ownerid + '/' + projid;
        $.get(ACT, function(loaddata) {
            if (loaddata.status) {
                var url = $(location).attr('href');
                //var pathname	= url.replace("#","");

                $("#accept_prj_" + ownerid).css("background-position", "0px -48px");
                $("#cancel_prj_" + ownerid).css("background-position", "0px 0px");
                //window.location.href = url;
            }
        });
    }
}

global.reject_projExpadv_req = function(link, ownerid, projid) {
    if (link.length > 0) {
        var ACT = hosturl + link + ownerid + '/' + projid;
        $.get(ACT, function(loaddata) {
            if (loaddata.status) {
                var url = $(location).attr('href');
                //var pathname	= url.replace("#","");
                $("#accept_prj_" + ownerid).css("background-position", "0px 0px");
                $("#cancel_prj_" + ownerid).css("background-position", "0px -48px");
                //window.location.href = url;
            }
        });
    }
}




global.cancle_case_studies = function(clickid) {
    var formid = clickid.form.id;
    $('#' + formid).parent().prev('.edit_portlet').show();
    $('#' + formid).parent().hide();
    //$(clickid).prev('.edit_portlet').show();
    //$(clickid).parent().parent().next('.add_case_study').show();
}

global.cannot_send_message = function() {
    var modelmsg = lang['CantSend'];
    create_message(modelmsg, {
        isredirect: true
    });
}

global.scrollIt = function(where) {
    $('html,body').animate({
            scrollTop: where.offset().top
        },
        500
    );
}

global.changeLanguage = require('./_changeLanguage.js');

// --------------------------
//		CONCIERGE FORM

var $concierge = $('#concierge'),
    $c_action = $('#c_action'),
    $c_form = $('#c_form'),
    $c_confirmation = $('#c_confirmation'),
    $close_btn = $('.close, .close_btn', $concierge),
    $textbox = $('textarea', $c_form),
    post_link = '/api/search/concierge_question';

$close_btn.click(function() {
    $(this).closest('.view').fadeOut();
    return false;
});

$('.btn, .form-link', $c_action).click(function() {
    $c_action.fadeOut();
    $c_form.fadeIn();
    return false;
});

$textbox.keyup(function() {
    var $btn = $('.btn', $c_form),
        text = $textbox.val();
    if (text.length > 0) {
        $btn.removeClass('light_gray').addClass('light_orange');
    } else {
        $btn.removeClass('light_orange').addClass('light_gray');
    }
});

$('.btn', $c_form).click(function() {
    var text = $textbox.val();
    if (text == '') {
        //$textbox.attr('placeholder','Please add your question.');
        $textbox.addClass('error');
    } else {
        var req = $.ajax({
            url: post_link,
            type: "GET",
            data: {
                'message': text
            },
            dataType: "json"
        });

        req.done(function(data) {

            if (data.status == 'success') {
                $c_form.fadeOut();
                $c_confirmation.fadeIn();
            }
            if (data.status == 'error') {
                alert(data.message);
            }
        });

        req.fail(function(x, y, z) {
            alert('error');
        });

        req.always(function(x, y, z) {});

    }
    return false;
});