/*
 * 	Additional function for widgets.html
 *	Written by ThemePixels	
 *	http://themepixels.com/
 *
 *	Copyright (c) 2012 ThemePixels (http://themepixels.com)
 *	
 *	Built for Amanda Premium Responsive Admin Template
 *  http://themeforest.net/category/site-templates/admin-templates
 */

jQuery(document).ready(function(){
	
	//jQuery('#licensestart').datepicker();
	//jQuery('#licenseend').datepicker();
	
	
        
       // jQuery("#licensestart").datepicker('setDate', new Date());
        jQuery("#licenseend").datepicker();
         
      


       jQuery("#licensestart").datepicker().bind("change",function(){
            var minValue = jQuery(this).val();
      		minValue = jQuery.datepicker.parseDate("mm/dd/yy", minValue);
	        minValue.setFullYear(minValue.getFullYear() + 1);
			jQuery("#licenseend").datepicker('setDate', minValue );
		        
        })
        
		
    

});
