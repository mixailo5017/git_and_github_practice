<div class="centercontent">
    
    <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $headertitle; ?></h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
  
        
        <div id="contentwrapper" class="contentwrapper">
			<div class="contenttitle2">
                <h3>HTML</h3>
            </div><!--contenttitle-->

			<?php if ($error) { ?>
			<div class="notibar msgerror">
			    <a class="close"></a>
			    <p><?php echo $error; ?></p>
			</div>
			<?php } ?>

			<form>
				<textarea id="htmloutput" readonly="readonly">Here is some HTML</textarea>
				<br>
				<button class="copybutton" data-copyfrom="htmloutput">Copy</button>
			</form>
			

		</div><!--contentwrapper-->
        
    </div>
</div>