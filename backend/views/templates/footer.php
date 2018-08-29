<!-- Load TinyMCE -->
<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=mai7xr5o658n95pijgmghfedm0228bki140we2d11pevudr5"></script> 
<script>
	tinymce.init({
		selector:'textarea.tinymce',

		// General options
		width :	jQuery('textarea.tinymce').data('width') || "900",
        height : jQuery('textarea.tinymce').data('height') || "300",
        branding: false,
        plugins: "code,autolink,lists,image,link,preview,media,paste,fullscreen",
        toolbar: "bold,italic,underline,,bullist,numlist,outdent,indent,justifyleft,justifycenter,justifyright,|,link,unlink,anchor,image,media,|,formatselect,|,code",
        menubar: "edit,view,insert,format",

        // Allow iframe tag
        extended_valid_elements: "iframe[class|src|alt|title|width|height|align|name|frameborder|allowfullscreen]",

        // Apply custom markup for certain formatting buttons
        // Looks like it may just have been copy/pasted from sample code, TODO: check if this can be removed
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
		content_css : "/css/style.css",

		// Keep hidden textarea in sync so jQuery Ajax submission works
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        },
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
