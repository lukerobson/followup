<?php
###############################
function dateare ($jdate){ # function 
	$j2000 = 946684800;# unix time on j2000 
	$jd2000= 2451544.5;# jd date of j2000
	$jdats = ($jdate-$jd2000)*60*60*24; # caculates the number of seconds between j2000 and the input date
	$times = $j2000 + $jdats;# adds the number of seconds from j2000 to the caculate the full unix time
	return $times;
}
###############################
$csvname = "k2candidates.csv";# name of CSV file in the same folder as this file
$csvfile = file_get_contents($csvname); # gets the contents of the file
$file = (explode("\n",$csvfile)); # splits the file per line
$cont = count($file); # counts the number of lines

$arrybig = array(); # array that will contain arrays that each will have the data for the exoplents
for ($m=150; $m<$cont;$m++){ # for each line of the CSV file skiping lines 0-149 as there are comments in the CSV file on those lines
	# starting $m may change per file
	$pass = (explode(",",($file[$m]))); # splits the select line per comma
	# numbers in the pass may change if diffrent files
	$period = $pass[15]; # gets the period in days
	$transittime = $pass[27]; # get transit time in day
	$atransitmid = dateare((($pass[19])-($transittime/2))); # gets the midtime in JD and turns the time to unixtime at start time of transit
	$transittime = ($pass[27])*24*60; # gets transit time in days turns to mins
	$ra = $pass[12]; # Right assension
	$dec = $pass[14]; # Declanation
	$depth = $pass[23]/100; # transit depth from % to magnitude
	$filterV = $pass[96]; # Magnitude in V- band johonson
	$name  = $pass[1]; # name of star + letter
	
	# if checks to see if it is worth putting in the database or if an error
	if ($atransitmid > 1000000000 and $filterV > 0){
		if (strlen($name) > 0 and $transittime > 0){
			if ($depth > 0.0001){
			
				$arry = array(); # for each exoplent creastes a array
				#  adds the info to the array
				array_push($arry,$name);
				array_push($arry,$name);
				array_push($arry,$atransitmid);
				array_push($arry,$transittime);
				array_push($arry,$ra);
				array_push($arry,$dec);
				array_push($arry,$period);
				array_push($arry,$depth);
				array_push($arry,$filterV);
				array_push($arry,$name);
				# adds the arry data (exoplent data) to an array that contains all of them
				array_push($arrybig,$arry);

			}
		}
	}
}


###############################
$sizeofit =  sizeof($arrybig); # counts the of good exoplents 

for ($r=0; $r<$sizeofit; $r++){ # for loop for each good exoplent
	$line = $arrybig[$r]; # gets a exoplent data
	
	for ($c=0; $c<10;$c++){ #
		$pice = $line[$c]; # gets the pice out the line and echos it
		echo $pice;
		if ($c != 9){# stops the comma at the end of the line
			echo ","; # comma so thats its a CSV file
		}
	}
	echo"\n"; # new line
}

?>

