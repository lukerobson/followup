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
# array that contains the names of the exoplent of the website 
$namearr = array("CoRoT-1 b","CoRoT-10 b","CoRoT-11 b","CoRoT-12 b","CoRoT-13 b","CoRoT-17 b","CoRoT-18 b","CoRoT-19 b","CoRoT-2 b","CoRoT-20 b","CoRoT-3 b","CoRoT-4 b","CoRoT-5 b","CoRoT-6 b","CoRoT-8 b","CoRoT-9 b","EPIC 218916923 b","EPIC 228735255 b","EPIC-203771098 b","EPIC-203771098 c","EPIC-210957318 b","EPIC-211089792 b","EPIC-212110888 b","GJ1214 b","GJ3470 b","GJ436 b","HAT-P-1 b","HAT-P-10/WASP-11 b","HAT-P-11 b","HAT-P-12 b","HAT-P-13 b","HAT-P-14 b","HAT-P-15 b","HAT-P-16 b","HAT-P-17 b","HAT-P-18 b","HAT-P-19 b","HAT-P-2 b","HAT-P-20 b","HAT-P-21 b","HAT-P-22 b","HAT-P-23 b","HAT-P-24 b","HAT-P-25 b","HAT-P-26 b","HAT-P-27/WASP-40 b","HAT-P-28 b","HAT-P-29 b","HAT-P-3 b","HAT-P-30/WASP-51 b","HAT-P-31 b","HAT-P-32 b","HAT-P-33 b","HAT-P-34 b","HAT-P-35 b","HAT-P-36 b","HAT-P-37 b","HAT-P-38 b","HAT-P-39 b","HAT-P-4 b","HAT-P-40 b","HAT-P-41 b","HAT-P-42 b","HAT-P-43 b","HAT-P-44 b","HAT-P-45 b","HAT-P-46 b","HAT-P-49 b","HAT-P-5 b","HAT-P-50 b","HAT-P-51 b","HAT-P-52 b","HAT-P-53 b","HAT-P-54 b","HAT-P-55 b","HAT-P-56 b","HAT-P-57 b","HAT-P-6 b","HAT-P-65 b","HAT-P-66 b","HAT-P-67 b","HAT-P-7 b","HAT-P-8 b","HAT-P-9 b","HATS-1 b","HATS-11 b","HATS-12 b","HATS-18 b","HATS-19 b","HATS-20 b","HATS-21 b","HATS-22 b","HATS-23 b","HATS-24 b","HATS-25 b","HATS-26 b","HATS-27 b","HATS-28 b","HATS-29 b","HATS-30 b","HATS-31 b","HATS-32 b","HATS-33 b","HATS-34 b","HATS-35 b","HATS-36 b","HATS-39 b","HATS-40 b","HATS-41 b","HATS-42 b","HATS-43 b","HATS-44 b","HATS-45 b","HATS-46 b","HATS-5 b","HATS-50 b","HATS-51 b","HATS-52 b","HATS-53 b","HATS-6 b","HATS-P-7 b","HD149026 b","HD17156 b","HD189733 b","HD209458 b","HD80606 b","HD97658 b","K2-114 b","K2-115 b","K2-30 b","K2-34 b","KELT-1 b","KELT-10 b","KELT-11 b","KELT-15 b","KELT-16 b","KELT-17 b","KELT-2A b","KELT-3 b","KELT-4A b","KELT-6 b","KELT-7 b","KELT-8 b","KELT-9 b","Kepler-10 c","Kepler-11 c","Kepler-11 d","Kepler-11 e","Kepler-11 f","Kepler-11 g","Kepler-12 b","Kepler-14 b","Kepler-15 b","Kepler-16A b","Kepler-16B b","Kepler-17 b","Kepler-18 c","Kepler-18 d","Kepler-19 b","Kepler-20 c","Kepler-20 d","Kepler-4 b","Kepler-448 b","Kepler-5 b","Kepler-6 b","Kepler-7 b","Kepler-8 b","Kepler-9 b","Kepler-9 c","KOI 0135 b","KOI 0196 b","KOI 0204 b","KOI 0428 b","LUPUS-TR3 b","Mascara-1 b","Mascara-2 b","OGLE-TR-10 b","OGLE-TR-111 b","OGLE-TR-113 b","OGLE-TR-132 b","OGLE-TR-182 b","OGLE-TR-211 b","OGLE-TR-56 b","OGLE-TR-L9 b","Qatar-1 b","Qatar-2 b","Qatar-3 b","Qatar-4 b","Qatar-5 b","TrES-1 b","TrES-2 b","TrES-3 b","TrES-4 b","TrES-5 b","WASP-1 b","WASP-10 b","WASP-100 b","WASP-101 b","WASP-102 b","WASP-103 b","WASP-104 b","WASP-105 b","WASP-106 b","WASP-107 b","WASP-108 b","WASP-109 b","WASP-110 b","WASP-111 b","WASP-112 b","WASP-113 b","WASP-114 b","WASP-117 b","WASP-118 b","WASP-119 b","WASP-12 b","WASP-120 b","WASP-121 b","WASP-122 b","WASP-123 b","WASP-124 b","WASP-126 b","WASP-127 b","WASP-129 b","WASP-13 b","WASP-130 b","WASP-131 b","WASP-132 b","WASP-133 b","WASP-134 b","WASP-136 b","WASP-137 b","WASP-138 b","WASP-139 b","WASP-14 b","WASP-140 b","WASP-141 b","WASP-142 b","WASP-143 b","WASP-146 b","WASP-15 b","WASP-151 b","WASP-153 b","WASP-156 b","WASP-157 b","WASP-16 b","WASP-161 b","WASP-163 b","WASP-167 b","WASP-17 b","WASP-170 b","WASP-18 b","WASP-19 b","WASP-2 b","WASP-20 b","WASP-21 b","WASP-22 b","WASP-23 b","WASP-24 b","WASP-25 b","WASP-26 b","WASP-28 b","WASP-29 b","WASP-3 b","WASP-31 b","WASP-32 b","WASP-33 b","WASP-34 b","WASP-35 b","WASP-36 b","WASP-37 b","WASP-38 b","WASP-39 b","WASP-4 b","WASP-41 b","WASP-42 b","WASP-43 b","WASP-44 b","WASP-45 b","WASP-46 b","WASP-47 b","WASP-48 b","WASP-49 b","WASP-5 b","WASP-50 b","WASP-52 b","WASP-54 b","WASP-55 b","WASP-56 b","WASP-57 b","WASP-58 b","WASP-59 b","WASP-6 b","WASP-60 b","WASP-61 b","WASP-62 b","WASP-63 b","WASP-64 b","WASP-65 b","WASP-66 b","WASP-67 b","WASP-68 b","WASP-69 b","WASP-7 b","WASP-70A b","WASP-71 b","WASP-72 b","WASP-73 b","WASP-74 b","WASP-75 b","WASP-76 b","WASP-77 b","WASP-78 b","WASP-79 b","WASP-8 b","WASP-80 b","WASP-82 b","WASP-83 b","WASP-84 b","WASP-85A b","WASP-86/Kelt-12 b","WASP-87 b","WASP-88 b","WASP-89 b","WASP-90 b","WASP-91 b","WASP-92 b","WASP-93 b","WASP-94A b","WASP-95 b","WASP-96 b","WASP-97 b","WASP-98 b","WASP-99 b","WD 1145+017 b","XO-1 b","XO-2 b","XO-3 b","XO-4 b","XO-5 b","XO-6 b");

##############################################
foreach($namearr as $nameplus){

	$nameset = (str_split($nameplus)); #splits the name into each charater
	$namecount = strlen($nameplus); # gets the lenth of the string

	$star = ""; #gets the name of the star by adding each letter and removes the last 2 charlaters of the exoplents name
	for($c=0;$c<($namecount-2);$c++){
		$star .= $nameplus[$c];
	}

	$letter = $nameset[($namecount-1)]; # gets the letter of the exoplent

	# VAR 2 website of each exoplent
	$csvname = "http://var2.astro.cz/ETD/etd.php?STARNAME=" . $star . "&PLANET=" . $letter ;
	$csvfile = file_get_contents($csvname); # gets the contents of the website URL 
	$file = (explode("DURATION (min)</b></td></tr><tr><td>",$csvfile)); # finds the section of the website that has the data
	$file = (explode("</tr>",$file[1]));
	$line = (explode("</td><td>",$file[0])); # both removes data that is not need

	$stringecho = ""; # stars a string that will contain the exoplent data
	$stringecho .= $nameplus; # name of exoplent
	$stringecho .= ",";
	$stringecho .= $nameplus; # name of exoplent
	$stringecho .= ",";

	$trasittime = $line[6];
	$trasittime = (explode("</td>",$trasittime));
	$trasittime = $trasittime[0]; # gets the transit time in mins

	$midtime = $line[3];
	$midtime = (explode("value='",$midtime));
	$midtime = (explode("'>",$midtime[1]));
	$midtime = $midtime[0]; # jd mid time
	$starttime = (dateare ($midtime))-($trasittime*30); # unixtime in start time
	if ($starttime>0){ # checks if start time is valid
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
		$stringecho .= $nameplus; # name of exoplent
		$stringecho .= ",0\n"; # sets log RHK at 0;

		if($vmag>0){ # checks if it has a V-band(Johnson)
			if($depth>0.0001){# checks if transit depth is grather than 0.1 mMag
				echo $stringecho;
			}
		}
	}
}

?>