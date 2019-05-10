<?php
####################################################
function dateare ($jdate){
	$j2000 = 946684800;# unix time on j2000 
	$jd2000= 2451544.5;# jd date of j2000
	$jdats = ($jdate-$jd2000)*60*60*24; # caculates the number of seconds between j2000 and the input date
	$times = $j2000 + $jdats;# adds the number of seconds from j2000 to the caculate the full unix time
	return $times;
}
###################
# AS this loads a lot of pages it takes a lot of time to load to a file is strored offline
#
$csvname = "http://var2.astro.cz/ETD/protocol.php"; # Website url
$csvfile = file_get_contents($csvname); # gets the data

$split = explode("Known transiters:</b></p>",$csvfile);  # gets the list of exoplent that var2 has 
$split = explode("<!-- menu vlevo KONEC -->",$split[1]); 
$namearr = explode("href='",$split[0]); 
$count = count($namearr); # counts the number of exoplents

#foreach($namearr as $nameplus1){
for($pop=1; $pop<$count; $pop++ ){
	$nameplus1 = $namearr[$pop]; # gets a exoplent name
	$nameplusa = explode("</a>",$nameplus1); # removes the a link 
	$nameplusb = explode("'",$nameplusa[0]);  # removes the '
	
	$urlshort = $nameplusb[0]; 
	$name = explode(">",$nameplusb[1]);
	$name  = $name[1]; # the name 

	$csvname = "http://var2.astro.cz/ETD/" . $urlshort; # the url of the exoplent
	
	$csvfile = file_get_contents($csvname); # gets the contents of the website URL 
	$file = (explode("DURATION (min)</b></td></tr><tr><td>",$csvfile)); # finds the section of the website that has the data
	$file = (explode("</tr>",$file[1]));
	$line = (explode("</td><td>",$file[0])); # both removes data that is not need

	$trasittime = $line[6]; 
	$trasittime = (explode("</td>",$trasittime));
	$trasittime = $trasittime[0]; # gets the transit time in mins

	$midtime = $line[3];
	$midtime = (explode("value='",$midtime));
	$midtime = (explode("'>",$midtime[1]));
	$midtime = $midtime[0]; # jd mid time
	$starttime = (dateare ($midtime))-($trasittime*30); # unixtime in start time
	if ($starttime>0){ # checks if start time is valid
		$stringecho = "";
		$stringecho .= $starttime; #adds unixtime in start time
		$stringecho .= ",";
 
		$stringecho .= $trasittime; # transit time in mins
		$stringecho .= ",";
		
		$ra = $line[0];
		$dec = $line[1];
		$ra = (explode("&nbsp;",$ra));#(hh mm ss)
		$dec = (explode("&nbsp;",$dec));# (dd mm ss)
		$ra = ($ra[0]*15)+($ra[1]*0.25)+($ra[2]*0.00416666666); # turns the ra into desimal
		$dec = ($dec[0])+($dec[1]*0.01666666667)+($dec[2]*0.0002777778); # turns the dec into desimal
		$stringecho .= $ra; # ra and dec in desimals
		$stringecho .= ",";
		$stringecho .= $dec;
		$stringecho .= ",";

		$period = $line[2];
		$period = (explode("value='",$period));
		$period = (explode("'>",$period[1]));
		$period = $period[0];
		$stringecho .= $period; # period in days
		$stringecho .= ",";

		$depth = $line[5];
		$depth = (explode("<b>",$depth));
		$depth = (explode("</b>",$depth[1]));
		$depth = $depth[0];
		$stringecho .= $depth; # transit depth magnitude
		$stringecho .= ",";

		$vmag = $line[4];
		$stringecho .= $vmag; # V-band magnitude (Johnson)
		$stringecho .= ",";
		$stringecho .= $name; # name of exoplent
		$stringecho .= ",0\n"; # sets log RHK at 0;
	
		if($vmag>0){ # checks if it has a V-band(Johnson)
			if($depth>0.0001){# checks if transit depth is grather than 0.1 mMag
				echo $stringecho;
			}
		}
	}
}

?>