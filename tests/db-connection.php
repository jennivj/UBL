<?php
# !!!!! -----> CONNECTION <--------!!!!!
 $servername = "localhost";
$username = "root";
$password = "";
$dbname = "myblue";

// Create connection
//global $conn ;
  $conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
/*
 
	
*/
###################################################
function getsysVarValues($variableArg ){
global $conn ;
 $sqlVar = "SELECT *  FROM `sysvariables`   WHERE  infoVar ='".$variableArg."' ";
 $resultVar = $conn->query($sqlVar); 
 
    if ($resultVar->num_rows > 0) {
        // output data of each row
         $row = $resultVar->fetch_assoc();
         if( $variableArg == 'TVAintra' ){
         	if (strpos($row['texteVar'], '.') !== false) {
             $row['texteVar'] = str_replace('.', '',  $row['texteVar']);
           }
         }
        return $row['texteVar'];         
    }
}
	?>