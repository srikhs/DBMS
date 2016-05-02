<?php
$q = floatval($_GET['q']);
$r = floatval($_GET['r']);

$con = mysqli_connect('localhost','srikhs1','S!rikhs1234','DBMSDB');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($con,"ajax_demo");
$sql1="SELECT * FROM user WHERE id = '".$q."'";
$sql="SELECT Intersection, Latitude, Longitude FROM TABLE1";

$sql2='SELECT j.BlockId,i.Intersection,j.Latitude2,j.Longitude2,j.Count, ( 3959 * ACOS( COS( RADIANS( '.$q.' ) ) * COS( RADIANS( j.Latitude2 ) ) * COS( RADIANS( j.Longitude2 ) - RADIANS( '.$r.' ) ) + SIN( RADIANS( ' .$q.' ) ) * SIN( RADIANS( j.Latitude2 ) ) ) ) AS distance
FROM TABLE1 AS i inner join TABLE2 as j on j.Node1=i.Intersection WHERE j.Count>0 HAVING distance <800
ORDER BY  `distance` ASC LIMIT 5';

$sql3='Select aaT.BID as BID, aaT.Intr as Intersection, (Cnt/(distance*distance)) as gravity,aaT.Cnt as Cnt,aaT.distance as distance,Lat2,Long2 from (SELECT j.BlockId as BID,i.Intersection as Intr,j.Latitude2 as Lat2,j.Longitude2 as Long2,j.Count as Cnt, ( 3959 * ACOS( COS( RADIANS( '.$q.' ) ) * COS( RADIANS( j.Latitude2 ) ) * COS( RADIANS( j.Longitude2 ) - RADIANS( '.$r.' ) ) + SIN( RADIANS( '.$q.' ) ) * SIN( RADIANS( j.Latitude2 ) ) ) ) AS distance
FROM TABLE1 AS i inner join TABLE2 as j on j.Node1=i.Intersection WHERE j.Count>0
ORDER BY  `distance` ASC LIMIT 5) as aaT order BY gravity DESC';
$result = mysqli_query($con,$sql2);

$sql4='Select Count(*),Lat2, Long2 from (Select aaT.BID as BID, aaT.Intr as Intersection, (aaT.Cnt/(aaT.distance*aaT.distance)) as gravity,aaT.Cnt as Cnt,aaT.distance as distance,Lat2,Long2 from (SELECT j.BlockId as BID,i.Intersection as Intr,j.Latitude2 as Lat2,j.Longitude2 as Long2,j.Count as Cnt, ( 6371 * ACOS( COS( RADIANS( '.$q.' ) ) * COS( RADIANS( j.Latitude2 ) ) * COS( RADIANS( j.Longitude2 ) - RADIANS( '.$r.' ) ) + SIN( RADIANS( '.$q.' ) ) * SIN( RADIANS( j.Latitude2 ) ) ) ) AS distance
FROM TABLE1 AS i inner join TABLE2 as j on j.Node1=i.Intersection WHERE j.Count>0 HAVING distance <800
ORDER BY  `distance` ASC LIMIT 5) as aaT order BY gravity DESC) as a';

/*

$result = mysqli_query($con,$sql2);



echo "<table>
<tr>
<th>BID</th>
<th>Intr</th>
<th>Lat</th>
<th>Long</th>
<th>Cnt</th>
<th>Distance</th>

</tr>";
while($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['BlockId'] . "</td>";
    echo "<td>" . $row['Intersection'] . "</td>";
    echo "<td>" . $row['Latitude2'] . "</td>";
    echo "<td>" . $row['Longitude2'] . "</td>";
    echo "<td>" . $row['Count'] . "</td>";
    echo "<td>" . $row['distance'] . "</td>";

    echo "</tr>";
}
echo "</table>";

$result1 = mysqli_query($con,$sql3);

echo "<table>
<tr>
<th>BID</th>
<th>Intersection</th>
<th>gravity</th>
<th>Cnt</th>
<th>Distance</th>
<th>Lat</th>
<th>Long</th>


</tr>";
while($row1 = mysqli_fetch_array($result1)) {
    echo "<tr>";
    echo "<td>" . $row1['BID'] . "</td>";
    echo "<td>" . $row1['Intersection'] . "</td>";
    echo "<td>" . $row1['gravity'] . "</td>";
    echo "<td>" . $row1['Cnt'] . "</td>";
    echo "<td>" . $row1['distance'] . "</td>";
    echo "<td>" . $row1['Lat2'] . "</td>";
    echo "<td>" . $row1['Long2'] . "</td>";

    echo "</tr>";
}
echo "</table>";

*/

 
$result4 = mysqli_query($con,$sql4);




while($row4 = mysqli_fetch_array($result4)) {
    $latitudeValue=$row4['Lat2'];
    $longitudeValue=$row4['Long2'];
    $firstaddress="37.806249, -122.423884";
    $latlongValue=$latitudeValue+","+$longitudeValue;
  
    $str23="37.806249, -122.423884";
    
    echo $latitudeValue;
    echo ",";
    echo $longitudeValue;
    
    
    
$file = 'newfileOld.txt';
// Open the file to get existing content
$current = file_get_contents($file);
// Append a new person to the file
$current .= "\n";
$current .= $latitudeValue;
$current .= "\t";
$current .= $longitudeValue;
file_put_contents($file, $current);

}

?>