<?php
####################################################
function dateare ($jdate){
	$j2000 = 946684800;# unix time on j2000 
	$jd2000= 2451544.5;# jd date of j2000
	$jdats = ($jdate-$jd2000)*60*60*24; # caculates the number of seconds between j2000 and the input date
	$times = $j2000 + $jdats;# adds the number of seconds from j2000 to the caculate the full unix time
	return $times;
}
####################################################
$csvname = "http://exoplanets.org/csv-files/exoplanets.csv"; # name of URL where CSV file is
$csvfile = file_get_contents($csvname); # gets the contents of the file
$file = (explode("\n",$csvfile));# splits the file per line
$count = count($file);# counts the number of lines

$datacase = array();# array that will contain arrays that each will have the data for the exoplents

for ($x=1;$x<$count;$x++){ # for each line of the CSV file skiping line 0 as there is a header in the CSV file on those lines
	$line = (explode(",",$file[$x]));# splits the select line per comma
	if($line[40]>0){
		$T0 = dateare ($line[274]); #turns the JD mid time to unix time mid time
		if($T0>0){	
			$string = "";
			$string .= $line[152];# NAME of exoplanet
			$string .= ",";
			$string .= $line[152];# NAME of exoplanet
			$string .= ",";
	
			$string .= ($T0 -(($line[280]*24*60*60)/2)); # start time in unixtime
			$string .= ",";
			$string .= ($line[280]*24*60);# =>  transit duration in days to mins
			$string .= ",";
			$ra = $line[182];# => # right assension
			$ra = (explode(":",$ra)); # splits the ra (hr:mm:ss) so it can be turns to desimals
	
			$string .= ($ra[0]*15)+($ra[1]*0.25)+($ra[2]*0.00416666666); # turns ra into desimal
	
			$string .= ",";
			$string .= $line[32];# => declanation in desimals
			$string .= ",";
			$string .= $line[168]; # => period in days 
			$string .= ",";
			$string .= $line[40]; #=> transit depth in magnitude  
			$string .= ",";
			$string .= $line[303]; #=> V-band (Jonhson) 
			$string .= ",";
			$string .= $line[152];#N AME of exoplanet
			$string .= ",";
			if($line[183]<0){ # checks if RHK has a value if it does then it gets that value if not then set to zero as not a valid answer of other codes
				$string .= $line[183];# => RHK Y
			}else{
				$string .= "0"; 
			}
	
			$string .= "\n";
			if($line[40]>0.0001){ # transit depth bigger then 0.1 mMag
				if($line[303]>0){ # has a V-band (Jonhson) value
					array_push($datacase,$string); # pushes the string containing the exoplanet data to an array
				}
			}
		}
	}
}

sort($datacase); # sorts the data so that its in alphabetical order

foreach($datacase as $line){ # echos out each line
	echo $line;
	
}
?>