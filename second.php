<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
	<title>Fantasy</title>

	<!-- css -->
	
	<!-- favicon -->
	<!-- ... -->
 
 
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
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
		geocoder = new google.maps.Geocoder(); // creating a new geocode object
		
		// getting the two address values
		address1 = document.getElementById("address1").value;
		address2 = document.getElementById("address2").value;
		
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
			mapTypeId: google.maps.MapTypeId.HYBRID
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
		var line = new google.maps.Polyline({
			map: map, 
			path: [location1, location2],
			strokeWeight: 7,
			strokeOpacity: 0.8,
			strokeColor: "#FFAA00"
		});
		
		// create the markers for the two locations		
		var marker1 = new google.maps.Marker({
			map: map, 
			position: location1,
			title: "First location"
		});
		var marker2 = new google.maps.Marker({
			map: map, 
			position: location2,
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
		
		document.getElementById("distance_direct").innerHTML = "<br/>The distance between the two points (in a straight line) is: "+d;
	}
	
	function toRad(deg) 
	{
		return deg * Math.PI/180;
	}
</script>

</head>
<body class="page-brand">
	<header class="header header-transparent header-waterfall">
		<ul class="nav nav-list pull-left">
			<li>
				<a data-toggle="menu" href="#doc_menu">
					<span class="icon icon-lg">menu</span>
				</a>
			</li>
		</ul>
		<a class="header-logo margin-left-no" href="login.php">Fantasy</a>
		<ul class="nav nav-list pull-right">
			<li class="dropdown margin-right">
				<a class="dropdown-toggle padding-left-no padding-right-no" data-toggle="dropdown">
					<span class="access-hide">John Smith</span>
					<span class="avatar avatar-sm"><img alt="alt text for John Smith avatar" src="../images/users/avatar-001.jpg"></span>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a class="padding-right-lg waves-attach" href="javascript:void(0)"><span class="icon icon-lg margin-right">account_box</span>Profile Settings</a>
					</li>
					<li>
						<a class="padding-right-lg waves-attach" href="javascript:void(0)"><span class="icon icon-lg margin-right">add_to_photos</span>Upload Photo</a>
					</li>
					<li>
						<a class="padding-right-lg waves-attach" href="page-login.html"><span class="icon icon-lg margin-right">exit_to_app</span>Logout</a>
					</li>
				</ul>
			</li>
		</ul>
	</header>
	<nav aria-hidden="true" class="menu" id="doc_menu" tabindex="-1">
		<div class="menu-scroll">
			<div class="menu-content">
				<a class="menu-logo" href="login.php">Fantasy</a>
				<ul class="nav">
					<li>
						<a class="collaosed waves-attach" data-toggle="collapse" href="#doc_menu_components">Components</a>
						<ul class="collapse menu-collapse" id="doc_menu_components">
							<li>
								<a class="waves-attach" href="ui-button.html">Buttons</a>
							</li>
						
						</ul>
					</li>
					<li>
						<a class="collapsed waves-attach" data-toggle="collapse" href="#doc_menu_extras">Extras</a>
						<ul class="collapse menu-collapse" id="doc_menu_extras">
							<li>
								<a class="waves-attach" href="ui-avatar.html">Avatars</a>
							</li>
							
						</ul>
					</li>
					<li>
						<a class="collapsed waves-attach" data-toggle="collapse" href="#doc_menu_javascript">Javascript</a>
						<ul class="collapse menu-collapse" id="doc_menu_javascript">
							<li>
								<a class="waves-attach" href="ui-affix.html">Affix</a>
							</li>
							
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<main class="content">
		<div class="content-heading">
			<div class="container">
				<div class="row">
				<center><img src="board.png"></center>
					<!--<div class="col-lg-6 col-lg-push-3 col-sm-10 col-sm-push-1">
						<h1 class="heading">Fantasy</h1>
						
					</div>-->
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-lg-push-3 col-sm-10 col-sm-push-1">
					<section class="content-inner margin-top-no">
						<div class="card">
							<div class="card-main">
								<div class="card-inner">
								
						
									<p>The primary focus of the project is to create an online game, which involves a 
								number of users playing in a simulated environment. </p>
									<p>The game will consist of ‘n’ number of users competing against each others on each ‘Game Day’.</p>
									</div>
							</div>
						</div>
						
								<section class="content-inner">
						<div class="card-wrap">
						 <?php
$servername = "localhost";
$username="srikhs1"; // Mysql username 
$password="S!rikhs1234";
$db_name="DBMSDB";


$conn = mysql_connect($servername, $username, $password);
if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}
$sql = 'SELECT Intersection, Latitude, Longitude FROM TABLE1 where Intersection=7001';

mysql_select_db('DBMSDB');
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
{
    echo "ID :{$row['Intersection']}  <br> ".
         "Lat: {$row['Latitude']} <br> ".
         "Long: {$row['Longitude']} <br> ".
         "--------------------------------<br>";
} 
echo "Fetched data successfully\n";
mysql_close($conn);
?>


 
	<div id="form" style="width:100%; height:20%">
		<table align="center" valign="center">
			<tr>
				<td colspan="7" align="center"><b>Find the distance between two locations</b></td>
			</tr>
			<tr>
				<td colspan="7">&nbsp;</td>
			</tr>
			<tr>
				<td>First address:</td>
				<td>&nbsp;</td>
				<td><input type="text" name="address1" id="address1" size="50"/></td>
				<td>&nbsp;</td>
       
				<td>Second address:</td>
				<td>&nbsp;</td>
				<td><input type="text" name="address2" id="address2" size="50"/></td>
        	
        	
			</tr>
			<tr>
				<td colspan="7">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="7" align="center"><input type="button" value="Show" onclick="initialize();"/></td>
			</tr>
		</table>
	</div>
	<center><div style="width:100%; height:10%" id="distance_direct"></div></center>
	<center><div style="width:100%; height:10%" id="distance_road"></div></center>

	<center><div id="map_canvas" style="width:70%; height:54%"></div></center>
						</div>
					
					</section>
					</section>
				</div>
			</div>
		</div>
	</main>
	<footer class="footer">
		<div class="container">
			<p>Saisrikanth Rathakrishnan</p>
		</div>
	</footer>
	<div class="fbtn-container">
		<div class="fbtn-inner">
			<a class="fbtn fbtn-brand-accent fbtn-lg" data-toggle="dropdown"><span class="fbtn-text">Links</span><span class="fbtn-ori icon">apps</span><span class="fbtn-sub icon">close</span></a>
			<div class="fbtn-dropdown">
				<a class="fbtn" href="https://github.com/Daemonite/material" target="_blank"><span class="fbtn-text">Fork me on GitHub</span><span class="icon">code</span></a>
				<a class="fbtn fbtn-brand" href="https://twitter.com/daemonites" target="_blank"><span class="fbtn-text">Follow Daemon on Twitter</span><span class="icon">share</span></a>
				<a class="fbtn fbtn-green" href="http://www.daemon.com.au/" target="_blank"><span class="fbtn-text">Visit Daemon Website</span><span class="icon">link</span></a>
			</div>
		</div>
	</div>

	<!-- js -->

</body>
</html>