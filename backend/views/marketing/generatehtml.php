<div class="centercontent">
    
    <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $headertitle; ?></h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
  
        
        <div id="contentwrapper" class="contentwrapper">
			<div class="contenttitle2">
                <h3>HTML</h3>
            </div><!--contenttitle-->

			<?php if (count($errors)) { ?>
			<div class="notibar msgerror">
			    <a class="close"></a>
			    <p>
			    <?php foreach ($errors as $error): ?>
				    <?php echo $error; ?><br>
				<?php endforeach; ?>
				</p>
			</div>
			<?php } ?>

			<form>
				<textarea id="htmloutput" readonly="readonly" rows="25"><?php echo htmlentities($this->load->view('marketing/email_output', '', true)) ?></textarea>
				<br>
				<button class="copybutton" data-copyfrom="htmloutput">Copy</button>
			</form>
			
			<iframe srcdoc="<?php echo htmlspecialchars($this->load->view('marketing/email_output', '', true)) ?>" style="width: 85%; height: 500px; margin-top: 20px;"></iframe>

		</div><!--contentwrapper-->
        
    </div>
</div>