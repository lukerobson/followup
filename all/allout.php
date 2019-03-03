<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<style>
table {
	/*  makes table 100% width of the article and text in center  */ 
    width: 100%;
	text-align: center;
}
/*  makes the table have diffrent colors for the odd and even tr(not heading of table)   */ 
table  tr:nth-child(even){background-color: #f2f2f2;}
table  tr:nth-child(odd){background-color: #bedaf6;}

</style>
<article>

<?php

$start = $_POST["start"]; # imports info from all.php
$lenth = $_POST["lenth"];
$csvname = $_POST["csvname"];
$transitbefore = $_POST["transitbefore"];
$transitafter = $_POST["transitafter"];

############################################
$datacase=array(); # array that will contain all exoplanet data 
$csvfile = file_get_contents($csvname); # gets the file of the csvname 
$file = (explode("\n",$csvfile)); # splits the file per line
foreach ($file as $line) { # 
	$dataline = array(); # array for the exoplanet
	$each = (explode(",",$line)); # splits the line by comma
		for ($x = 2; $x <= 9; $x++) { 
			$eachrun= $each[$x]; #  selects the approprate data
			array_push($dataline,$eachrun); # adds that to the array for that exoplanet
		}
	array_push($datacase,$dataline); # then adds that array of the exoplanet to array containg all the exoplanet arrays 
}
############################################
#checks to see if data has been entered and saved in SESSION  
#gets the data out then tests the string lenth of the latitude, longitude and horizon and if any of them are not entered then the string lenth will be zreo so gets the data out 
$arry = $_SESSION["datarun"]; 
$long = $arry[1];
$lat = $arry[0];
$horzion = $arry[2];
$typeofdata = strlen($long)* strlen($lat)*strlen($horzion);
	
if ($typeofdata==0){  
	
	$lat = $_POST["lat"]; # imports info from auto.php
	$long = $_POST["long"];
	$horzion = 30;
}
############################################
	
$datasizeloop = count($datacase);  # counts the number of lines
$loopset = $datasizeloop-1; # as count starts at 1 it takes off 1 to get to 0

for ($lenthtime = 0; $lenthtime<$lenth; $lenthtime++){ # loop for each day
	$datanow = (date_timestamp_get(date_create())); # gets the unixtime at the time of loading the page
	$hournow = date('H', $datanow);# gets the hours
	$minnow = date('i', $datanow); # gets the mins
	$removetime = ($minnow * 60) + ($hournow*60*60); # works out the number of seconds after midnight
	$now= $datanow + (24*60*60*$start)+ (24*60*60*$lenthtime) - $removetime + (12*60*60) - ($long*240); # gets the time now, adds the start number of days, the number of days of the loop, removeing the hours after midnight, then adds 12hrs, removes seconds related to the longitude 
	# gets the midday time at that longitude for that day
	
	$perdayset = array(); # array that will contain all the approprate exoplanet transit for that day
	for ($loop = 0; $loop <$datasizeloop; $loop++){ # loop for each exoplanet
		 
		$add = 86400;# number of seconds in a days 
		$then = $now + $add; # calculates the number of seconds in UNIX time at the end of the observation 
		$pick=$datacase[$loop]; # gets the data for that paticular exoplanet
	
		$number = ceil((($then-$pick[0])/($pick[4]*60*60*24))); # caculates the maxiumm number of period that have occured between the start time of the recored and the end of the observing day
		$lasts = $pick[0]; # picks out a start of the desired exoplanet transit
		
		# sets inital condition on counttransits which will be the number of possible visible transits in that time at that location
		$counttransits = 0;
		$DEC = $pick[3]; # pickes out the declination from the pick
		$RA = $pick[2]; # pickes out the right ascension from the pick
		$dataarry = array();
		for ($x = 0; $x <= $number; $x++) { 
			if ($lasts >= $now and $lasts < $then){
				$starttime = $lasts - ($transitbefore * 60); # takes off the added time from the start of a transit
				$endtime = $lasts + (($pick[1] * 60) + ($transitafter * 60)); # adds the added time and the time of the transit time to the start of a transit
			
				$sunri = date_sunrise(($lasts+(24*60*60)),SUNFUNCS_RET_TIMESTAMP,$lat,$long,102,0); # gets the sunrise of that paticular day (lasts)
				$sunset = date_sunset($lasts,SUNFUNCS_RET_TIMESTAMP,$lat,$long,102,0); # gets the sunset of that paticular day (lasts)
			
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
			}
			$lasts = $lasts + ($pick[4]*60*60*24);# adds the Period of the exoplanet to the started last transit	
		}
		$arrayline = array();
		if ($counttransits == 1){ # if there is a transit for that day 
	
			array_push($arrayline ,$dataarry[0]); # unixtime start of observation
			array_push($arrayline ,$dataarry[1]); # unixtime end of observation
			array_push($arrayline ,($pick[7])); # name of exoplanet
			array_push($arrayline ,($pick[6])); # V-band
			array_push($arrayline ,($pick[5])); # transit depth
			array_push($perdayset,$arrayline);
		}
	
		if ($loop == $loopset){ # if at the end of the database of exoplanets it goes throgh
			if (count($perdayset) > 0){ # makes sure if there is at least a transit
				echo "Date: ";
				echo date('d-m', $now); # data in UT
				$sunset = date_sunset($now,SUNFUNCS_RET_TIMESTAMP,$lat,$long,102,0); # Sunset at location
				echo "&nbsp&nbsp&nbsp Sunset: "; 
				echo date('d/m - H:i',$sunset);
				echo "&nbsp&nbsp&nbsp Sunrise: ";
		
				$sunri = date_sunrise(($now+(24*60*60)),SUNFUNCS_RET_TIMESTAMP,$lat,$long,102,0); # gets the sunrise of that day (lasts)
		
				echo date('d/m - H:i',$sunri); # sunrise at location
				
				echo "<table><tr><th>Name</th><th>Start (UT)</th><th>End (UT)</th><th>Duration (Mins) </th><th>Transit depth (Mag)</th><th>Mag (V-band)</th></tr>";
				foreach ($perdayset as $set) { # loop throgh the array 
		
					echo "<tr><td>";
					echo $set[2]; # name of the exoplanet
					echo "  </td><td> ";
					echo date('d/m - H:i',$set[0]); # start time of observing
					echo "  </td><td> ";
					echo date('d/m - H:i', $set[1]); # end time of observing
					echo "  </td><td> ";
					$lenth2 = ($set[1]-$set[0])/60; # find the the diffrent between start and end then divade it by 60 to get it in to mins
					$mins = intval($lenth2); # gets the intval so that is easier to read
					echo $mins; # minitues of Duration need to obsere transit and added time before and after
					echo "  </td><td> ";
					echo $set[4]; # transit depth in magnitude
					echo "  </td><td> ";
					echo $set[3]; # V-band (Johnson)
					echo "  </td></tr> ";
				}
		echo "</table>";
			} # if a transit
		} # database end
	}#per day
}#loop end
	
?>
</article>
</html>