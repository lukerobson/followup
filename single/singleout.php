<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>

<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<?php require_once('functions.php'); ?>

<article>
<br>
<style>
table {

}

#backbut a {

}


</style>
<?php
# loads the data in from the privies page
#######################
function NormSInv($p){
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
###########################
function uncertainty2 ($mtarget,$airmass,$exotime,$equation){
	$first = ($equation[0] *$mtarget);
	$third = -($equation[2]*(1-$airmass));
	$tot = $first + $equation[1] + $third;
	$power = $tot*(-2/5);
	$Nt = $exotime *((pow(10,$power)));

	$delsqu1 = ($equation[3]*$equation[3]); # bias
	$delsqu2 = (pow(($equation[4]*$exotime),2)); # dark
	$delsqu3 = (pow(($equation[5]* $Nt),2)); # flat
	$delsqu4 = $Nt;
	
	$delsqu6 = pow($Nt*($equation[6]*((pow($airmass,(7/4)))*(pow((2*$exotime),(-0.5))))),2); # irms 
	
	$skyconst = $equation[7];
	$Nsky = $skyconst * $exotime;
	$skydataconst = $equation[8];
	$skydatapower = $equation[9] * $exotime;
	$skydata = ($skydataconst + $skydatapower)*($skydataconst + $skydatapower);
	$cal = $delsqu1 +$delsqu2 + (($equation[5] * $Nsky)*($equation[5] * $Nsky));
	$rms = pow($Nsky*($equation[6]*((pow($airmass,(7/4)))*(pow((2*$exotime),(-0.5))))),2);
	
	$delsqu5 = $skydata + $rms - $cal;
	$delsqu = $delsqu1 + $delsqu2 + $delsqu3 + $delsqu4 + $delsqu5 + $delsqu6;
	$del = (pow($delsqu,0.5));
	$inlog = 1 + ($del / $Nt);
	$deltotaltargetmag =2.5*log10($inlog);
	$delmag =  $deltotaltargetmag;
	
	return $delmag;
	}
#######################
$star = $_POST["star"];
$addm = $_POST["addm"];
$mouth = $_POST["mouth"];
$arry = $_SESSION["datarun"];
$transitbefore = $_POST["transitbefore"];
$transitafter = $_POST["transitafter"];

$type1 = strlen($arry[0])* strlen($arry[1])*strlen($arry[2]);
$type2 = strlen ($arry[8]) * strlen ($arry[9]) * strlen ($arry[10]) * strlen ($arry[11]) * strlen ($arry[12]) *strlen ($arry[13]) * strlen ($arry[17]) * strlen ($arry[18]) * strlen ($arry[19]) ;
$csvname = "all.csv";
$csvfile = file_get_contents($csvname);
$file = (explode("\n",$csvfile));
$datacase = array();
$filesize = count($file);
	
	
foreach ($file as $line) {
	
	$each = (explode(",",$line));		
	$run= $each[9];
		if (strcasecmp($run, $star) == 0) {
			$dataline = array();	
			$run= $each[2];
			array_push($datacase,$run);#0	
			$run= $each[3];
			array_push($datacase,$run);
			$run= $each[4];
			array_push($datacase,$run);
			$run= $each[5];
			array_push($datacase,$run);#3
			$run= $each[6];
			array_push($datacase,$run);
			$run= $each[7];
			array_push($datacase,$run);#5
			$run= $each[8];
			array_push($datacase,$run);	
			$run= $each[9];
			array_push($datacase,$run);	#7
			$run= $each[1];
			array_push($datacase,$run);	
		}
}
$pick = $datacase;
$pickcount = count($pick);

if ( $pickcount == 0){
	echo "ERROR  !!";
	$error = 100;
}else{
$startext = $pick[8];
if ($type1 == 0){#no info
	$lat = $_POST["lat"];
	$long = $_POST["long"];
	date_default_timezone_set('UTC');
	
	####
	$addm2 = $transitbefore + $transitafter; # caculates the total add on 
	$totaltime = ($addm2 + $pick[1])*60;
	$daysec = 86400*30; # number of seconds per 30 days
	$add = $mouth * $daysec; # calculates the number of seconds in the required period
	$now=date_timestamp_get(date_create());# gets current UNIX time at the moment of running the program
	$then = $now + $add; 
	$error = 0;
	$errorarry = array();
	$dataarry = array(); # array that will hold start then end of the next transit
	$number = intval((($then-$pick[0])/($pick[4]*60*60*24))+2);
	$lasts = $pick[0]; # picks out a start of the desired exoplanet transit
	# sets inital condition on count which will be the number of possible transits in that time
	$count = 0; 
	# sets inital condition on counttransits which will be the number of possible visible transits in that time at that location
	$counttransits = 0;
	$DEC = $pick[3]; # pickes out the declination from the pick
	$RA = $pick[2]; # pickes out the right ascension from the pick	
	$elevation = 30;
	#######################################
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
					if ($alt1 >= $elevation and $alt2 >= $elevation ){
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
	
	#######################################
	if ($counttransits < 0.5) {# checks is there are transits in the period if there is no transt then it does not due the main program
	if ($mouth == 1){# if the month is one then it outputs this
	echo "For $startext there are no transits visible in the next month";
	}
	else {# if the month is more than one then it outputs this
		echo "For $startext there are no transits visible in the next $mouth months ";
	}
	}
else {# if there an observerable transit then this runs
	echo "For $startext   "; # tells the user about the exoplanet is
	
	if ($mouth == 1){ # if the month is one then it outputs this - due to diffrant frasing 
	echo "there are $counttransits possible viewable transits in the next month"; # echoes out the number of possible transits
	}
	else {# if the month is more than one then it outputs this
		echo "there are $counttransits possible viewable transits in the next $mouth months";# echoes out the number of possible transits
	}
	echo "<br>RA: ";
	echo number_format($RA,4); 
	echo " DEC: ";
	echo number_format($DEC ,4); 
	echo " Vmag: ";
	echo number_format($pick[6],4); 
	echo " Transit depth (Mag) : ";
	echo number_format($pick[5],4); 
	
	####################################################
	for ($y = 0; $y < (2*$counttransits); $y+=2) {# for loop for the number of possible transits +2 as dataarry has start then end times but only need start times
	$starttime = $dataarry[$y]; # gets out the start time of the transist
	$endtime = $dataarry[$y + 1];
	$numberset =  ($endtime - $starttime)/4;
	
	echo "<br>Date - ";# echoes data
	echo date('d/m/Y', $starttime);# echoes the year month then day of the start time of the transit
	echo "<br>";
	echo "<table> 
  <tr>
    <th>Time (UT)</th>
	<th>Altitude (degrees)</th>
    <th>Airmass</th> 
  </tr>"; # above creates the top of the table
	$time = $starttime;
		for ($x = 0; $x <=3 ; $x++) { # for loop , for the 9 data points  # gets out the time of that data point
		$time = $starttime + ($numberset*$x); # adds the start time and the data ponit ot get the actual time for that ponit
		$alt = altitude($pick,$time,$long,$lat);# caculates alitude
		$airmass = airmasscal ($alt);# caculates airmass
		echo "  <tr><td> "; # start row then element
		echo date('H:i', $time);# echos the hour and mins
		echo "  </td><td> ";# new element
		echo number_format($alt,0);
		echo "  </td><td> ";# new element
		echo number_format($airmass,3); # echos the airmass with 3DP
		echo "</td></tr>"; # cloces the row
		} 
	echo "</table>"; #  closing down the table for that loop
	}
	
}

	####	

}else{
	if ($type2 == 0){ #1.1
	
	$arry = $_SESSION["datarun"];
	$lat = $arry[0];
	$long =  $arry[1];
	$elevation = $arry[2];
	date_default_timezone_set('UTC');
	
	####
	$addm2 = $transitbefore + $transitafter;
	$totaltime = ($addm2 + $pick[1])*60;
	$daysec = 86400*30; # number of seconds per 30 days
	$add = $mouth * $daysec; # calculates the number of seconds in the required period
	$now=date_timestamp_get(date_create());# gets current UNIX time at the moment of running the program
	$then = $now + $add; 
	$error = 0;
	$errorarry = array();
	$dataarry = array(); # array that will hold start then end of the next transit
	$number = intval((($then-$pick[0])/($pick[4]*60*60*24))+2);
	$lasts = $pick[0]; # picks out a start of the desired exoplanet transit
	# sets inital condition on count which will be the number of possible transits in that time
	$count = 0; 
	# sets inital condition on counttransits which will be the number of possible visible transits in that time at that location
	$counttransits = 0;
	$DEC = $pick[3]; # pickes out the declination from the pick
	$RA = $pick[2]; # pickes out the right ascension from the pick	
	#######################################
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
					if ($alt1 >= $elevation and $alt2 >= $elevation ){
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
	
	#######################################
	if ($counttransits < 0.5) {# checks is there are transits in the period if there is no transt then it does not due the main program
	if ($mouth == 1){# if the month is one then it outputs this
	echo "For $startext there are no transits visible in the next month";
	}
	else {# if the month is more than one then it outputs this
		echo "For $startext there are no transits visible in the next $mouth months ";
	}
	}
else {# if there an observerable transit then this runs
	echo "For $startext   "; # tells the user about the exoplanet is
	
	if ($mouth == 1){ # if the month is one then it outputs this - due to diffrant frasing 
	echo "there are $counttransits possible viewable transits in the next month"; # echoes out the number of possible transits
	}
	else {# if the month is more than one then it outputs this
		echo "there are $counttransits possible viewable transits in the next $mouth months";# echoes out the number of possible transits
	}
	echo "<br>RA: ";
	echo number_format($RA,4); 
	echo " DEC: ";
	echo number_format($DEC ,4); 
	echo " Vmag: ";
	echo number_format($pick[6],3); 
	echo " Transit depth (Mag) : ";
	echo number_format($pick[5],4);
	echo "<br> ";
	
	
	$type3sent = strlen ($arry[3]) * strlen ($arry[4]) * strlen ($arry[5]) * strlen ($arry[6]) * strlen ($arry[7]); 
	
	if ($type3sent == 0){
		
	}else{
	$create = array();
	array_push($create,$dataarry);
	array_push($create,$pick);
	array_push($create,$arry);
	$_SESSION["create"] = $create;
	echo "<div id=\"backbut\"><a href=\"create.php\">Create RTML</a></div>";
	}
	####################################################
	for ($y = 0; $y < (2*$counttransits); $y+=2) {# for loop for the number of possible transits +2 as dataarry has start then end times but only need start times
	$starttime = $dataarry[$y]; # gets out the start time of the transist
	$endtime = $dataarry[$y + 1];
	$numberset =  ($endtime - $starttime)/4;
	
	echo "<br>Date - ";# echoes data
	echo date('d/m/Y', $starttime);# echoes the year month then day of the start time of the transit
	echo "<br>";
	echo "<table> 
  <tr>
    <th>Time (UT)</th>
	<th>Altitude (degrees)</th>
    <th>Airmass</th> 
  </tr>"; # above creates the top of the table
	$time = $starttime;
		for ($x = 0; $x <=3 ; $x++) { # for loop , for the 9 data points  # gets out the time of that data point
		$time = $starttime + ($numberset*$x); # adds the start time and the data ponit ot get the actual time for that ponit
		$alt = altitude($pick,$time,$long,$lat);# caculates alitude
		$airmass = airmasscal ($alt);# caculates airmass
		echo "  <tr><td> "; # start row then element
		echo date('H:i', $time);# echos the hour and mins
		echo "  </td><td> ";# new element
		echo number_format($alt,0);
		echo "  </td><td> ";# new element
		echo number_format($airmass,3); # echos the airmass with 3DP
		echo "</td></tr>"; # cloces the row
		} 
	echo "</table>"; #  closing down the table for that loop
	}
	
}

	
	
	}else{#2
	
	$mtar = $_POST["mtar"];
	$mref = $_POST["mref"];
	$exotime = $_POST["exotime"];
	$lat = $arry[0];
	$long =  $arry[1];
	$LAT =$lat;
	$addm2 = $transitbefore + $transitafter;
	$totaltime = ($addm2 + $pick[1])*60;
	$imagecount = $totaltime /($exotime + $arry[3]); # gets an image count useing the total time of observation divided by the exposer time with 26 second as the time between each image
	$imagecountper = $imagecount/4;# divites by four as to get the number of images per plan
	$countin = intval($imagecountper); # next two make sure that you get all the required images to get all the time in 
	$countin = $countin +1;
	$countimag2 = ($countin/2) ; # then divies by 2 to get half the images of a plan
	$imagearry = array(); # creates a array that will hold the image times
	for ($x = 0; $x <= 8; $x++) { # for loop for the image times 
	$imagecont = ($countimag2*$x)*($arry[3]+$exotime);# first braket gets the image number then 2nd muplites the first by the exopser time + delay time in images to get the times of the start of the images
	array_push($imagearry,$imagecont); # pushes the caculated start times to the array
    } 
	
	$daysec = 86400*30; # number of seconds per 30 days
	$add = $mouth * $daysec; # calculates the number of seconds in the required period
	$now=date_timestamp_get(date_create());# gets current UNIX time at the moment of running the program
	
	$then = $now + $add; # calculates the number of seconds in UNIX time at the end of the observation 
	# next two sets error count($error) and an error array ($errorarry) that will hold any error to show to the user
	# if there is an error
	$error = 0;
	$errorarry = array();
	$dataarry = array(); # array that will hold start then end of the next transit
	$number = intval((($then-$pick[0])/($pick[4]*60*60*24))+2);
	$lasts = $pick[0]; # picks out a start of the desired exoplanet transit
	# sets inital condition on count which will be the number of possible transits in that time
	$count = 0; 
	# sets inital condition on counttransits which will be the number of possible visible transits in that time at that location
	$counttransits = 0;
	$DEC = $pick[3]; # pickes out the declination from the pick
	$RA = $pick[2]; # pickes out the right ascension from the pick	
	$elavaton = $arry[2];# as the uncertainty equation is only vaid for airmass up to 3.0
	#######################################
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
					
					if ($alt1 > $elavaton ){
						if ($alt2 > $elavaton ){
						array_push($dataarry,$starttime); # addds the start time of the transit with the added time taken off in unix time to dataarry
						array_push($dataarry,$endtime); # addds the end time of the observerable transit with the add time put on in unix time to dataarry
						$counttransits = $counttransits  + 1; # counts how many observerable transits there are
						}
					}
				}
			}
			$count = $count  + 1; # counts how possible transits there are
		}
		$lasts = $lasts + ($pick[4]*60*60*24);# adds the Period of the exoplanet to the started last transit	
	}
	
	#######################################
	if ($counttransits < 0.5) {# checks is there are transits in the period if there is no transt then it does not due the main program
	if ($mouth == 1){# if the month is one then it outputs this
	echo "For $startext there are no transits visible in the next month";
	}
	else {# if the month is more than one then it outputs this
		echo "For $startext there are no transits visible in the next $mouth months ";
	}
	}
else {# if there an observerable transit then this runs
	echo "For $startext "; # tells the user about the exoplanet is
	
	if ($mouth == 1){ # if the month is one then it outputs this - due to diffrant frasing 
	echo "there are $counttransits possible viewable transits in the next month"; # echoes out the number of possible transits
	}
	else {# if the month is more than one then it outputs this
		echo "there are $counttransits possible viewable transits in the next $mouth months";# echoes out the number of possible transits
	}
	if ($mtar == "" and $mref == ""){
		echo " Dates of transits are (YYYY-MM-DD) : <br>";
		for ($y = 0; $y < (2*$counttransits); $y+=2) {
			$starttime = $dataarry[$y];
			echo date('Y-m-d', $starttime);
			if ($y != (($counttransits-1)*2)){
			echo "<br>";
			}
		}
	}else{
	
	echo ", with exposure time of $exotime seconds you get  "; # echos the exopser time that the user has selected
	echo ($countimag2*8); # echos the total number of images per transit that will be goten with that exopser time
	$imagesoff = floor((($transitbefore + $transitafter)*60)/($arry[3]+$exotime));
	$imageson = floor(($countimag2*8) - ($imagesoff));
	echo " images with approximately $imagesoff images for each off-transit time and approximately $imageson images during the transit";# next 2 echos image and new line
	echo "<br>";
	$type3sent = strlen ($arry[3]) * strlen ($arry[4]) * strlen ($arry[5]) * strlen ($arry[6]) * strlen ($arry[7]); 
	
	if ($type3sent == 0){
		
	}else{
	$create = array();
	array_push($create,$dataarry);
	array_push($create,$pick);
	array_push($create,$arry);
	$_SESSION["create"] = $create;
	echo "<div id=\"backbut\"><a href=\"create.php\">Create RTML</a></div>";
	
	}
	echo "<br>";
	$greenvaule = 0;
	$uncerarry = array();
	
	$equation = array();
	array_push($equation,$arry[20]);        # power on instrmental vs catalog    0 
	array_push($equation,$arry[21]);      # constant on instrmental vs catalog 1
	array_push($equation,$arry[18]);          # power on instrmental vs airmass    2
	array_push($equation,$arry[8]);          # bias frames                        3
	array_push($equation,$arry[9]);        # dark current                       4
	array_push($equation,$arry[10]);       # flat field                         5
	array_push($equation,$arry[11]);    # Scintillation constant             6
	array_push($equation,$arry[12]);          # sky noise const                    7
	array_push($equation,$arry[8]);          # sky noise const  analsis           8
	array_push($equation,$arry[13]);         # sky noise power analsis            9
	
	####################################################
	for ($y = 0; $y < (2*$counttransits); $y+=2) {# for loop for the number of possible transits +2 as dataarry has start then end times but only need start times
	$starttime = $dataarry[$y]; # gets out the start time of the transist
	echo "Date - ";# echoes data
	echo date('Y-m-d', $starttime);# echoes the year month then day of the start time of the transit
	
	
	echo "<br>";
	echo "<table> 
  <tr>
    <th>Airmass</th>
    <th>Time</th> 
    <th>Image number</th>
	<th>Targets Peak pixel value (ADU)</th>
	<th>Reference Peak pixel value (ADU)</th>
	<th>Uncertainty</th>
	<th>Transit depth to Uncertainty ratio</th>
  </tr>"; # above creates the top of the table
		for ($x = 0; $x <=8 ; $x++) { # for loop , for the 9 data points 
		$timeadd = $imagearry[$x]; # gets out the time of that data point
		$time = $starttime + $timeadd; # adds the start time and the data ponit ot get the actual time for that ponit
		$alt = altitude($pick,$time,$long,$lat);# caculates alitude
		$airmass = airmasscal ($alt);# caculates airmass
		echo "  <tr><td> "; # start row then element
		echo number_format($airmass,3); # echos the airmass with 3DP
		echo "  </td><td> ";# new element
		echo date('H:i', $time);# echos the hour and mins
		echo "  </td><td> ";# new element in the row
		$imagenumber = ($countimag2 *$x)+1; # caculates the number of of images total in this loop
		if ($x == 8){
			$imagenumber = $imagenumber -1;
		}
		echo intval($imagenumber); # echoes the image count 
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
			$greenvaule = $greenvaule + 1;
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
			$greenvaule = $greenvaule + 1;
		}
		
		
		echo " </td><td> "; # new element in the row
		$deltar = uncertainty2 ($mtar,$airmass,$exotime,$equation);
		# gets the uncertiny on target
		$delref = uncertainty2 ($mref,$airmass,$exotime,$equation);
		# gets the uncertiny on refrance
		$uncert = pow((($deltar*$deltar)+($delref*$delref)),0.5);   # gets the total uncertiny 
		
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
			$greenvaule = $greenvaule + 1;
		}
		echo "</td></tr>"; # cloces the row
		} 
	echo "</table>"; #  closing down the table for that loop
	echo "<br>"; # separates the data more
	}
	}
	echo "<div id=\"grath1\"></div>"; 
}

$numberimoff = $imagesoff;
$numberimon = $imageson;
$numerimondiv1 = floor($numberimon/4);
$numerimondiv2 = $numberimon - $numerimondiv1;
$exoplus = ($arry[3]+$exotime);
$dip = $pick[5];
$indip = 1 - $dip;
$ploty = array();
$plotx = array();
$ploterror = array();
$timerun = 0;
$timeit = $dataarry[0];
$ranstartnum = 35;
$ranendnum = 965;
for ($off=0; $off<$numberimoff; $off++){
	 
	$randomn2 = (mt_rand($ranstartnum,$ranendnum))/1000;
	$sd = NormSInv($randomn2);
	$nsd = $uncerarry[0]*$sd;
	$randomn = $nsd + 1;
	$uncer0 = $uncerarry[0];
	array_push($ploty,$randomn);
	$timeset = $timerun/60;
	array_push($plotx,$timeset);
	array_push($ploterror,$uncer0);
	$timerun = $timerun + $exoplus;
	
}
###
for ($on=0; $on<$numberimon; $on++){
	$indip = 1 - $dip;
	
	$randomn1 = (mt_rand($ranstartnum,$ranendnum))/1000;
	$sd = NormSInv($randomn1);
	$nsd = $uncerarry[4]*$sd;
	$randomn = $nsd + $indip; 
	
	if ($on<$numerimondiv1){
	$indip = 1 - (($dip/$numerimondiv1)*($on+1));
	$randomnums = (mt_rand($ranstartnum,$ranendnum))/1000;
	$sd = NormSInv($randomnums);
	$nsd = $uncerarry[4]*$sd;
	$randomn = $nsd + $indip; 
	
	
	}
	if ($on>=$numerimondiv2){
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

###
for ($off=0; $off<$numberimoff; $off++){
	
	$randomnnum = (mt_rand($ranstartnum,$ranendnum))/1000;
	$sd = NormSInv($randomnnum);
	$nsd = $uncerarry[8]*$sd;
	$randomn = $nsd + 1; 
	$uncer8 = $uncerarry[8];
	
	array_push($ploty,$randomn);
	$timeset = $timerun/60;
	array_push($plotx,$timeset);
	array_push($ploterror,$uncer8);
	
	$timerun = $timerun + $exoplus;

}
	
$count = count($ploty);

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

	
	
	
	}##
}

}#star error
echo "Time";
echo $xdata;
echo "magnitude:<br><br>";
echo $ydata;
echo "magnitude uncertainty:<br><br>";
echo $unexo;
?>
</article>
</html>