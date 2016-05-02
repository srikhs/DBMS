<html>
<head>
<title>Distance finder</title>
<meta type="description" content="Find the distance between two places on the map and the shortest route."/>
<style type="text/css">
 <style>
      #right-panel {
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }

      #right-panel select, #right-panel input {
        font-size: 15px;
      }

      #right-panel select {
        width: 100%;
      }

      #right-panel i {
        font-size: 12px;
      }
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
        float: left;
        width: 63%;
        height: 100%;
      }
      #right-panel {
        float: right;
        width: 34%;
        height: 100%;
      }
      .panel {
        height: 100%;
        overflow: auto;
      }
    </style>
</style>


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

 function showUser(str) {
 
    if (str == "") {
   
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
    var res = str.split(",");
  //  var value1= res[0].replace(".","&#46;");
    //var value2= res[1].replace(".","&#46;");
    
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
           //    document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
  //alert(xmlhttp.responseText);
              
                initialize(xmlhttp.responseText);
                
            }
        };
       // alert("getdetails.php?q="+res[0]+"&r="+res[1]);
        xmlhttp.open("GET","getdetails.php?q="+res[0]+"&r="+res[1],true);
        xmlhttp.send();
        
        
    }
}



	function initialize(strvalue)
	{

		geocoder = new google.maps.Geocoder(); // creating a new geocode object
		
		// getting the two address values
		address1 = document.getElementById("address1").value;
		address2 = strvalue;
		
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
       // alert(directionsDisplay.setDirections(response));
				distance = "Distance: "+response.routes[0].legs[0].distance.text;
				distance += "<br/>Driving Time: "+response.routes[0].legs[0].duration.text;
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
 
 
 
 
 
       function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: latlng  // Australia.
        });

        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer({
          draggable: true,
          map: map,
          panel: document.getElementById('right-panel')
        });

        directionsDisplay.addListener('directions_changed', function() {
          computeTotalDistance(directionsDisplay.getDirections());
        });

        displayRoute(address1, address2, directionsService,
            directionsDisplay);
      }

      function displayRoute(origin, destination, service, display) {
        service.route({
          origin: origin,
          destination: destination,
          waypoints: [],
          travelMode: google.maps.TravelMode.DRIVING,
          avoidTolls: true
        }, function(response, status) {
          if (status === google.maps.DirectionsStatus.OK) {
            display.setDirections(response);
          } else {
            alert('Could not display directions due to: ' + status);
          }
        });
      }

      function computeTotalDistance(result) {
        var total = 0;
        var myroute = result.routes[0];
        for (var i = 0; i < myroute.legs.length; i++) {
          total += myroute.legs[i].distance.value;
        }
        total = total / 1000;
        document.getElementById('total').innerHTML = total + ' km';
      }

</script>


</head>

<body>

<!--<h1>Parking Guesstimator</h1>-->
<center>
<div class="header-cont">
    <div>
    <div id="form" style="width:100%; height:20%">
		
			Current Location:
			
				<input type="text" name="address1" id="address1" size="50"/>
		&nbsp;
			Destination Location:
			&nbsp;
			<input type="text" name="address2" id="address2" size="50"/>
      &nbsp;
     <input type="button" value="Show" onclick="showUser(document.getElementById('address2').value);"/>
	</div>
    </div>
</div>
	
</center>
 <br><br>
 <div id="txtHint">


 </div>
 
<!--	<center><div style="width:100%; height:10%" id="distance_direct"></div></center>  
	<center><div style="width:100%; height:10%" id="distance_road"></div></center>

	<center><div id="map_canvas" style="width:100%; height:100%"></div></center>-->
</body>
</html>










  <div id="map"></div>
    <div id="right-panel">
      <p>Total Distance: <span id="total"></span></p>
    </div>
    <script>
      
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD6gWfp48__yXk3uPoTt3XLow5C6ZhqohI&callback=initMap">
    </script>
  </body>