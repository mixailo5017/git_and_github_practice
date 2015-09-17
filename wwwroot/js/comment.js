$.fn.CommentEditor = function(options) {

	var OPT;
	
	var hosturl = location.protocol+'//'+location.hostname;
		
	OPT = $.extend({
		url: hosturl+"/forum/comment",
		comment_body: '.comment_body',
		showEditor: '.edit_link',
		hideEditor: '.cancel_edit',
		saveComment: '.submit_edit',
		closeComment: '.mod_link'
	}, options);
		
	var view_elements = [OPT.comment_body, OPT.showEditor, OPT.closeComment].join(','),
		edit_elements = '.editCommentBox', 
		hash = $("#comment_form input[type=hidden]").val();
		
		
	return this.each(function() {
		var id = this.id.replace('comment_', ''),
		parent = $(this);
			
		parent.find(OPT.showEditor).click(function() { showEditor(id); return false; });
		parent.find(OPT.hideEditor).click(function() { hideEditor(id); return false; });
		parent.find(OPT.saveComment).click(function() { saveComment(id); return false; });
		parent.find(OPT.closeComment).click(function() { closeComment(id); return false; });
	});

	function showEditor(id) {
		$("#comment_"+id)
			.find(view_elements).hide().end()
			.find(edit_elements).show().end();
	}

	function hideEditor(id) {
		$("#comment_"+id)
			.find(view_elements).show().end()
			.find(edit_elements).hide();
	}

	function closeComment(id) {
		
		var data = {status: "close", comment_id: id, csrf_vip: hash};

		$.post(OPT.url, data, function (res) {
			if (res.error) {
				return $.error('Could not moderate comment.');
			}
			
			$('#comment_' + id).hide();
	   });
	}

	function saveComment(id) {
		var content = $("#comment_"+id).find('.editCommentBox'+' textarea').val(),
			data = {status: "update", comment: content, comment_id: id, csrf_vip: hash};
		
	$.post(OPT.url, data, function (res) {
			if (res.error) {
				return $.error('Could not save comment.');
			}

			$("#comment_"+id).find('.comment_body p').html(res.comment);
			hideEditor(id);
   		});
	}
};
	

$(function() { $('.comment').CommentEditor(); });
