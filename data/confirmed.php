<?php
$csvname = "confirmed.csv"; #the name of the file 
$csvfile = file_get_contents($csvname); # gets the file open 
$lines = (explode("\n",$csvfile)); # split the csv per line

$cont = count($lines); # counts the number of lines in the csv so can do for loop

# function that takes in julian Data in form of 2457697.485 and caculates the unixtime stamp by working out the number of days since that data to J2000 ()
function dateare ($jdate){
	$j2000 = 946684800;# unix time on j2000 
	$jd2000= 2451544.5;# jd date of j2000
	$jdats = ($jdate-$jd2000)*60*60*24; # caculates the number of seconds between j2000 and the input date
	$times = $j2000 + $jdats;# adds the number of seconds from j2000 to the caculate the full unix time
	return $times;
}

$arrybig = array(); # creates an array that will contain arrays each of which has the data in it 

for ($m=359; $m<$cont;$m++){ # due to the comments in the csv the program must start after this point 
		
	$pass = (explode(",",($lines[$m]))); # splits the line by Commas to get each dat points
	$starname = $pass[1];  # gets the stars name
	$period = $pass[6];  # the period of the planet in days	
	$transittime = $pass[128]; # gets the transit time in days
	$atransitmid = dateare((($pass[132])-($transittime/2))); # gets the transit-mid point in julain data and first makes it the transit-start point then converts the julain data to unixtime so it can be manipulated easily.  
	$transittime = intval(($pass[128])*24*60); # gets the transit time in mins
	$ra = $pass[40]; #the ra and dec in desimals
	$dec = $pass[42];
	$depth = $pass[124]/100; # the transit depth is in % so divide by 100 to get to magnitude
	$filterV = $pass[257]; # gets the V-band johnson
  
  # the if statments below are designed to remove data points that are not right or do not make sence
	if ($atransitmid > 0 and $filterV > 0){ 
		if (strlen($starname) > 0 and $transittime > 0){
			if ($depth > 0.00001){
			
#	creates a array that then has the data instered into it  		
# starname is instead 3 times as histrorical problem that has been solved. 0-1 is used in single.php and 2-9 the rest fuctions. #1 used to be the starname with the exoplanet on it.
				$arry = array(); 
				array_push($arry,$starname);#0
				array_push($arry,$starname);#1
				array_push($arry,$atransitmid);#2
				array_push($arry,$transittime);#3
				array_push($arry,$ra);#4
				array_push($arry,$dec);#5
				array_push($arry,$period);#6
				array_push($arry,$depth);#7
				array_push($arry,$filterV);#8
				array_push($arry,$starname);#9
				array_push($arrybig,$arry); # pushes the data that is in the array to the big array
				}
			}
		}
	}
###############################
$sizeofit =  sizeof($arrybig); # counts the size of the big array

# this going to an array and then back out is so that the extra comma and new line at the end is stopped

for ($r=0; $r<$sizeofit; $r++){ # for loop of the size of the big array
	$arry = $arrybig[$r]; # selects the arry that in in the big array
	
	for ($c=0; $c<10;$c++){
		$pice = $arry[$c];
		echo $pice;
		if ($c != 9){ # stops comma at the end of the line  
			echo ",";
		}
	}
	if ($r != ($sizeofit-1)){ # stops new line at the end of the CSV
			echo"\n";	# new line 
	}
}
?>