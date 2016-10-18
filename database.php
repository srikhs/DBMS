<?php
ini_set( "display_errors", 0);
ob_start();
$host="localhost"; // Host name 
 
 // Table name 

// Connect to server and select databse.
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");
?>
