<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link rel = "stylesheet" href = "../main/stylemain.css" type = "text/css"/>
<title>All - Transit Follow Up Tool</title>
<?php require_once('../main/title.html'); ?>
<h3>All</h3>
<?php require_once('../main/navtop.html'); ?>
<?php require_once('../main/functions.php'); ?>
<style>
table {
    width: 100%;
	text-align: center;
}
table  tr:nth-child(even){background-color: #f2f2f2;}
table  tr:nth-child(odd){background-color: #bedaf6;}
article {
 font-family: "Arial",sans-serif;
 width:98%;
 padding-top:50px;
 padding-left:1%;
 padding-right:1%;
 font-size:16pt;
 line-height: 200%;
 word-wrap: break-word;
 
}

</style>
<article>

<?php

$start = $_POST["start"]; # imports info from auto.php
$lenth = $_POST["lenth"];
$csvname = $_POST["csvname"];


if (strcasecmp($csvname, "confshort.csv") == 0) {
	$datasizeloop =141;
}elseif (strcasecmp($csvname, "k2can.csv") == 0) {
	$datasizeloop =135;
}else{
	$datasizeloop =277;
}
	############################################
    $datacase=array();
    #$pick=array(1182451521.6,178,291.063708,0.746143,13.24060000,0.0161036,15.220,"CoRoT-10");
	$csvfile = file_get_contents($csvname);
	$file = (explode("\n",$csvfile));
	$filesize = count($file);
    foreach ($file as $line) {
	$dataline = array();
	$each = (explode(",",$line));
		for ($x = 2; $x <= 9; $x++) {
			$eachrun= $each[$x];
			#echo $eachrun;
			array_push($dataline,$eachrun);
			
	}
	
	array_push($datacase,$dataline);
}

	
	$arry = $_SESSION["datarun"];
	$long = $arry[1];
	$LAT = $arry[0];
	$horzion = $arry[2];
	$lat =$LAT;
	
	$transitbefore = $_POST["transitbefore"];
	$transitafter = $_POST["transitafter"];
	
	$typeofdata = strlen($long)* strlen($LAT)*strlen($horzion);
	
	if ($typeofdata==0){  # enter lat long from prev page
	
	$LAT = $_POST["lat"]; # imports info from auto.php
	$long = $_POST["long"];
	
	$horzion = 30;
	$lat =$LAT;
	
	$size = sizeof($datacase);
	for ($lenthtime = 0; $lenthtime<$lenth; $lenthtime++){
	$datanow = (date_timestamp_get(date_create()));
	$hournow = date('H', $datanow);
	$minnow = date('i', $datanow);
	$removetime = ($minnow * 60) + ($hournow*60*60);
	$now= $datanow + (24*60*60*$start)+ (24*60*60*$lenthtime) - $removetime + (12*60*60);
	
	$perdayset = array();
	for ($loop = 0; $loop <$size; $loop++){
	
	
	$addm = 30;
	########################################
	
	$daysec = 86400; # number of seconds  days
	$add = $daysec; # calculates the number of seconds in the required period
	 # gets current UNIX time at the moment of running the program
	$then = $now + $add; # calculates the number of seconds in UNIX time at the end of the observation 
	
	
	$pick=$datacase[$loop];
	
	$number = ceil((($then-$pick[0])/($pick[4]*60*60*24)));
	$lasts = $pick[0]; # picks out a start of the desired exoplanet transit
	# sets inital condition on count which will be the number of possible transits in that time
	$count = 0; 
	# sets inital condition on counttransits which will be the number of possible visible transits in that time at that location
	$counttransits = 0;
	$DEC = $pick[3]; # pickes out the declination from the pick
	$RA = $pick[2]; # pickes out the right ascension from the pick
	$dataarry = array();
	# full transit and wings code
	for ($x = 0; $x <= $number; $x++) {
		if ($lasts >= $now and $lasts < $then){
			$starttime = $lasts - ($transitbefore * 60); # takes off the added time from the start of a transit
			$endtime = $lasts + (($pick[1] * 60) + ($transitafter * 60)); # adds the added time and the time of the transit time to the start of a transit
			
			$sunri = date_sunrise(($lasts+(24*60*60)),SUNFUNCS_RET_TIMESTAMP,$LAT,$long,102,0); # gets the sunrise of that paticular day (lasts)
			$sunset = date_sunset($lasts,SUNFUNCS_RET_TIMESTAMP,$LAT,$long,102,0); # gets the sunset of that paticular day (lasts)
			
			if ($starttime > $sunset) { # checks if the the start of the transit is before or after sunset or sunrise and runs if its after sunset but before sunrise
				if ($endtime < $sunri ) {	
					$alt1 = altitude($pick,$starttime,$long,$lat); # caculates the alitude of the target star at the start of the required time ( the start of the transit minus the added time )   
					$alt2 = altitude($pick,$endtime,$long,$lat);# caculates the alitude of the target star at the end of the required time ( the end of the transit plus the added time )   
					
					if ($alt1 >= $horzion and $alt2 >= $horzion ){
						array_push($dataarry,$starttime); # addds the start time of the transit with the added time taken off in unix time to dataarry
						array_push($dataarry,$endtime); # addds the end time of the observerable transit with the add time put on in unix time to dataarry
						$counttransits = $counttransits  + 1; # counts how many observerable transits there are
					}
				}
			}
			$count = $count  + 1; # counts how possible transits there are
		}
		$lasts = $lasts + ($pick[4]*60*60*24);# adds the Period of the exoplanet to the started last transit	
	}
	$arrayline = array();
	

	
	if ($counttransits == 1){
	
	array_push($arrayline ,$dataarry[0]);
	array_push($arrayline ,$dataarry[1]);
	array_push($arrayline ,($pick[7]));
	array_push($arrayline ,($pick[6]));
	array_push($arrayline ,($pick[5]));
	array_push($perdayset,$arrayline);
	}
	
	
	if ($loop == $datasizeloop){
	if (count($perdayset) > 0){
		echo "Date: ";
		echo date('d-m', $now);
		$sunset = date_sunset($now,SUNFUNCS_RET_TIMESTAMP,$LAT,$long,102,0);
		echo "&nbsp&nbsp&nbsp Sunset: ";
		echo date('d/m - H:i',$sunset);
		echo "&nbsp&nbsp&nbsp Sunrise: ";
		
		$sunri = date_sunrise(($now+(24*60*60)),SUNFUNCS_RET_TIMESTAMP,$LAT,$long,102,0); # gets the sunrise of that 
		#paticular day (lasts)
		
		echo date('d/m - H:i',$sunri);
		echo "<table> 
  <tr>
    <th>Name</th>
    <th>Start (UT)</th> 
    <th>End (UT)</th>
	<th>Duration (Mins) </th>
	<th>Transit depth (Mag)</th>
	<th>Mag (V-band)</th>
	
	
  </tr>";
		foreach ($perdayset as $set) {
		
		echo "<tr><td>";
		echo $set[2];
		echo "  </td><td> ";
		echo date('d/m - H:i',$set[0]);
		echo "  </td><td> ";
		echo date('d/m - H:i', $set[1]);
		echo "  </td><td> ";
		$lenth2 = ($set[1]-$set[0])/60;
		$hr = intval($lenth2);
		echo $hr;
		echo "  </td><td> ";
		$exoarry = array(1,2,3,4,5,10,15,20,30,45,60,90,120,180,240,300);
		$exosetarry = array();
		$magunarry = array();
		$ratioarry = array();
		#foreach ($exoarry as $exotimeset){
		
		#$peackccsit = peakccd ($set[3],1,$exotimeset,$telecpickarr2);
		#if ($peackccsit<40000 and  $peackccsit>5000){
		#echo $exotimeset;
		#echo "<br>";
		#array_push($exosetarry,$exotimeset);
		echo $set[4]; # dip
		echo "  </td><td> ";
		echo $set[3]; #mag
		#}
		#}
		echo "  </td></tr> ";
		}
		echo "</table>";
		
	}
	}
	}#per day
	}#loop end
	
	
	
	}
	else{
		
		
	
	
	$size = sizeof($datacase);
	
	for ($lenthtime = 0; $lenthtime<$lenth; $lenthtime++){
	$datanow = (date_timestamp_get(date_create()));
	$hournow = date('H', $datanow);
	$minnow = date('i', $datanow);
	$removetime = ($minnow * 60) + ($hournow*60*60);
	$now= $datanow + (24*60*60*$start)+ (24*60*60*$lenthtime) - $removetime + (12*60*60);
	
	$perdayset = array();
	for ($loop = 0; $loop <$size; $loop++){
	
	
	$addm = 30;
	########################################
	
	$daysec = 86400; # number of seconds  days
	$add = $daysec; # calculates the number of seconds in the required period
	 # gets current UNIX time at the moment of running the program
	$then = $now + $add; # calculates the number of seconds in UNIX time at the end of the observation 
	
	
	$pick=$datacase[$loop];
	
	$number = ceil((($then-$pick[0])/($pick[4]*60*60*24)));
	$lasts = $pick[0]; # picks out a start of the desired exoplanet transit
	# sets inital condition on count which will be the number of possible transits in that time
	$count = 0; 
	# sets inital condition on counttransits which will be the number of possible visible transits in that time at that location
	$counttransits = 0;
	$DEC = $pick[3]; # pickes out the declination from the pick
	$RA = $pick[2]; # pickes out the right ascension from the pick
	$dataarry = array();
	# full transit and wings code
	for ($x = 0; $x <= $number; $x++) {
		if ($lasts >= $now and $lasts < $then){
			$starttime = $lasts - ($transitbefore * 60); # takes off the added time from the start of a transit
			$endtime = $lasts + (($pick[1] * 60) + ($transitafter * 60)); # adds the added time and the time of the transit time to the start of a transit
			
			$sunri = date_sunrise(($lasts+(24*60*60)),SUNFUNCS_RET_TIMESTAMP,$LAT,$long,102,0); # gets the sunrise of that paticular day (lasts)
			$sunset = date_sunset($lasts,SUNFUNCS_RET_TIMESTAMP,$LAT,$long,102,0); # gets the sunset of that paticular day (lasts)
			
			if ($starttime > $sunset) { # checks if the the start of the transit is before or after sunset or sunrise and runs if its after sunset but before sunrise
				if ($endtime < $sunri ) {	
					$alt1 = altitude($pick,$starttime,$long,$lat); # caculates the alitude of the target star at the start of the required time ( the start of the transit minus the added time )   
					$alt2 = altitude($pick,$endtime,$long,$lat);# caculates the alitude of the target star at the end of the required time ( the end of the transit plus the added time )   
					
					if ($alt1 >= $horzion and $alt2 >= $horzion ){
						array_push($dataarry,$starttime); # addds the start time of the transit with the added time taken off in unix time to dataarry
						array_push($dataarry,$endtime); # addds the end time of the observerable transit with the add time put on in unix time to dataarry
						$counttransits = $counttransits  + 1; # counts how many observerable transits there are
					}
				}
			}
			$count = $count  + 1; # counts how possible transits there are
		}
		$lasts = $lasts + ($pick[4]*60*60*24);# adds the Period of the exoplanet to the started last transit	
	}
	$arrayline = array();
	

	
	if ($counttransits == 1){
	
	array_push($arrayline ,$dataarry[0]);
	array_push($arrayline ,$dataarry[1]);
	array_push($arrayline ,($pick[7]));
	array_push($arrayline ,($pick[6]));
	array_push($arrayline ,($pick[5]));
	array_push($perdayset,$arrayline);
	}
	
	
	if ($loop == $datasizeloop){
	if (count($perdayset) > 0){
		echo date('d-m', $now);
		$sunset = date_sunset($now,SUNFUNCS_RET_TIMESTAMP,$LAT,$long,102,0);
		echo "&nbsp&nbsp&nbsp Sunset: ";
		echo date('d/m - H:i',$sunset);
		echo "&nbsp&nbsp&nbsp Sunrise: ";
		
		$sunri = date_sunrise(($now+(24*60*60)),SUNFUNCS_RET_TIMESTAMP,$LAT,$long,102,0); # gets the sunrise of that 
		#paticular day (lasts)
		
		echo date('d/m - H:i',$sunri);
		echo "<table> 
  <tr>
    <th>Name</th>
	
    <th>Start (UT)</th> 
    <th>End (UT)</th>
	<th>Duration (Mins) </th>
	<th>Transit depth (Mag)</th>
	<th>Mag (V-band)</th>
	
	
  </tr>";
		foreach ($perdayset as $set) {
		
		echo "<tr><td>";
		echo $set[2];
		echo "  </td><td> ";
		echo date('d/m - H:i',$set[0]);
		echo "  </td><td> ";
		echo date('d/m - H:i', $set[1]);
		echo "  </td><td> ";
		$lenth2 = ($set[1]-$set[0])/60;
		$hr = intval($lenth2);
		echo $hr;
		echo "  </td><td> ";
		$exoarry = array(1,2,3,4,5,10,15,20,30,45,60,90,120,180,240,300);
		$exosetarry = array();
		$magunarry = array();
		$ratioarry = array();
		#foreach ($exoarry as $exotimeset){
		
		#$peackccsit = peakccd ($set[3],1,$exotimeset,$telecpickarr2);
		#if ($peackccsit<40000 and  $peackccsit>5000){
		#echo $exotimeset;
		#echo "<br>";
		#array_push($exosetarry,$exotimeset);
		echo $set[4]; # dip
		echo "  </td><td> ";
		echo $set[3]; #mag
		#}
		#}
		echo "  </td></tr> ";
		}
		echo "</table>";
		
	}
	}
	}#per day
	}#loop end
	
		
		
	}
	?>
	</div>
	
</article>
</html>