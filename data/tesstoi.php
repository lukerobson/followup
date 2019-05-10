<?php require_once('../main/functions.php');  # gets the function page
	
$csvname = "https://exofop.ipac.caltech.edu/tess/download_toi.php?sort=toiasc&output=csv"; # gets the 
$content = file_get_contents($csvname); # gets the data from the URL
$pop2 = (explode("\n",$content)); # split it per line
$cont = count($pop2); # count the number of lines
for ($m=1; $m<($cont-7);$m++){ # -7 due to lines at the bottom of the CSV that are empty
		
		$pass = (explode(",",($pop2[$m]))); # splites the line the a comma so get array that has all the info in an array
		
		$tic = $pass[0];  # gets the values to varibles so that its see to see what they are
		$toi = $pass[1];#
		$master = $pass[2];
		$sg1a = $pass[3];
		$sg1b = $pass[4];
		$tessdis = $pass[9];
		$tfopwg = $pass[10];
		$tessmag = $pass[11]; #
		$raex = (explode(":",$pass[16])); # RA and DEC is in HH:MM:SS needs to be in desimal so turns it to desimal
		$decex = (explode(":",$pass[17]));
		$ra = ($raex[0]*15)+($raex[1]*0.25)+($raex[2]*0.0042);
		$dec = ($decex[0])+($decex[1]*(0.5/30))+($decex[2]*(0.0083/30));
		
		$midtime = dateare($pass[22]); # mid time from JD to unixtime
		$miderror = ($pass[23]*24*60); # mid in mins
		$period = $pass[24]; # period in days
		$perioderr = ($pass[25]*24*60); # period error in min
		$duration = ($pass[26]*60); # duration in mins
		$durerror = ($pass[27]*60); # duration in mins
		$depth = $pass[28]/1000; # # depth into mag
		$ppm = $pass[30]; # PPM
		$comments = $pass[48]; # comments
		if ($period>0){ # checks if period is valed as has had non value on it
			if ($m == 1){ # if the first line then no need for new line
		
			}else{ # else echoes new line
				echo "\n";
			}

		echo ($midtime - (($duration*60)/2)); # turns unixtime mid to start time
		echo ",";
		echo $duration;
		echo ",";
		echo $ra;
		echo ",";
		echo $dec;
		echo ",";
		echo $period;
		echo ",";
		echo $depth;
		echo ",";
		echo $tessmag;
		echo ",";
		echo $toi;
		echo ",";
		echo "0,";
		echo $miderror ;
		echo ",";
		echo $perioderr;
		echo ",";
		echo $durerror;
		echo ",";
		echo $master;
		echo ",";
		echo $sg1a;
		echo ",";
		echo $sg1b;
		echo ",";
		echo $tessdis;
		echo ",";
		echo $tfopwg;
		echo ",";
		echo $ppm;
		echo ",";
		echo $comments;	
		echo ",";
		echo $tic;
	}
}
?>