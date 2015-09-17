<div class="centercontent">
    
    <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $headertitle; ?></h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
  
        
        <div id="contentwrapper" class="contentwrapper">
			<div class="contenttitle2">
                <h3>Visits</h3>
            </div>
            <?php 
            	$startdate 	= date("Y-m-d",strtotime("-1 month"));
            	$enddate = date("Y-m-d");
            ?>
            <!-- Add Google Analytics authorization button -->
			  <button id="authorize-button" style="visibility: hidden">
			        Authorize Analytics</button>
			
			  <!-- Div element where the Line Chart will be placed -->
			  <div id='last_visits'></div>
			  
			  <!-- Div element where the Line Chart will be placed -->
			  <div class="contenttitle2">
	          	<h3>Page Views</h3>
	          </div>
			  <div id='last_pageviews'></div>
			  
			  <!-- Div element where the Line Chart will be placed -->
			  <div class="contenttitle2">
	          	<h3>Sources</h3>
	          </div>
			  <div id='last_sources'></div>
			
			  <!-- Load all Google JS libraries -->
			  <script src="https://www.google.com/jsapi"></script>
			  <script src="/themes/js/vip_custom/gadash-1.0.js"></script>
			  <script src="https://apis.google.com/js/client.js?onload=gadashInit"></script>
			  <script>
			    // Configure these parameters before you start.
			    var API_KEY = '<?php echo $setting["api_key"]; ?>';
			    var CLIENT_ID = '<?php echo $setting["clientid"]; ?>';
			    var TABLE_ID = 'ga:<?php echo $setting["profileid"]; ?>';
			    // Format of table ID is ga:xxx where xxx is the profile ID.
			
			    gadash.configKeys({
			      'apiKey': API_KEY,
			      'clientId': CLIENT_ID
			    });
			
			    // Create a new Chart that queries visitors for the last 30 days and plots
			    // visualizes in a line chart.
			    var chart1 = new gadash.Chart({
			      'type': 'ColumnChart',
			      'divContainer': 'last_visits',
			      'query': {
			        'ids': TABLE_ID,
			        'metrics': 'ga:visits,ga:visitors',
			        'dimensions': 'ga:date',
			        'start-date': '<?php echo $startdate; ?>',
    			  	'end-date': '<?php echo $enddate; ?>'
			      },
			      'chartOptions': {
			        height:400,
			        title: 'Visits in Last Month',
			        hAxis: {title:'Date'},
			        vAxis: {title:'Visits'},
			        curveType: 'function'
			      },
			       'onSuccess': function(response){
			      		var totalVisits1 = response.totalsForAllResults['ga:visits'];
			      		var totalVisits2 = response.totalsForAllResults['ga:visitors'];
				      	if(totalVisits1 > 0 || totalVisits2 > 0) {
				      		this.defaultOnSuccess(response);
				      	}else {
				      		document.getElementById("last_visits").innerHTML = "No Visits found to display.";
				      	}
	
			      },
			      'onError': function(message) {
			      	
			      }
			    }).render();
			    
			    var chart2 = new gadash.Chart({
			      'type': 'Table',
			      'divContainer': 'last_pageviews',
			      'query': {
			        'ids': TABLE_ID,
			        'metrics': 'ga:visits,ga:visitBounceRate,ga:avgTimeOnPage',
			        'dimensions': 'ga:pagePath',
			        'start-date': '<?php echo $startdate; ?>',
    			  	'end-date': '<?php echo $enddate; ?>'
			      },
			      'chartOptions': {
			        height:300,
			        title: 'Pageviews in Last Month',
			        curveType: 'function'
			      },
			      'onSuccess': function(response){
			      	var totalVisits = response.totalsForAllResults['ga:visits'];
			      	if(totalVisits > 0) {
			      		this.defaultOnSuccess(response);
			      	}else {
			      		document.getElementById("last_pageviews").innerHTML = "No Pages found to display.";
			      	}
			      },
			      'onError': function(message) {
			      	
			      }
			    }).render();
			    
			    var chart2 = new gadash.Chart({
			      'type': 'Table',
			      'divContainer': 'last_sources',
			      'query': {
			        'ids': TABLE_ID,
			        'metrics': 'ga:visits,ga:visitBounceRate,ga:avgTimeOnPage',
			        'dimensions': 'ga:source',
			        'start-date': '<?php echo $startdate; ?>',
    			  	'end-date': '<?php echo $enddate; ?>'
			      },
			      'chartOptions': {
			        height:300,
			        title: 'Pageviews in Last Month',
			        curveType: 'function'
			      },
			      'onSuccess': function(response){
			      	var totalVisits = response.totalsForAllResults['ga:visits'];
			      	if(totalVisits > 0) {
			      		this.defaultOnSuccess(response);
			      	}else {
			      		document.getElementById("last_sources").innerHTML = "No Sources found to display.";
			      	}
			      },
			      'onError': function(message) {
			      	
			      }
			    }).render();

			    
			  </script>
          

        </div><!--contentwrapper-->
        
	</div>