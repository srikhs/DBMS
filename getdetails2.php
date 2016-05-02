
<?php
$q = $_GET['q'];
$r = $_GET['r'];

$con = mysqli_connect('localhost','srikhs1','S!rikhs1234','DBMSDB');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($con,"ajax_demo");

$sql="SELECT BlockId FROM TABLE2 where Latitude2='".$q."' and Longitude2='".$r."'";



$result4 = mysqli_query($con,$sql);






while($row4 = mysqli_fetch_array($result4)) {

    $latitudeValue=$row4['BlockId'];

    echo $latitudeValue; 
    
    $file = 'newfile.txt';
// Open the file to get existing content
$current = file_get_contents($file);
// Append a new person to the file
$current .= "\n";
$current .= $latitudeValue;


// Write the contents back to the file
file_put_contents($file, $current);
   }
  
   
$sqlrun="SELECT Count from TABLE2 where BlockId='".$latitudeValue."'";

$result5 = mysqli_query($con,$sqlrun);
 while($row5 = mysqli_fetch_array($result5)) {
 
 
$minus= $row5['Count'];
//echo $minus;
$minus=$minus-1;

$file = 'newfile.txt';
// Open the file to get existing content
$current = file_get_contents($file);
// Append a new person to the file
$current .= "\n";
$current .= $minus;


// Write the contents back to the file
file_put_contents($file, $current);
}

$sqlrun2="UPDATE TABLE2 SET Count='".$minus."'WHERE BlockId='".$latitudeValue."'";
$result6 = mysqli_query($con,$sqlrun2);
while($row6 = mysqli_fetch_array($result6)) {


$file = 'newfile.txt';
// Open the file to get existing content
$current = file_get_contents($file);
// Append a new person to the file
$current .= "\n";
$current .= $minus;


// Write the contents back to the file
file_put_contents($file, $current);
}


?>
