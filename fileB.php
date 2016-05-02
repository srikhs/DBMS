<!DOCTYPE html>
<html>
<head>
<style>
table { 
color: #333;
font-family: Helvetica, Arial, sans-serif;
width: 640px; 
border-collapse: 
collapse; border-spacing: 0; 
}

td, th { 
border: 1px solid transparent; /* No more visible border */
height: 30px; 
transition: all 0.3s;  /* Simple transition for hover effect */
}

th {
background: #DFDFDF;  /* Darken header a bit */
font-weight: bold;
}

td {
background: #FAFAFA;
text-align: center;
}
tr:nth-child(even) td { background: #F1F1F1; }   

/* Cells in odd rows (1,3,5...) are another (excludes header cells)  */ 
tr:nth-child(odd) td { background: #FEFEFE; }  

tr td:hover { background: #666; color: #FFF; } /* Hover cell effect! */
</style>




<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=&sensor=false&callback=initialize"></script>
<script type="text/javascript">

	var location1;
	var location2;
	
	var address1;
	var address2;

	var latlng;
	var geocoder;
	var map;
	
	var distance;
	
	// finds the coordinates for the two locations and calls the showMap() function



	function initialize()
	{
 alert("hi");
		geocoder = new google.maps.Geocoder(); // creating a new geocode object
		
		// getting the two address values
		address1 = "37.806249, -122.423884";
		address2 = "37.806249, -122.423884";
		
		// finding out the coordinates
		if (geocoder) 
		{
			geocoder.geocode( { 'address': address1}, function(results, status) 
			{
				if (status == google.maps.GeocoderStatus.OK) 
				{
					//location of first address (latitude + longitude)
					location1 = results[0].geometry.location;
				} else 
				{
					alert("Geocode was not successful for the following reason: " + status);
				}
			});
			geocoder.geocode( { 'address': address2}, function(results, status) 
			{
				if (status == google.maps.GeocoderStatus.OK) 
				{
					//location of second address (latitude + longitude)
					location2 = results[0].geometry.location;
					// calling the showMap() function to create and show the map 
					showMap();
				} else 
				{
					alert("Geocode was not successful for the following reason: " + status);
				}
			});
		}
	}
		
	// creates and shows the map
	function showMap()
	{
		// center of the map (compute the mean value between the two locations)
		latlng = new google.maps.LatLng((location1.lat()+location2.lat())/2,(location1.lng()+location2.lng())/2);
		
		// set map options
			// set zoom level
			// set center
			// map type
		var mapOptions = 
		{
			zoom: 1,
			center: latlng,
      panControl:true,
    zoomControl:true,
    mapTypeControl:true,
    scaleControl:true,
    streetViewControl:true,
    overviewMapControl:true,
    rotateControl:true,    
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		
		// create a new map object
			// set the div id where it will be shown
			// set the map options
		map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
		
		// show route between the points
		directionsService = new google.maps.DirectionsService();
		directionsDisplay = new google.maps.DirectionsRenderer(
		{
			suppressMarkers: true,
			suppressInfoWindows: true
		});
		directionsDisplay.setMap(map);
		var request = {
			origin:location1, 
			destination:location2,
			travelMode: google.maps.DirectionsTravelMode.DRIVING
		};
		directionsService.route(request, function(response, status) 
		{
			if (status == google.maps.DirectionsStatus.OK) 
			{
				directionsDisplay.setDirections(response);
				distance = "The distance between the two points on the chosen route is: "+response.routes[0].legs[0].distance.text;
				distance += "<br/>The aproximative driving time is: "+response.routes[0].legs[0].duration.text;
				document.getElementById("distance_road").innerHTML = distance;
        
			}
		});
		
		// show a line between the two points
	//	var line = new google.maps.Polyline({
		//	map: map, 
		//	path: [location1, location2],
		//	strokeWeight: 7,
		//	strokeOpacity: 0.8,
		//	strokeColor: "#FFAA00"
	//	});
		
		// create the markers for the two locations		
		var marker1 = new google.maps.Marker({
			map: map, 
			position: location1,
			title: "First location"
		});
		var marker2 = new google.maps.Marker({
			map: map, 
			position: location2,
      animation:google.maps.Animation.BOUNCE,
			title: "Second location"
		});
		
		// create the text to be shown in the infowindows
		var text1 = '<div id="content">'+
				'<h1 id="firstHeading">First location</h1>'+
				'<div id="bodyContent">'+
				'<p>Coordinates: '+location1+'</p>'+
				'<p>Address: '+address1+'</p>'+
				'</div>'+
				'</div>';
				
		var text2 = '<div id="content">'+
			'<h1 id="firstHeading">Second location</h1>'+
			'<div id="bodyContent">'+
			'<p>Coordinates: '+location2+'</p>'+
			'<p>Address: '+address2+'</p>'+
			'</div>'+
			'</div>';
		
		// create info boxes for the two markers
		var infowindow1 = new google.maps.InfoWindow({
			content: text1
		});
		var infowindow2 = new google.maps.InfoWindow({
			content: text2
		});

		// add action events so the info windows will be shown when the marker is clicked
	google.maps.event.addListener(marker1, 'click', function() {
		infowindow1.open(map,marker1);
		});
		google.maps.event.addListener(marker2, 'click', function() {
			infowindow2.open(map,marker1);
		});
		
		// compute distance between the two points
		var R = 6371; 
		var dLat = toRad(location2.lat()-location1.lat());
		var dLon = toRad(location2.lng()-location1.lng()); 
		
		var dLat1 = toRad(location1.lat());
		var dLat2 = toRad(location2.lat());
		
		var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
				Math.cos(dLat1) * Math.cos(dLat1) * 
				Math.sin(dLon/2) * Math.sin(dLon/2); 
		var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
		var d = R * c;
		
	//	document.getElementById("distance_direct").innerHTML = "<br/>The distance between the two points (in a straight line) is: "+d;
	}
	
	function toRad(deg) 
	{
		return deg * Math.PI/180;
	}
</script>


</head>
<body>

<?php
$q = floatval($_GET['q']);
$r = floatval($_GET['r']);
echo $q;
echo $r;
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
    

}

?>

<center><div style="width:100%; height:10%" id="distance_road"></div></center>

	<center><div id="map_canvas" style="width:70%; height:54%"></div></center>
</body>

</html>