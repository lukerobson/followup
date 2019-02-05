<?php require_once('../main/functions.php'); 
	
$csvname = "https://exofop.ipac.caltech.edu/tess/download_toi.php?sort=toiasc&output=csv"; #the name of URL that conatains the data in csv form
$content = file_get_contents($csvname);# gets the data in the websit and saves it to content  
$lines = (explode("\n",$content));# split the csv per line

$cont = count($lines); # counts the number of lines in the csv so can do for loop
for ($m=1; $m<($cont-7);$m++){ # starts at 1 due to the header of the csv file removes 7 as there is 7 new lines in the csv url
		
		$pass = (explode(",",($lines[$m]))); # splits the line by Commas to get each dat points
		
		$tic = $pass[0]; # the number in the target catalog
		$toi = $pass[1];# the number of the target of intrest 
		$master = $pass[2]; # the priority number master for all ones
		$sg1a = $pass[3]; # the priority number of SG1a
		$sg1b = $pass[4]; # the priority number of SG1b
		$tessdis = $pass[9]; 
		$tfopwg = $pass[10];
		$tessmag = $pass[11]; # tess magnitude
		
		#next 4 get the ra and dec and turns them into desimal values from hour, min and sec
		$raex = (explode(":",$pass[16])); 
		$decex = (explode(":",$pass[17]));
		$ra = ($raex[0]*15)+($raex[1]*0.25)+($raex[2]*0.0042);
		$dec = ($decex[0])+($decex[1]*(0.5/30))+($decex[2]*(0.0083/30));
		
		$midtime = dateare($pass[22]); # gets the julian data of the mid transit time and turns it in to unixtime
		$period = $pass[24]; # period of the TOI
		$perioderr = $pass[25]; # error of period
		$duration = ($pass[26]*60); # duration of transit to mins from hours
		$durerror = ($pass[27]*60); # duration of transit error to mins from hours
		$depth = $pass[28]/1000; # depth to magnitude
		$ppm = $pass[30];
		$comments = $pass[48]; # comments on the TOI
		if ($period>0){ # makes sure that the period is real
		if ($m == 1){ # stops new line at the start
		
		}else{
			echo "\n"; # new line
		}
		# echos all the infomation out separated by commas
		echo $toi; 
		echo ",";
		echo $toi;
		echo ",";
		echo ($midtime - (($duration*60)/2)); # coverts the midtime in unix to starttime in unix 
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
		echo $tic;
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
		echo $perioderr;
		echo ",";
		echo $durerror;
		echo ",";
		echo $ppm;
		echo ",";
		echo $comments;
		}
}
?>