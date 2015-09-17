<?php
error_reporting(E_ALL);

//echo `whoami`;

//echo '<pre>'; var_dump( $_SERVER ); exit;
	
 // attempt a connection
// $dbh = pg_connect("host=localhost port=5432 dbname=psql_test user=tester password=p@ssw0rd!");
 
 $dbh = pg_connect("dbname=gvip user=gvip password=yaTZbIRFlIwuO0z2Ct&m");
 
 if (!$dbh) {
     die("Error in connection: " . pg_last_error());
 }       
 


 // execute query
 $sql = "SELECT * FROM migrations";
 $result = pg_query($dbh, $sql);
 if (!$result) {
     die("Error in SQL query: " . pg_last_error());
 }       

 // iterate over result set
 // print each row
 while ($row = pg_fetch_array($result)) {
     echo "migration: " . $row[0] . "<br />";
//      echo "val: " . $row[1] . "<p />";
 }       

 // free memory
 pg_free_result($result);       

 // close connection
 pg_close($dbh);
 
 
 
 phpinfo();       