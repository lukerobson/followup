<?php
// Start the session
session_start();?>
<style>
table {
    width: 100%;
	text-align: center;
}
table  tr:nth-child(even){background-color: #f2f2f2;}
table  tr:nth-child(odd){background-color: #bedaf6;}
article {
 font-family: "Arial",sans-serif;
 width:100%;
 padding-top:15px;
 padding-left:0%;
 padding-right:0%;
 font-size:16pt;
 line-height: 200%;
 word-wrap: break-word;
 
}
.row3rds{
	background-color:#0073CF;
	color:white;
}
#latlong{
	background-color:#f2f2f2;
	color:black;
}

</style>
<script>
var viewportwidth;
var viewportheight;
if (typeof window.innerWidth != 'undefined'){
	viewportwidth = window.innerWidth-50,
    viewportheight = window.innerHeight/2
}
var layouttime = {
	autosize: false,
	width: viewportwidth,
	height: viewportheight,
	title:'Timeline',
	font: {
      size: 20
    },
	 yaxis: {
		title: 'Exoplanet',
		autotick: false,
		titlefont: {
			size: 18,
		}
		 
	},
	 xaxis: {
		title: 'Time (Hrs after sunset)',
		titlefont: {
			size: 18,
		}
	},
};

var layoutalt = {
	autosize: false,
	width: viewportwidth,
	height: viewportheight,
	title:'Alt & AZ',
	font: {
      size: 20
    },
	 yaxis: {
		title: 'AZ (Degrees)',
		titlefont: {
			size: 18,
		}
		 
	},
	 xaxis: {
		title: 'Alt (Degrees)',
		titlefont: {
			size: 18,
		}
	},
};

var layoutalttime = {
	autosize: false,
	width: viewportwidth,
	height: viewportheight,
	title:'Alt Time',
	font: {
      size: 20
    },
	 yaxis: {
		title: 'AZ (Degrees)',
		titlefont: {
			size: 18,
		}
		 
	},
	 xaxis: {
		title: 'Time From Sunset (Min)',
		titlefont: {
			size: 18,
		}
	},
};

var layoutmoon = {
	autosize: false,
	width: viewportwidth,
	height: viewportheight,
	title:'Moon',
	font: {
      size: 20
    },
	yaxis: {
		title: 'Separation angle (Degrees)',
		titlefont: {
			size: 18,
		}
		 
	},
	 xaxis: {
		title: 'Time (Hrs after sunset)',
		titlefont: {
			size: 18,
		}
	},
};
</script>
<?php

if($moonin == 1){ # if the moon vaule is set (set == 1) then the lenth is set to one due to loading time
	$lenth = 1;
}
if($depthtype == 1){ # sets the output of the depth type string either Mag or mMag 
	$depthtypestring ="Mag";
}else{
	$depthtypestring ="mmag";
}
$savelatlong = array(); # saves the lat and long so that the datarun(Phases) data can be checked if valid
array_push($savelatlong,$lat);
array_push($savelatlong,$long);

$arry = $_SESSION["datarun"];
$long = $arry[1];
$lat = $arry[0];
$horzion = $arry[2];
$type1 =  strlen($arry[0])* strlen($arry[1])*strlen($arry[2]);
if($observatorystring == 999){# the observatory list is set to manual so it needs the entered lat long	
	$lat = $savelatlong[0]; # gets out the saved lat long
	$long = $savelatlong[1];
	$horzion = 30;	
}else{ # either observatory list has been used or phase info has been entered
	if($type1 != 0){ # phase info has been entered
		$lat = $arry[0];
		$long = $arry[1];
		$horzion = $arry[2];
	}
	else{ # observatory list has been used
		$observatory = explode(";",$observatorystring); # splits the observatory by its divider
		$lat = $observatory[0]; # gets out the lat and long out of it
		$long = $observatory[1];
		if($timezonerun == 1){
			$timezonetime = $observatory[2]; # gets the time zone code for php
			$timezonerunyes = 1;	# sets value so that the timezone has been used
		}
		if($long<-180){ # selected menu has long as 0 to -360 rather than 180 to -180
			# changes this
			$long = $long+360;
		}
		$horzion = 30; # sets the horzion at 30 degrees
	}
}

$errorarray = array(); # sets a array that will hold any error problems
$datacase = getdatacsv($typeoflist,$mindepth); # gets the data of all the exoplanets with each exoplanets data in an array so array of array
$datasizeloop = count($datacase);	# counts the number of exoplanets
if (count($datacase)==0){ # if there are no exoplanets in the datacase then it sets an error massage normaly due to minimum depth set to high
	array_push($errorarray,"No exoplanets with that minimum depth");
}

$sunangle = $_POST["sunsetnumber"]; # gets the sun angle
if((count($sunangle)) < 1){ # if the sun angle is not set it turns to 102 degrees i.e twilight
	$sunangle = 102;
}

$yesbutno = 0; # sets a value to see if any exoplanets transit and if none then later it does a error massage

for ($lenthtime = 0; $lenthtime<$lenth; $lenthtime++){ # loop per day
	$now = findnowtime ($long,$start,$lenthtime); # gets the now time in timestamp that is ajusted for loction its set to 12am midday local time but in UTC time. set at 12 am midday local time so that its during the day 
	$perdayset = array(); # array that will hold the transiting exoplanets and there details
	for ($loop = 0; $loop <$datasizeloop; $loop++){ # loop per exoplanet
		date_default_timezone_set("UTC"); # sets the timezone to UTC
		$perdayset = transittimes ($lat,$long,$now,$datacase,$loop,$transitbefore,$transitafter,$horzion,$perdayset,$sunangle);# full transit and wings code 
		
		if($timezonerunyes == 1 and (strlen($timezonetime)) > 0  ){ # if local time has been set and the time zone name has a lenth
			date_default_timezone_set($timezonetime); # sets the timezone to selected one 
		}
		if ($loop == ($datasizeloop-1)){ # if at end at -1 due to loop starting at 0 and datasizeloop starting at 1 
			if($lenthtime == 0){ # only the first time round 
				# echos lat long and timezone
				echo"<div class = \"row3rds\" id=\"latlong\">";
				echo "<div>";
				echo "Latitude:	" . $lat;
				echo "</div><div>";
				echo "Longitude:	".$long;
				echo "</div><div>";
				echo "Timezone: "; 
				if($timezonerunyes == 1 and (strlen($timezonetime)) > 0 ){
					#
					echo $timezonetime;
					date_default_timezone_set($timezonetime);
				}else{
					# else it uses UTC
					echo "UTC";
				}
				echo "</div></div>";
			}
			
			$returntable = tablemake ($perdayset,$lat,$long,$now,$transitbefore,$transitafter,$depthtypestring,$depthtype,$typeoflist,$sunangle); # makes table for the all exoplanets that transit in that night
			
			if($typeoflist == 3 and (count($perdayset)) != 0){ # if TESS and there are transit it outputs more data about them.
				tablemaketess ($perdayset);
			}
			
			date_default_timezone_set("UTC"); # re sets the time zone to UTC as the plots will not work with local time but due to them being set at the start of twilight it does not matter
			
			if ($timelinein == 1){ # 1 equals selected
				timelinefun ($lenthtime,$returntable); # timeline plot
			} 
			if($altazin == 1){ # 1 equals selected
				altazfun ($lenthtime,$returntable,$long,$lat,$sunangle); # altitude and az plot
			}

			if($moonin == 1){ # 1 equals selected
				$moonran = moonallset($lenthtime,$now,$lat,$long,$returntable,$sunangle);
				# moon plot
			}			
			$yesbutno1 = $returntable[0]; # if 1 then there are transits
			if($yesbutno1 == 1){
				$yesbutno = 1; # sets yesbutno to 1 so that the no exoplanet transit does not output
			}
		} # end of the if exoplanet end
	}# end of exoplanet loop 
}#end of loop of days loop
if (($yesbutno)== 0){ # if set to zero then there are no exoplanets in that period
	array_push($errorarray,"No exoplanets transit in that period from that data source");
}
if (count($errorarray)>0){ # if there is an error massage then it echos it out
	foreach($errorarray as $errorst){
		echo $errorst;
		echo "<br>";
	}	
}
?>