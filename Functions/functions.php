<?php

#########################################################################
function airmasscal ($ALTstar){
	#This function uses Rozenberg equation for airmass (Rozenberg, 1966)
	# takes in alitude in degress 
	#outputs the airmass for that altitude
	#Rozenberg, G. V. (1966). Twilight; a study in atmospheric optics. New York: Plenum Press.
	#𝑋 = (cos 𝑍 + 0.025𝑒^(-11cos Z))^-1
		$ALTZ = (90 - $ALTstar); # works out zenith angle 
		$cosz = COS($ALTZ*0.0174533); # turns angle in to radis and tskes the cos of it  
		$cos11z=0.025*exp(-11*$cosz);
		$cosall = $cosz + $cos11z;
		$airmassalt = (1/$cosall);
		return $airmassalt; # reutns fucnction
	}
##################################################################
function altitude($pick,$time,$long,$lat) {
	# This function uses http://www.stargazing.net/kepler/altaz.html
	# To work out the alitude of the object at the time and location (longitude and latitude)
	#takes in pick, time, long and lat
	# outputs alitude
	
	$RA = $pick[2]; # takes out ra and dec
	$DEC = $pick[3]; #
	$j2000 = 946728000; # unixtime of the julian data 2000 (1200 hrs UT on Jan 1st 2000 AD,)
	$dif = $time - $j2000 ; # find the diffrents 
	$dif2 = $dif /(86400); # number of days past J2000
	$hour = date("H", $time); # get hour time
	$min = date("i", $time); # get min time
	$sec = date("s", $time); # get min time
	$hourtime = ($hour + ($min/60)+($sec/(60*60)))*15; # works out hour time with desmila using mins
	$difft = 0.985647 * $dif2; 
	$LST = 100.46 + ($difft) + $long + $hourtime; # local Siderial time
	$HA = $LST - $RA; # hour angle
	$pi180 = 0.01745329252; # pi/180
	$sALT = sin(($DEC*$pi180))*sin(($lat*$pi180))+cos(($DEC*$pi180))*cos(($lat*$pi180))*cos(($HA*$pi180));
	$ALT = asin($sALT);
	$ALT = $ALT/$pi180; # rad to deg
	return $ALT; # returns alitude
    }
###############################################################
function azfortime($pick,$time,$long,$lat) {
	# This function uses http://www.stargazing.net/kepler/altaz.html
	# To work out the azimuth of the object at the time and location (longitude and latitude)
	#takes in pick, time, long and lat
	# outputs azimuth in degress
	$RA = $pick[2];
	$DEC = $pick[3];
	$pi180 = 0.01745329252;
	$ALT = altitude($pick,$time,$long,$lat); # gets alitude in degress
	$cosA = (sin($DEC*$pi180)-(sin($ALT*$pi180)*sin($lat*$pi180)))/(cos($ALT*$pi180)*cos($lat*$pi180));
	$A = acos($cosA);	
	$AZ = $A/$pi180; # turns the az fro radis to degress
	return $AZ;
}
#################################################################
function peakccd ($objmag,$airmass,$exotime,$arry,$equation){
	# this function is based on beck, https://uhra.herts.ac.uk/handle/2299/19912 
	# 2.2.8 Conversion of Catalogue Magnitude to Instrument Magnitude
	# works out the peck ccd value of the object using the telescope data
	# takes in objmag ( mag of object ), airmass, exotime (exposure time in s), arry(telescope data),equation (telescope data sorted )
	# outputs the ccd peck value 
	
	$bgcountexp = $arry[12]; # gets data out of the arry and sets it to value
	$k = $arry[18];
	$seeing = $arry[17];
	$pxsize = $arry[16];
	$binning = $arry[5];
	$flength = $arry[14];
	$pedestal = 70;
	$scale = $pxsize*$binning / ($flength/ 206264.8062 ) / 1000; #  works out the scale of the pixels after binning with its units of  arseconds per pixel
	
	$first = ($equation[0] *$objmag);
	$third = -($equation[2]*(1-$airmass));
	$tot = $first + $equation[1] + $third;  # works out the total instmal magnitude
	$power = $tot*(-2/5);
	$objcount = $exotime *((pow(10,$power))); # the total object counts
	$unbinned_dark_current = $pxsize*$pxsize * (1/ 16.022) * exp( 0.69315 * (-20 - 25) / 7);
	$dark_current = $unbinned_dark_current*$binning*$binning;
	$seeingpx = $seeing/ $scale;
	$bgcount =($bgcountexp*$exotime)+$pedestal;	# background count 
	$peakval = ($objcount / (2*3.14159265*(0.51*$seeingpx)*(0.51*$seeingpx)))+$bgcount; #
	$peakval = intval($peakval); # makes the 
	return $peakval;# 
}
################################################
function dateare ($jdate){
	# takes in the julian date and uputs the time as unixtime
	$j2000 = 946684800;# unix time on j2000 
	$jd2000= 2451544.5;# jd date of j2000
	$jdats = ($jdate-$jd2000)*60*60*24; # caculates the number of seconds between j2000 and the input date
	$times = $j2000 + $jdats;# adds the number of seconds from j2000 to the caculate the full unix time
	return $times;
}
################################################
function tablemakeplanet ($perdayset,$lat,$long,$now,$transitbefore,$transitafter,$typeoflist,$sunangle){
	# This function makes the table of the single tool  
	# takes in perdayset (array hat contains the start and end of the transit as well as all the other info on it), latitude,longitude, now (The current day time), transitbefore (time before transit in mins),transitafter (time after transit in mins),typeoflist (witch database is being used) 
	if (count($perdayset) > 0){ # checks if it needs to run
		$yesbutno = 1;
		echo"<div class = \"row3rds\">"; # opens div to store day, sunset and sunrise times 
		echo "<div>"; # div for date
		echo "Date: ";
		echo date('d-m', $now); # eches day and month
		echo "</div><div>";
		$sunset = date_sunset($now,SUNFUNCS_RET_TIMESTAMP,$lat,$long,$sunangle,0);
		echo "Sunset: "; # eches sunset at location with sun angle 12 below the horizon
		echo date('d/m - H:i',$sunset); 
		echo "</div><div>";
		echo "Sunrise: ";
		$sunri = date_sunrise(($now+(24*60*60)),SUNFUNCS_RET_TIMESTAMP,$lat,$long,$sunangle,0); # eches sunrise at location with sun angle 12 below the horizon (due to using timestamp unix it needs to add 24 hrs to get correct day)
		echo date('d/m - H:i',$sunri);
		echo "</div></div>"; # closes all the divs
		if($typeoflist == 3){ # if the database is the TESS TOI then it needs to change some of the lables in the table
			$magtype = "Tess"; # filter used for magnitude
			$nameplanet = "TOI"; # name 
		}else{
			$magtype = "V";
			$nameplanet = "Name";
		}
		$tagettimeline = array(); # starts 2 arrays that will be used later
		$tagetlist = array();
		echo "<table><tr><th>Start (UT)</th><th>End (UT)</th><th>Start<br>Airmass </th><th>Start of transit<br>Airmass </th><th>Mid of transit<br>Airmass </th><th>End of transit<br>Airmass </th><th>End<br>Airmass </th></tr>"; 
		# gets table header set up
		foreach ($perdayset as $set) { # on single this only runs once but built from all tool 
			echo "<tr><td>";
			echo date('d - H:i',$set[0]); # the start time of obsrving
			echo "  </td><td> ";
			echo date('d - H:i', $set[1]); # the end time of obsrving
			$startalt = $set[0]; # start of obsrving
			$trnasitalt = $set[0] + ($transitbefore*60); # start of transit
			$midtransit = $set[0] + ($transitbefore*60) + ($set[5][1]/2); # mid point of transit
			$endtrnsit = $set[1] - ($transitafter*60); # end of transit
			$endalt = $set[1]; # end of obsrving
			$timearralt = array(); # pushes the times to array
			array_push($timearralt,$startalt);
			array_push($timearralt,$trnasitalt);
			array_push($timearralt,$midtransit);
			array_push($timearralt,$endtrnsit);
			array_push($timearralt,$endalt);
			
			foreach($timearralt as $timealt){
				# for loop for each time
				$alt = altitude($set[5],$timealt,$long,$lat);# caculates alitude
				$airmass = airmasscal ($alt);# caculates airmass
				echo "  </td><td> ";
				echo round($airmass,3); # rounds to 3 dp
			}
			
			echo "  </td></tr> ";
			$tagettime = array();

			array_push($tagettime,(($set[0]-$sunset)/(60*60))); # time diffrents between start of observation and sunset
			array_push($tagettime,(($set[1]-$sunset)/(60*60))); # time diffrents between End of observation and sunset
			array_push($tagettimeline,$tagettime); # puses the two time to array
			array_push($tagetlist,$set[2]); # name of exoplanet to array of target list
			}
		echo "</table>";
		}
	$return = array();
	array_push($return,$yesbutno); # if it ran or not
	array_push($return,$tagettimeline); # times relate to sunset
	array_push($return,$tagetlist); # target names list
	array_push($return,$perdayset); # array with start end and transit info (pick)
	return $return; # returns array
}
################################################
function tablemake ($perdayset,$lat,$long,$now,$transitbefore,$transitafter,$depthtypestring,$depthtype,$typeoflist,$sunangle){
	# This function makes the table of the ALL tool  
	# similar to the single version "tablemakeplanet"
	# takes in perdayset (array hat contains the start and end of the transit as well as all the other info on it), latitude,longitude, now (The current day time), transitbefore (time before transit in mins),transitafter (time after transit in mins),typeoflist (witch database is being used), 
	# but with depthtypestring (string, mmag or magnitude), depthtype (1,0)
	if (count($perdayset) > 0){ # if it needs to run
		$yesbutno = 1;
		echo"<table id = \"datasun\">"; # table only with the header for date sunrise and set
		echo "<tr><th>";
		echo "Date: ";
		echo date('d-m', $now);
		echo "</th><th>";
		$sunset = date_sunset($now,SUNFUNCS_RET_TIMESTAMP,$lat,$long,$sunangle,0);
		# sunset time 
		echo "Sunset: ";
		echo date('d/m - H:i',$sunset);
		echo "</th><th>";
		echo "Sunrise: ";
		$sunri = date_sunrise(($now+(24*60*60)),SUNFUNCS_RET_TIMESTAMP,$lat,$long,$sunangle,0); # sunrise time due to timestamp needs to add 24 hr to get right day
		echo date('d/m - H:i',$sunri);
		echo "</th></tr></table>"; # closes div
		if($typeoflist == 3){ # if data source is TESS TOI then chages table text
			$magtype = "Tess";
			$nameplanet = "TOI";
		}else{
			$magtype = "V";
			$nameplanet = "Name";
		}
		$tagettimeline = array();
		$tagetlist = array();
		echo "<table><tr><th>" . $nameplanet . "</th><th>Start</th><th>End</th><th>Transit time<br>(Mins)</th><th>Full time<br>(Mins)</th>	<th>Transit depth<br>(". $depthtypestring .")</th><th>Mag<br>(". $magtype."-band)</th></tr>"; #
		foreach ($perdayset as $set) { # for each exoplanet transit for that day(night)
			echo "<tr><td>";
			echo $set[2]; # name or TOI
			echo "  </td><td> ";
			echo date('d - H:i',$set[0]); # start of obsrving
			echo "  </td><td> ";
			echo date('d - H:i', $set[1]); # end of obsrving
			echo "  </td><td> ";
			$lenth2 = (($set[1]-$set[0])/60); # gets the leth of time in mins
			$hr = (intval($lenth2));
			echo ($hr- $transitbefore- $transitafter); # transit time
			echo "  </td><td> ";
			echo $hr; # total obsrving time in mins
			echo "  </td><td> "; 
			if($depthtype == 1){ # depnding on depthtype either outputs depth of transit in mmag or mag
				echo round($set[4],5); # dip mag
			}else{
				echo round(($set[4]*1000),3); # dip mmag
			}
			echo "  </td><td> ";
			echo round($set[3],4); #the magitude of the target
			echo "  </td></tr> "; # closes table line
			$tagettime = array();
			array_push($tagettime,(($set[0]-$sunset)/(60*60))); # dif between sunset and start of observation
			array_push($tagettime,(($set[1]-$sunset)/(60*60))); # dif between sunset and end of observation
			array_push($tagettimeline,$tagettime); 
			array_push($tagetlist,$set[2]); # name of transit
			}
		echo "</table>"; # closes table
	}
	$return = array();
	array_push($return,$yesbutno); # if run then 1  
	array_push($return,$tagettimeline); # sunrise and sunset diff times
	array_push($return,$tagetlist); # array of targets list 
	array_push($return,$perdayset); # array with start end and transit info (pick)
	return $return;
}

#########################################
function transittimes ($lat,$long,$now,$datacase,$loop,$transitbefore,$transitafter,$horzion,$perdayset,$sunangle){
	$daysec = 86400; # number of seconds  days
	$then = $now + $daysec; # calculates the number of seconds in UNIX time at the end of the observation 
	$pick = $datacase[$loop]; # gets a exoplanet info 
	$number = ceil((($then-$pick[0])/($pick[4]*60*60*24))); # works out the max value of periods needed between the start of the T0 and then end of the obsving window
	$lasts = $pick[0]; # picks out a start of the desired exoplanet transit 
	$counttransits = 0;  # sets insial condition
	$DEC = $pick[3]; # pickes out the declination from the pick
	$RA = $pick[2]; # pickes out the right ascension from the pick
	$dataarry = array();

	for ($x = 0; $x <= $number; $x++) { # for every period it checks 
	# Note: not code efficent Will be improved at some point 
		if ($lasts >= $now and $lasts < $then){ #  checks if the lasts is between the 2 windows of now and then 
			$starttime = $lasts - ($transitbefore * 60); # takes off the added time from the start of a transit
			$endtime = $lasts + (($pick[1] * 60) + ($transitafter * 60)); # adds the added time and the time of the transit time to the start of a transit
			$sunri = date_sunrise(($lasts+(24*60*60)),SUNFUNCS_RET_TIMESTAMP,$lat,$long,$sunangle,0); # gets the sunrise of that paticular day (lasts) , adds 24 to get right day 
			$sunset = date_sunset($lasts,SUNFUNCS_RET_TIMESTAMP,$lat,$long,$sunangle,0); # gets the sunset of that paticular day (lasts)
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
	
	if ($counttransits == 1){ # only if counttransits is 1 it runs 
		$arrayline = array();
		array_push($arrayline ,$dataarry[0]); # start of observation
		array_push($arrayline ,$dataarry[1]); # end of observation
		array_push($arrayline ,($pick[7])); # name of exoplanet
		array_push($arrayline ,($pick[6])); # magitude of target
		array_push($arrayline ,($pick[5])); # Depth of transit
		array_push($arrayline ,($pick)); # that exoplanet info 
		array_push($perdayset,$arrayline);
	}
	return $perdayset;
}
####################################
function findnowtime ($long,$start,$lenthtime){
	$datanow = (date_timestamp_get(date_create())); # gets the timestamp of the time that the codes runs
	$hournow = date('H', $datanow); # hour time
	$minnow = date('i', $datanow); # min time
	$removetime = ($minnow * 60) + ($hournow*60*60); # turns it to seconds
	$now= $datanow + (24*60*60*$start)+ (24*60*60*$lenthtime) - $removetime + (12*60*60) + ($long*-240);
	# works out the now time: current time, added number of start days, added the number of lenth days, removes time to get to mid night , adds 12hr to get to mid day , then adds a conter to help with timezones so that now is near the midday of the location 
	# -240 == 4 min per deg
	return $now;
}
####################################
function getdatacsv($typeoflist,$mindepth){
	# This function outputs the exoplanet database for the selescted database 
	# Input typeoflist (which database is being used 0,1,2,3),mindepth if a min depth of transit depth is needed
	# Function used for ALL tool
	# selects the right name
	# full URLS so that the code runs rather than gets data, could be changed to functions
	if ($typeoflist == 0){ # var 2
		$csvname = "http://observatory.herts.ac.uk/exotransitpredict/data/var2.txt";
	}
	elseif ($typeoflist == 1){ # Exoplanet NASA archive
		$csvname = "http://observatory.herts.ac.uk/exotransitpredict/data/exoplanetnasa.php";
	}
	elseif ($typeoflist == 2){ # Exoplanet . org
		$csvname = "http://observatory.herts.ac.uk/exotransitpredict/data/exoplanetsorg.php";
	}
	elseif ($typeoflist == 3){ # TESS TOI
		$csvname = "http://observatory.herts.ac.uk/exotransitpredict/data/tesstoi.php";
	}
	
	$csvfile = file_get_contents($csvname); # gets the data
	$file = (explode("\n",$csvfile)); # splits it per line
	$datacase = array(); # opens array that will hold exoplanet data
	
	foreach ($file as $line) { #  for each line in the csv
		$each = (explode(",",$line)); # splits it by comma
		if(($mindepth/1000)<($each[5])){ # if the depth of transit is bigger than the set min depth then it adds to database
			array_push($datacase,$each);
		}	
	}
return $datacase; # returns database
}
########################################
function gettransitdata ($typeoflist,$star){
	# This function outputs the exoplanet for the selescted database 
	# Input typeoflist (which database is being used 0,1,2,3), Star name - 
	# Function used for Single tool
	# full URLS so that the code runs rather than gets data, could be changed to functions
	if ($typeoflist == 0){
		$csvname = "http://observatory.herts.ac.uk/exotransitpredict/data/var2.txt";
	}
	elseif ($typeoflist == 1){
		$csvname = "http://observatory.herts.ac.uk/exotransitpredict/data/exoplanetnasa.php";
	}
	elseif ($typeoflist == 2){
		$csvname = "http://observatory.herts.ac.uk/exotransitpredict/data/exoplanetsorg.php";
	}
	elseif ($typeoflist == 3){
		$csvname = "http://observatory.herts.ac.uk/exotransitpredict/data/tesstoi.php";
	}
	$csvfile = file_get_contents($csvname); # gets the csv file
	$file = (explode("\n",$csvfile));  # splits per line
	
	foreach ($file as $line) { # for each line in csv file
		$each = (explode(",",$line));	# splits by comma	
		$run = $each[7]; # name of it
		if (strcasecmp($run, $star) == 0) { # cheks if the names are the same
			$return = $each;	# sets the value to return
		}
	}
	return $return;
}
########################################
function timelinefun ($lenthtime,$returntable){
	# This function plots time line of the exoplanets 
	# inputs : lenthtime (the number of days in the period), returntable (the ouput of "tablemake")
	# does not ouput anything but produces the plot
	
	$tagettimeline = $returntable[1];
	$tagetlist = $returntable[2];
	$timelinecount = count($tagettimeline);
	if($timelinecount>1){ # runs only if there is more than 1 exoplanet
		echo " <div id=\"myDiv" . $lenthtime . "\"></div>"; # div for the plot
		echo "<script>\n"; # start script
	
		$timelinedata2 = "var data" . $lenthtime . " = ["; # starts data string
		for ($timenumber=0; $timenumber < ($timelinecount); $timenumber++ ){
			# for each exoplanet
			$timesetdata = $tagettimeline[$timenumber];
			$timelinedata = "var trace";
			$timelinedata .= $timenumber;
			$timelinedata .= " = {\nx:[";
			$timelinedata .= $timesetdata[0]; # x start obsrving
			$timelinedata .= ",";
			$timelinedata .= $timesetdata[1]; # x end obsrving
			$timelinedata .= "],\ny:[";
			$timelinedata .= ($timelinecount - $timenumber); # Y axis set to number
			$timelinedata .= ",";
			$timelinedata .= ($timelinecount - $timenumber); # Y axis set to number
			$timelinedata .= "],\nmode:'lines',\nname:'";
			$timelinedata .= $tagetlist[$timenumber]; # name of target
			$timelinedata .= "',\nline: {width: 10}};\n";
			echo $timelinedata; # echoes that exoplanet timeline
			$timelinedata2 .= "trace";
			$timelinedata2 .= $timenumber;
			if($timenumber == ($timelinecount-1)){}
			else{
				$timelinedata2 .= ",";
			}
		}
		$timelinedata2 .= "];\n";
		echo $timelinedata2; # echoes JS array containg the timeline data
		echo "Plotly.newPlot('myDiv" . $lenthtime .  "',data" . $lenthtime . ", layouttime);"; # plots the data 
		echo "</script>";
	}
	return;
}
####################################
function altazfun ($lenthtime,$returntable,$long,$lat,$sunangle){
	# This function plots the ALT & AZ of the exoplanets 
	# inputs : lenthtime (the number of days in the period), returntable (the ouput of "tablemake"), longitude , latitude
	# Process plot showing every 5 min from start and end of observation of transit
	# does not ouput anything but produces the plot
	
	$tagettimeline = $returntable[1];
	$tagetlist = $returntable[2];
	$perdayset = $returntable[3];
	$timelinecount = count($tagettimeline); # counts the number of exoplanets
	if($timelinecount>0){ # runs as long as there is an exoplanet
		echo " <div id=\"myDivalt" . $lenthtime . "\"></div>"; # div that will be the plot
		echo " <div id=\"myDivalttime" . $lenthtime . "\"></div>"; # div that will be the plot
		echo "<script>"; # starts the script
		$timelinedata2 = "var dataalt" . $lenthtime ." = ["; # dataalttime
		$timelinedata3 = "var dataalttime" . $lenthtime ." = ["; # 
		for ($timenumber=0; $timenumber < ($timelinecount); $timenumber++ ){
			# for each exoplanet
			$timesetdata = $tagettimeline[$timenumber];
			$timelinedata = "var trace";
			$timelinedata .= $lenthtime;
			$timelinedata .= $timenumber;
			$timelinedata .= " = {x:[";
			
			$timelinedata4 = "var tracetimealt";
			$timelinedata4 .= $lenthtime;
			$timelinedata4 .= $timenumber;
			$timelinedata4 .= " = {x:[";
			
			$set = $perdayset[$timenumber]; # gets out that exoplanet data 
			$start = $set[0]; # start of observation
			$end = $set[1]; # end time of observation
			$pick9 = $set[5]; # pick 
			$setmin = 5; # sets the intrval of run
			$timedow = ($end-$start)/(60*$setmin);
			$manip = $start; # sets a value to be manipulate
			$timedatast = "";
			$timedatait  = "";
			$sunset = date_sunset($start,SUNFUNCS_RET_TIMESTAMP,$lat,$long,$sunangle,0);
			for ($ush=0; $ush<$timedow; $ush++ ){
				$az = azfortime($pick9,$manip,$long,$lat); # az
				$timedatast .= $az;
				$manip = $manip + ($setmin*60); # 
				$timedatast .= ",";
				$timedatait .= (($manip-$sunset)/60);
				$timedatait .= ",";				
			}
			
			$timelinedata .= substr($timedatast, 0, -1); # removes last comma
			$timelinedata .= "],\ny:[";	
			$timelinedata4 .= substr($timedatait, 0, -1);  # removes last comma
			
			
			$manip = $start;# sets a value to be manipulate
			$timedatast = "";
			for ($ush=0; $ush<$timedow; $ush++ ){
				$alt = altitude($pick9,$manip,$long,$lat); # alitude
				$timedatast .= $alt;
				$manip = $manip + ($setmin*60);
				$timedatast .= ",";
				
			}
			
			$timelinedata4 .= "],\ny:[";
			$timelinedata4 .= substr($timedatast, 0, -1);  # removes last comma
			$timelinedata4 .= "],\nmode:'lines',\nname:'";
			$timelinedata4 .= $tagetlist[$timenumber]; # name of exoplanet
			$timelinedata4 .= "',\nline: {width: 3}};\n";	
			echo $timelinedata4; # eches exoplanet data	
			
			$timelinedata .= substr($timedatast, 0, -1);  # removes last comma
			$timelinedata .= "],\nmode:'lines',\nname:'";
			$timelinedata .= $tagetlist[$timenumber]; # name of exoplanet
			$timelinedata .= "',\nline: {width: 3}};\n";	
			echo $timelinedata; # eches exoplanet data
			
			
			
			$timelinedata2 .= "trace";
			$timelinedata2 .= $lenthtime;
			$timelinedata2 .= $timenumber;
			$timelinedata3 .= "tracetimealt";
			$timelinedata3 .= $lenthtime;
			$timelinedata3 .= $timenumber;
			
			if($timenumber == ($timelinecount-1)){} # stops last comma
			else{
				$timelinedata2 .= ",";
				$timelinedata3 .= ",";
			}
		}
		$timelinedata2 .= "];\n";
		echo $timelinedata2; # eches JS array of exoplanet
		$timelinedata3 .= "];\n";
		echo $timelinedata3; # eches JS array of exoplanet
		echo "Plotly.newPlot('myDivalt" . $lenthtime . "',dataalt" . $lenthtime  . ", layoutalt);\n"; # plots data 
		echo "Plotly.newPlot('myDivalttime" . $lenthtime . "',dataalttime" . $lenthtime  . ", layoutalttime);"; # plots data 
		echo "</script>"; 
		}
	return;
}
###############################
function findmoon2($unixday,$lat,$long){
	# This function uses https://aa.usno.navy.mil/data/docs/AltAz.php Form B
	# Inputs : unixday (time) ,latitude,longitude
	# Outputs: array conting (time,alitude of moon,az of moon,anglur disneeded)
	$mintime = date('i',$unixday); # min
	$hourtime = date('H',$unixday); # hour
	$sectime = date('s',$unixday); # sec
	$needhiuttime = ($hourtime*60*60) + ($mintime*60) + $sectime; # number of seconds till be removed
	$unixday = $unixday - $needhiuttime; # gets midnight time
	$year = date('Y',$unixday); # Year
	$month = date('m', $unixday); # month
	$day = date('d', $unixday); # day
	$longsign = str_split (strval($long)); # splits long to into array with each on as a string
	$latsign =  str_split (strval($lat)); # splits lat to into array with each on as a string
	$sigornumblong = ($longsign [0]); # checks too see if long is negitive if it is it sets longsign to -1 else 1
	if( "-" == $sigornumblong){
		$longsign = -1;
	}
	else{
		$longsign = 1;
	}
	$sigornumblat = ($latsign [0]);

	if( "-" == $sigornumblat){ # checks too see if lat is negitive if it is it sets longsign to -1 else 1 , like long
		$latsign = -1;
	}
	else{
		$latsign = 1;
	}
	# turns the lat and long from desimals to degs and min with no sigins (abs value)
	$latdeg = (floor($lat)); 
	$latmin = abs(($latdeg - $lat)*60);
	$latdeg = abs($latdeg);
	$longdeg = (floor($long));
	$longmin = abs(($longdeg - $long)*60);
	$longdeg = abs($longdeg);
	#creates the URL name that the data will be at 
	$websitename = "http://aa.usno.navy.mil/cgi-bin/aa_altazw.pl?form=2&body=11&year=" . $year . "&month=" . $month . "&day=" . $day . "&intv_mag=10&place=bayford&lon_sign=" . $longsign ."&lon_deg=" . $longdeg ."&lon_min=" . $longmin ."&lat_sign=" . $latsign . "&lat_deg=" . $latdeg ."&lat_min=". $latmin . "&tz=&tz_sign=-1";
	
	$content = file_get_contents($websitename); # gets the data from the URL

	if (strlen($content)  !=  0){ # if content is zero "Error" then does not run
		$firstpass = (explode("                                                                              
",$content)); # splits the data up

		$alldata = $firstpass[2]; # gets number 2 
		$splitdata = (explode("\n",$alldata)); # splits per line
		$sliced = array_slice($splitdata, 0, -5); # removes some of the data
		$cont = count($sliced);
		$moondata = array(); # array to hold moon data
		for ($x = 0; $x < $cont; $x++) {
			$moonline = array();
			$line = $sliced[$x];
			$splitline = (explode("      ",$line)); # splits line by tab
			$time = $splitline[0]; # 
			$timearr = (explode(":",$time)); # splits the time
	
			$hour = $timearr[0]*60*60; # number of seconds of hours
			$min = $timearr[1]*60; # number of seconds of min
			$hourandmin = $hour + $min; # number of seconds of hours and mins
			
			$phase = $splitline[3]; # phase of the moon
			$Altitu = $splitline[1]; # alitude of moon
			$Azim = $splitline[2]; # az of moon
			if (strlen($Altitu)  !=  0 and strlen($Azim )  !=  0 ){ 
				if (strlen($phase)  !=  0){ # checks to see if any thing is wrong
					$disneeded = 60/(1+((14*(1-$phase))/6)*((14*(1-$phase))/6));
					$fullunixtim = $hourandmin + $unixday; # adds hour to day to get full unix time
					# 
					array_push($moonline,$fullunixtim);	
					array_push($moonline,$Altitu);	
					array_push($moonline,$Azim);
					array_push($moonline,$disneeded);
					array_push($moondata,$moonline);
				}
			}
		} 
	}
	return $moondata;
}
#########################################
function moonallset($lenthtime,$now,$lat,$long,$returntable,$sunangle){

	$perdayset = $returntable[3];
	echo " <div id=\"mymoon" . $lenthtime . "\"></div>";
	$sunsettime = date_sunset($now,SUNFUNCS_RET_TIMESTAMP,$lat,$long,$sunangle,0);
	$nownext =  $now + (24*60*60);
	$sunrisetime = date_sunrise($nownext,SUNFUNCS_RET_TIMESTAMP,$lat,$long,$sunangle,0);
	$startdata = findmoon2($sunsettime,$lat,$long);
	$countrunmoon = count($startdata);
	$moonset = array();
	for ($moonz = 0; $moonz < ($countrunmoon); $moonz++){
		$line = $startdata[$moonz];
		array_push($moonset,$line);
	}
	$startdata = findmoon2($sunrisetime,$lat,$long);
	$countrunmoon = count($startdata);
	for ($moonz = 0; $moonz < ($countrunmoon); $moonz++){
		$line = $startdata[$moonz];
		array_push($moonset,$line);
	}
	sort($moonset);
		$countsetmoon = count($moonset);
		$grathmoonset = array();
		
		foreach($perdayset as $set){
			$starttimemoon = $set[0];
			$endtimemoon = $set[1];
			$pickmoon = $set[5];
			$grathmoonper = array();
			for ($z = 0; $z < ($countsetmoon); $z++){
				$moonline = $moonset[$z];
				$moontime = $moonline[0];
				if ($moontime <= $endtimemoon) {	
					if ($moontime >= $starttimemoon) {
						$alt1 = altitude($pickmoon,$time,$long,$lat);
						$az = azfortime($pickmoon,$time,$long,$lat);
						$moonalt = $moonline[1];
						$moonaz = $moonline[2];
						$moondisneeded = $moonline[3];
						if ($moonaz > 180){
							$moonaz = $moonaz -360;
						}
						if($az > 180){
							$az = $az -360;	
						}
						$altdif = abs($alt1 - $moonalt);
						$azdif1 = abs($az - $moonaz);
						$azdif2 = abs($az + $moonaz);
						if($azdif1 < $azdif2){
							$azdif= $azdif1;
						}
						else{
							$azdif= $azdif2;
						}
						$moonpart = pow((($altdif*$altdif)+($azdif*$azdif)),0.5); 
						$timemoon = ($moontime - $sunsettime)/(60*60);
						$grathlinemoon = array();
						array_push($grathlinemoon,$timemoon);
						array_push($grathlinemoon,$moonpart);
						array_push($grathmoonper,$grathlinemoon);
					}
				}
			}
			$grathmoondataname = array();
			
			array_push($grathmoondataname,$pickmoon[7]);
			array_push($grathmoondataname,$grathmoonper);
			array_push($grathmoonset,$grathmoondataname);
		}
		$targetyesno = 0;
		foreach($grathmoonset as $grathmoondataname){
			$datasetarr = $grathmoondataname[1];
			$countdatasetarr = count($datasetarr);
			$targetyesno = $targetyesno + $countdatasetarr;
		}
		if($targetyesno >0){
		$xdataarr = array();
		$objectarr = array();
		$namearr = array();
		foreach($grathmoonset as $grathmoondataname){
			$nameset = $grathmoondataname[0];
			$datasetarr = $grathmoondataname[1];
			$countdatasetarr = count($datasetarr);
			
			$xdata = "";
			$part = "";
			for ($pmoon = 0; $pmoon < $countdatasetarr; $pmoon++) {
						$grathline = $datasetarr[$pmoon];
						$xone = $grathline[0]; 
						$xdata .= $xone;
						$yset = $grathline[1];
						$part .= $yset;
						if ($pmoon ==($countdatasetarr-1)){
						}
						else{
						$xdata	.= ",";
						$part .= ",";
						}
			}
			array_push($xdataarr,$xdata);
			array_push($objectarr,$part);
			array_push($namearr,$nameset);
			
		}
		
		echo"<script>";
		$timelinecount = count($namearr);
		$timelinemoon2 = "var datamoon = [";
		for ($timenumber=0; $timenumber < ($timelinecount); $timenumber++ ){
				
			$timelinedata = "var tracemoon";
			$timelinedata .= $timenumber;
			$timelinedata .= " = {x:[";
			$timelinedata .= $xdataarr[$timenumber];
			$timelinedata .= "],\ny:[";
			$timelinedata .= $objectarr[($timenumber)];
			$timelinedata .= "],\nmode: 'lines',\ntype: 'scatter',\nname:'";
			$timelinedata .= $namearr[$timenumber];
			$timelinedata .= "'};\n";
			echo $timelinedata;	
			$timelinemoon2 .= "tracemoon";
			$timelinemoon2 .= $timenumber;
			if($timenumber == ($timelinecount)){
			}
			else{
				$timelinemoon2 .= ",";
			}
		}
		$timelinemoon2 .= "moonneed";
		$timelinemoon2 .= "];\n";
		$sunsettime = date_sunset($now,SUNFUNCS_RET_TIMESTAMP,$lat,$long,$sunangle,0);
		$nownext =  $now + (24*60*60);
		$sunrisetime = date_sunrise($nownext,SUNFUNCS_RET_TIMESTAMP,$lat,$long,$sunangle,0);
		$moonneedar = array();
		for ($z = 0; $z < ($countsetmoon); $z++){
			$moonline = $moonset[$z];
			$moontime = $moonline[0];
			if ($moontime <= $sunrisetime) {	
				if ($moontime >= $sunsettime) {
					$moondisneeded = $moonline[3];
					$timemoonfixed =($moontime  - $sunsettime)/(60*60);
					$moonnee = array();
					array_push($moonnee,$timemoonfixed);
					array_push($moonnee,$moondisneeded);
					array_push($moonneedar,$moonnee);
				}
			}
		}
		$countdatasetarr = count($moonneedar);
		if($countdatasetarr>0){
			$moonallset = 1;
			$xdatamoon = "";
			$needmoontxt = "";
			for ($pmoon = 0; $pmoon < $countdatasetarr; $pmoon++) {
				$moonline = $moonneedar[$pmoon];
				$xone = $moonline[0]; 
				$xdatamoon .= $xone;
				$yset = $moonline[1];
				$needmoontxt .= $yset;
				if ($pmoon ==($countdatasetarr-1)){
				}
				else{
					$xdatamoon	.= ",";
					$needmoontxt .= ",";
				}
			}
			$timelinedata = "var moonneed";
			$timelinedata .= " = {x:[";
			$timelinedata .= $xdatamoon ;
			$timelinedata .= "],\ny:[";
			$timelinedata .= $needmoontxt ;
			$timelinedata .= "],\nmode: 'lines',\ntype: 'scatter',\nname:'";
			$timelinedata .= "Angle needed";
			$timelinedata .= "'};\n";
			echo $timelinedata;
			echo $timelinemoon2;
			echo "Plotly.newPlot('mymoon" . $lenthtime . "',datamoon, layoutmoon);";
			}
		echo"</script>";
		}
	return $moonallset;
}
###########################################################
function NormSInv($p){
# This function takes in a random desimal and outputs a SD value 
   $a1 = -39.6968302866538;
   $a2 = 220.946098424521;
   $a3 = -275.928510446969;
   $a4 = 138.357751867269;
   $a5 = -30.6647980661472;
   $a6 = 2.50662827745924;
   $b1 = -54.4760987982241; 
   $b2 = 161.585836858041; 
   $b3 = -155.698979859887;
   $b4 = 66.8013118877197;
   $b5 = -13.2806815528857; 
   $c1 = -7.78489400243029E-03;
   $c2 = -0.322396458041136;
   $c3 = -2.40075827716184;
   $c4 = -2.54973253934373;
   $c5 = 4.37466414146497; 
   $c6 = 2.93816398269878;
   $d1 = 7.78469570904146E-03;
   $d2 = 0.32246712907004; 
   $d3 = 2.445134137143; 
   $d4 = 3.75440866190742;
   $p_low = 0.02425; 
   $p_high = 1 - $p_low;
   $q = 0.0;
   $r = 0.0;
   if($p < $p_low){
      $q = pow(-2 * log($p), 2);
      $NormSInv = ((((($c1 * $q + $c2) * $q + $c3) * $q + $c4) * $q + $c5) * $q + $c6) / (((($d1 * $q + $d2) * $q + $d3) * $q + $d4) * $q + 1);
    } else if($p <= $p_high){
      $q = $p - 0.5; $r = $q * $q;
      $NormSInv = ((((($a1 * $r + $a2) * $r + $a3) * $r + $a4) * $r + $a5) * $r + $a6) * $q / ((((($b1 * $r + $b2) * $r + $b3) * $r + $b4) * $r + $b5) * $r + 1);
    } else {
      $q = pow(-2 * log(1 - $p), 2);
      $NormSInv = -((((($c1 * $q + $c2) * $q + $c3) * $q + $c4) * $q + $c5) * $q + $c6) /  (((($d1 * $q + $d2) * $q + $d3) * $q + $d4) * $q + 1);
    }
    return $NormSInv;
}
#######################################################
function uncertainty2 ($mtarget,$airmass,$exotime,$equation){
	# This fucnction is based on 
	# Input : mtarget (magitude of object) ,airmass (airmass), exotime (exposer time), equation (telescope data sorted)
	# Output :  the expected uncertainty of that object and airmass
	$first = ($equation[0] *$mtarget);
	$third = -($equation[2]*(1-$airmass));
	$tot = $first + $equation[1] + $third;
	$power = $tot*(-2/5);
	$Nt = $exotime *((pow(10,$power)));
	$delsqu1 = ($equation[3]*$equation[3]); # bias
	$delsqu2 = (pow(($equation[4]*$exotime),2)); # dark
	$delsqu3 = (pow(($equation[5]* $Nt),2)); # flat
	$delsqu4 = $Nt; # total count
	$delsqu6 = pow($Nt*($equation[6]*((pow($airmass,(7/4)))*(pow((2*$exotime),(-0.5))))),2); # irms 
	$skyconst = $equation[7];
	$Nsky = $skyconst * $exotime;
	$skydataconst = $equation[8];
	$skydatapower = $equation[9] * $exotime;
	$skydata = ($skydataconst + $skydatapower)*($skydataconst + $skydatapower);
	$cal = $delsqu1 +$delsqu2 + (($equation[5] * $Nsky)*($equation[5] * $Nsky));
	$rms = pow($Nsky*($equation[6]*((pow($airmass,(7/4)))*(pow((2*$exotime),(-0.5))))),2);
	$delsqu5 = $skydata + $rms - $cal; # Sky 
	$delsqu = $delsqu1 + $delsqu2 + $delsqu3 + $delsqu4 + $delsqu5 + $delsqu6;
	$del = (pow($delsqu,0.5));
	$inlog = 1 + ($del / $Nt);
	$deltotaltargetmag =2.5*log10($inlog);
	$delmag =  $deltotaltargetmag;
	
	return $delmag;
	}
#######################################################
function images($arry,$exotime,$transitbefore,$transitafter,$pick){
	#Input: arry (telescope data array) ,exotime (exposer time ),transitbefore (time before m) ,transitafter (time after m) , pick (exoplanet array)
	# Outputs : array containg images before,during and after transit
	$exoplus = ($arry[3]+$exotime); # exposure time plus time separation of images
	$numberimoffleft = floor(($transitbefore*60) /$exoplus); # images before 
	$numberimoffright = floor(($transitafter*60) /$exoplus); # images after
	$numberimon = floor(($pick[1]*60)/$exoplus); # images during transit
	$imagesnum = array(); # 
	array_push($imagesnum,$numberimoffleft);
	array_push($imagesnum,$numberimoffright);
	array_push($imagesnum,$numberimon);
	return $imagesnum;
}
#######################################################
function plotpredictdata($imagearry,$transitbefore,$transitafter,$arry,$exotime,$pick,$uncerarry){
	# This Function prodcuses the data needed for the predicted plot 
	# Input : imagearry,
	#transitbefore (time before in min),transitafter (time after in min),arry (telescope data),exotime (exposure time),pick (exoplanet data),uncerarry (uncertainty data )
	# Outputs : the data needed to plot  X,Y and Y uncertainty  
	$imagesnum = images($arry,$exotime,$transitbefore,$transitafter,$pick);
	# Gets the images before during and after transit
	$numberimoffleft = $imagesnum[0]; 
	$numberimoffright = $imagesnum[1]; 
	$numberimon = $imagesnum[2]; # images during transit
	$exoplus = ($arry[3]+$exotime); # seconds between images
	$numerimondiv1 = floor($numberimon/4); # 1-2 contact image number
	$numerimondiv2 = $numberimon - $numerimondiv1; # 3-4 contact image number
	$dip = $pick[5]; # transit depth
	$indip = 1 - $dip;  # depth in transit in magnitude
	$ploty = array(); # arrays that hold the y , x and y error values
	$plotx = array();
	$ploterror = array();
	$timerun = 0;
	$ranstartnum = 35; # limits the SD of the NormSInv to about 2.5 other wise it is too wild
	$ranendnum = 965;
	for ($off=0; $off<$numberimoffleft; $off++){
	 
		$randomn2 = (mt_rand($ranstartnum,$ranendnum))/1000; # random number between the number then mad desimal
		$sd = NormSInv($randomn2); # turns to the normal distrubtion into a gassin distrubtion
		$nsd = $uncerarry[0]*$sd; # muliltes the SD to whork out the off set
		$randomn = $nsd + 1; # adds the off set to the value
		$uncer0 = $uncerarry[0]; # gets the first value
		array_push($ploty,$randomn); # adds data to array
		$timeset = $timerun/60; # turns second to min
		array_push($plotx,$timeset);
		array_push($ploterror,$uncer0);
		$timerun = $timerun + $exoplus; # adds the time to the next image
	}
	###
	for ($on=0; $on<$numberimon; $on++){ # during transit
	# T 2 - 3
		$indip = 1 - $dip;
		$randomn1 = (mt_rand($ranstartnum,$ranendnum))/1000; # random number between the number then mad desimal
		$sd = NormSInv($randomn1); # turns to the normal distrubtion into a gassin distrubtion
		$nsd = $uncerarry[4]*$sd;# muliltes the SD to whork out the off set
		$randomn = $nsd + $indip; 
		if ($on<$numerimondiv1){ # T 1 - 2
			$indip = 1 - (($dip/$numerimondiv1)*($on+1));
			$randomnums = (mt_rand($ranstartnum,$ranendnum))/1000;
			$sd = NormSInv($randomnums);
			$nsd = $uncerarry[4]*$sd;
			$randomn = $nsd + $indip; 
		}
		if ($on>=$numerimondiv2){ # T 3 - 4
			$indip = 1- $dip +(($dip/$numerimondiv1)*($on-$numerimondiv2));
			$randomnum = (mt_rand($ranstartnum,$ranendnum))/1000;
			$sd = NormSInv($randomnum);
			$nsd = $uncerarry[4]*$sd;
			$randomn = $nsd + $indip; 
		}
		$uncer4 = $uncerarry[4];
		array_push($ploty,$randomn);
		$timeset = $timerun/60;
		array_push($plotx,$timeset);
		array_push($ploterror,$uncer4);
		$timerun = $timerun + $exoplus;
	}
	for ($off=0; $off<$numberimoffright; $off++){
		# off transit after
		$randomnnum = (mt_rand($ranstartnum,$ranendnum))/1000;
		$sd = NormSInv($randomnnum);
		$nsd = $uncerarry[7]*$sd;
		$randomn = $nsd + 1; 
		$uncer8 = $uncerarry[7];
		array_push($ploty,$randomn);
		$timeset = $timerun/60;
		array_push($plotx,$timeset);
		array_push($ploterror,$uncer8);
		$timerun = $timerun + $exoplus;
	}
	
	$count = count($ploty);  # this section turns the array to a string with the data separaed by comma but not the last one
	$xdata = "";
	$ydata = "";
	$unexo = "";
	for ($p = 0; $p < $count; $p++) { 
		$xone = $plotx[$p]; 
		$xdata .= $xone;	
		$yset = $ploty[$p];
		$ydata .= $yset;
		$uncer = $ploterror[$p];
		$unexo .= $uncer;
		if ($p ==($count-1)){
		}
		else{
			$xdata	.= ",";
			$ydata .= ",";
			$unexo .= ",";
		}
	}
	$return = array();
	array_push($return,$xdata); # returns the data back
	array_push($return,$ydata);
	array_push($return,$unexo);
	return $return;
}
####################################################
function imagecal ($exotime,$transitbefore,$transitafter,$pick,$arry){ 
	# This Function gets image times and number
	# Input : exotime (exposer time),transitbefore (transit time before in mins),$transitafter (transit time after in mins), pick (exoplanet array), arry (telescope data array)
	# Outputs : array containg image times in seconds and image numbers
	$addm2 = $transitbefore + $transitafter; # total added time 
	$totaltime = ($addm2 + $pick[1])*60; # full observation in seconds
	$imagecount = $totaltime /($exotime + $arry[3]);  # number of images
	$countimag2 = round($imagecount/8) ; # dives by 8 and rounds to nearist intval
	$imagearry = array(); #
	for ($x = 0; $x < 8; $x++) { # 
		$imagecont = ($countimag2*$x)*($arry[3]+$exotime); # seconds from start on that image
		array_push($imagearry,$imagecont); # adds time to array
    }
	$reuturnimages = array();
	array_push($reuturnimages,$imagearry); # array conting the times of the images from 0
	array_push($reuturnimages,$countimag2); # images per plan
	return $reuturnimages;
}
##############################################
function tablephase4 ($imagearry,$starttime,$long,$lat,$equation,$arry,$mtar,$mref,$pick,$exotime){
	# This Function ouputs a table used when phase 4 info has been enter into it
	# Input : imagearry (function - imagecal[0] ),starttime,longitude and latitude  equation, arry (telescope data), mtar (magitude of target), mref (magnitude of refrance),pick (transit data), exotime (exposer time)
	# Outputs : echoes a table containg image and a array contain the total uncertainty over the diffrent times  
	$uncerarry = array();
	echo "<table><tr><th>Airmass</th><th>Time</th><th>Image number</th><th>Targets Peak pixel value (ADU)</th><th>Reference Peak pixel value (ADU)</th><th>Uncertainty</th>	<th>Transit depth to Uncertainty ratio</th></tr>"; # above creates the top of the table
	for ($x = 0; $x <8 ; $x++) { # for loop , for the 9 data points 
		$timeadd = $imagearry[0][$x]; # gets out the time of that data point
		$time = $starttime + $timeadd; # adds the start time and the data ponit ot get the actual time for that ponit
		$alt = altitude($pick,$time,$long,$lat);# caculates alitude
		$airmass = airmasscal ($alt);# caculates airmass
		echo "  <tr><td> "; # start row then element
		echo number_format($airmass,3); # echos the airmass with 3DP
		echo "  </td><td> ";# new element
		echo date('H:i', $time);# echos the hour and mins
		echo "  </td><td> ";# new element in the row
		$imagenumber = ($imagearry[1] *$x)+1; # caculates the number of of images 
		echo floor($imagenumber); # echoes the image count 
		echo " </td><td> "; # new element in the row
		$peck = peakccd ($mtar,$airmass,$exotime,$arry,$equation); # gets the ccd peck value for the target
		if ($peck > $arry[19] or $peck <5000){
			echo "<span style=\"color:red\">"; # waring to user that peck is too low or high
			echo number_format($peck,1); # echoes the ccd peck for target 1DP
			echo "</span>";
		}else {
			echo "<span style=\"color:green\">"; 
			echo number_format($peck,1); # echoes the ccd peck for target 1DP
			echo "</span>";
		}
		echo " </td><td> ";# new element in the row
		$peckref = peakccd($mref,$airmass,$exotime,$arry,$equation); # gets the ccd peck value for the refrance star  
		if ($peckref > $arry[19] or $peckref <5000){
			echo "<span style=\"color:red\">"; # waring to user that peck is too low or high
			echo number_format($peckref,1); # echoes the ccd peck for target 1DP
			echo "</span>";
		}else {
			echo "<span style=\"color:green\">"; 
			echo number_format($peckref,1); # echoes the ccd peck for target 1DP
			echo "</span>";
		}
		echo " </td><td> "; # new element in the row
		$deltar = uncertainty2 ($mtar,$airmass,$exotime,$equation);# gets the uncertiny on target
		$delref = uncertainty2 ($mref,$airmass,$exotime,$equation);# gets the uncertiny on refrance
		$uncert = pow((($deltar*$deltar)+($delref*$delref)),0.5); # gets the total uncertiny 
		array_push($uncerarry,$uncert);
		echo number_format($uncert,5); # echoes the total uncertiny with 6DP
		echo " </td><td> "; #  new element in the row
		$ratio = $pick[5]/$uncert; # caculates to ratio of the dip in magnitude to the uncertiny
		if ($ratio <= 1.5 ){
			echo "<span style=\"color:red\">"; # waring to user that peck is too low or high
			echo number_format($ratio,3); # echoes the ratio with 3DP
			echo "</span>";
		}else {
			echo "<span style=\"color:green\">"; 
			echo number_format($ratio,3); # echoes the ratio with 3DP
			echo "</span>";
		}
		echo "</td></tr>"; # cloces the row
		} 
	echo "</table>"; #  closing down the table for that loop
	return $uncerarry; # returns array containg the uncertainty in mag for the difrrent times (image numbers)
}
##############################################
function plotpredictit ($predictplotdata){
	# This Function writes the info to plot the predicted plot
	# Input : predictplotdata array containg the x,y and uncertainty in Y data as a string with comma separation of the data
	# Outputs : plot of the predict 
	$xdata = $predictplotdata[0];
	$ydata = $predictplotdata[1];
	$unexo = $predictplotdata[2];
	echo "<div id=\"grath1\"></div>"; 
echo "<script>
    var full = {
  x: [$xdata],
  y: [$ydata],
  error_y: {
      type: 'data',
      array: [$unexo],
      visible: true
    },
  mode: 'markers'
};
 
var predictit = [ full];
var layout = {
	 title:'Predicted plot',
	 yaxis: {
		title: 'Normalized Magnitude'
		 
	},
	 xaxis: {
		title: 'Time (minutes)'
	},
};

Plotly.newPlot('grath1', predictit, laypredict);

  </script>";
  return;
}
###########################################################
function tablemaketess ($perdayset){
	# This function makes the table of the ALL tool  
	# similar to the single version "tablemakeplanet"
	# takes in perdayset (array hat contains the start and end of the transit as well as all the other info on it), latitude,longitude, now (The current day time), transitbefore (time before transit in mins),transitafter (time after transit in mins),typeoflist (witch database is being used), 
	# but with depthtypestring (string, mmag or magnitude), depthtype (1,0)
	echo"<div class = \"row3rds\">"; # div for date sunrise and set
	echo "<div></div><div>TESS INFO</div><div></div></div>";
	if (count($perdayset) > 0){ # if it needs to run
	
		echo "<table><tr><th>TOI</th><th>Master</th><th>SG1a</th><th>SG1b</th><th>TESS Disposition</th><th>TFOPWG Disposition</th><th>PPM</th><th>T mid error (min)</th><th>Duration (min)</th><th>Duration Error (min)</th><th>Comments</th></tr>";
		foreach ($perdayset as $set) { # for each exoplanet transit for that day(night)
		$picktess = $set[5];
		
			echo "<tr><td>";
			echo $picktess[7];
			echo "</td><td> ";
			echo $picktess[12];
			echo "</td><td> ";
			echo $picktess[13];
			echo "</td><td> ";
			echo $picktess[14];
			echo "</td><td> ";
			echo $picktess[15];
			echo "</td><td> ";
			echo $picktess[16];
			echo "</td><td> "; 
			echo $picktess[17];
			echo "</td><td> ";
			$number = ceil(($set[0]-$picktess[0])/($picktess[4]*24*60*60));
			echo round(($picktess[9]+($number*$picktess[10])),3);
			echo "</td><td> ";
			echo round($picktess[1],2);
			echo "</td><td> ";
			echo round($picktess[11],2);
			echo "</td><td> ";
			echo $picktess[18];
			echo " (<a href =\"https://exofop.ipac.caltech.edu/tess/edit_obsnotes.php?id=" . $picktess[19] . "\"target=\"_blank\" style=\"text-decoration:none;\" >more notes</a>)" ;
			echo "</td></tr> "; # closes table line
			
			}
		echo "</table>"; # closes table
	}
	
	return;
}
###############################

?>