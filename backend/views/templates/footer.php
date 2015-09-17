<!-- Load TinyMCE -->
<script type="text/javascript" src="/js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : '/js/tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",
            width :	jQuery('textarea.tinymce').data('width') || "900",
            height : jQuery('textarea.tinymce').data('height') || "300",
			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
            // Allow iframe tag
            extended_valid_elements: "iframe[class|src|alt|title|width|height|align|name|frameborder|allowfullscreen]",
			// Theme options
			theme_advanced_buttons1 : "bold,italic,underline,,bullist,numlist,outdent,indent,justifyleft,justifycenter,justifyright,|,link,unlink,anchor,image,media,|,formatselect,|,code",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : false,
			
			 formats : {
                alignleft : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'left'},
                aligncenter : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'center'},
                alignright : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'right'},
                alignfull : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'full'},
                bold : {inline : 'span', 'classes' : 'bold'},
                italic : {inline : 'span', 'classes' : 'italic'},
                underline : {inline : 'span', 'classes' : 'underline', exact : true},
                strikethrough : {inline : 'del'},
                customformat : {inline : 'span', styles : {color : '#00ff00', fontSize : '20px'}, attributes : {title : 'My custom format'}}
      		  },
			// Example content CSS (should be your site CSS)
			content_css : "/css/content.css",
			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js"
		});
	});
</script>

</div><!--bodywrapper-->
<div style="display:none">
<input type="hidden" name="sname" id="sname" value="<?php echo $this->security->get_csrf_token_name()?>" />
<input type="hidden" name="svalue" id="svalue" value="<?php echo $this->security->get_csrf_hash()?>" />
</div>
<script src="/themes/js/vip_custom/plugins.js"></script>
<script src="/themes/js/vip_custom/maps.js"></script>
</body>
</html>
