<?php
// Start the session
session_start();
?>
<style>
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
table {
    width: 100%;
	text-align: center;
}
table  tr:nth-child(even){background-color: #f2f2f2;}
table  tr:nth-child(odd){background-color: #bedaf6;}

.row3rds{
	background-color:#bedaf6;
}
#latlong{
	background-color:#f2f2f2;
	
}
#backbut a {
	display:block;
	text-decoration:none;
	background:#004a95;
	width:100%;
	text-align: center;
	text-transform: uppercase;
	color:white;
}
@media(min-width:600px){
	#backbut a {
		width:33.33%;
	}
	
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

var laypredict = {
	autosize: false,
	width: viewportwidth,
	height: viewportheight*1.5,
	title:'Predicted plot',
	font: {
      size: 20,
    },
	yaxis: {
		title: 'Normalized Magnitude',
		titlefont: {
			size: 18,
		}
	},
	xaxis: {
		title: 'Time (minutes)',
		titlefont: {
			size: 18,
		}
	},
};

</script>
<?php
if( strlen($typeoflist0)>0){ # if typeoflist0 has string lenth of more then one, then a name has been entered
	$pick = gettransitdata (0,$typeoflist0); # gets the exoplanet data
	$typeoflist = 0; # sets which type of the list has been used
}
elseif( strlen($typeoflist1)>0){ # if typeoflist1 has string lenth of more then one, then a name has been entered
	$pick = gettransitdata (1,$typeoflist1); # gets the exoplanet data
	$typeoflist = 1; # sets which type of the list has been used
}
elseif( strlen($typeoflist2)>0){ # if typeoflist2 has string lenth of more then one, then a name has been entered
	$pick = gettransitdata (2,$typeoflist2); # gets the exoplanet data
	$typeoflist = 2; # sets which type of the list has been used
}
elseif( strlen($typeoflist3)>0){ # if typeoflist3 has string lenth of more then one, then a name has been entered
	$pick = gettransitdata (3,$typeoflist3); # gets the exoplanet data
	$typeoflist = 3; # sets which type of the list has been used
}
###################################  lat long
$savelatlong = array(); # saves the lat and long for later
array_push($savelatlong,$lat);
array_push($savelatlong,$long);
$type1 = strlen($arry[0])* strlen($arry[1])*strlen($arry[2]);
$type3 = strlen ($arry[3]) * strlen ($arry[4]) * strlen ($arry[5]) * strlen ($arry[6]) * strlen ($arry[7]); 
$type4 = strlen ($arry[8]) * strlen ($arry[9]) * strlen ($arry[10]) * strlen ($arry[11]) * strlen ($arry[12]) *strlen ($arry[13]) * strlen ($arry[17]) * strlen ($arry[18]) * strlen ($arry[19]) ;

if($observatorystring == 999){ # The manual enter lat long has been selected	
	$lat = $savelatlong[0];  # removes the saved lat and long
	$long = $savelatlong[1];
	$horzion = 30;	
}else{ # The observatory list has been used or phases entered
	if($type4 != 0){ # Phase 4 info has been entered
		$lat = $arry[0];
		$long = $arry[1];
		$horzion = $arry[2];
		$equation = array(); # the telecope data gets manipulated to be used in older functions
		array_push($equation,$arry[20]); # power on instrmental vs catalog
		array_push($equation,$arry[21]); # constant on instrmental vs catalog
		array_push($equation,$arry[18]); # power on instrmental vs airmass
		array_push($equation,$arry[8]); # bias frames
		array_push($equation,$arry[9]); # dark current
		array_push($equation,$arry[10]); # flat field
		array_push($equation,$arry[11]);  # Scintillation constant
		array_push($equation,$arry[12]); # sky noise const              
		array_push($equation,$arry[8]);  # sky noise const  analsis    
		array_push($equation,$arry[13]); # sky noise power analsis    
	}
	elseif ($type3 != 0){ # Phase 3 info has been entered
		$lat = $arry[0]; 
		$long = $arry[1];
		$horzion = $arry[2];
	}else{ # observatory data used
		$observatory = explode(";",$observatorystring); # splits the data up
		$lat = $observatory[0]; # selected menu has long as 0 to -360 rather than 180 to -180 changes this
		$long = $observatory[1];
		if($long<-180){
			$long = $long+360;
		}
		$horzion = 30;
	}
}
###############################

$errorarray = array(); # array set from error massages
$datacase = array(); # array that will hold the exoplanet data
array_push($datacase,$pick);
$yesbutnoo = 0; # sets to zero so that if there is an exoplanet
if (count($pick)== 0 ){ # checks to see if there was a problem selecting the exoplanet
	array_push($errorarray,"Error selecting exoplanet");
	$yesbutnoo = 1;  # no error
}
$yesbutno = 0 ; # sets 
if($typeoflist == 3){  # mag type is diffrent if the type list is TESS TOI (3)
	$magtype = "Tess";
}else{
	$magtype = "V";
}
$raround = round($pick[2],5); # rounds the data from the table later on
$decround = round($pick[3],5);
$maground = round($pick[6],5);
$depthround = round($pick[5],7);
$fulltime = $pick[1] + ($transitafter) + ($transitbefore); # caclues the full time of obsertion
if ($type3  !=  0 and $yesbutnoo == 0){ # if phase 3 or above data (has a exoplanet) has been entered then displays button to go to the create page
	echo "<div id=\"backbut\"><a href=\"create.php\">Create RTML</a></div>";
}
if($type4 != 0 and $yesbutnoo == 0){ # if phase 4 and has a exoplanet then gets the image times
	$imagearry = imagecal($exotime,$transitbefore,$transitafter,$pick,$arry);
}
$create1 = array();
if ($yesbutnoo == 0){ # has exoplanet writes data about the exoplanet
	echo"<div class = \"row3rds\" id=\"latlong\">";
	echo "<div>For $pick[7]</div><div>RA: $raround</div><div>DEC: $decround</div><div>$magtype-mag: $maground </div><div>Transit depth (Mag) : $depthround</div> <div>Transit time (m) : $pick[1]</div> <div>Full time (m) : $fulltime</div>";
	echo "<div>Latitude:	" . $lat;
	echo "</div><div>Longitude:	".$long;
	if ($type4 != 0){ # if phase 4 then gets the number of images on and off transit
		$imagesnum = images($arry,$exotime,$transitbefore,$transitafter,$pick);
		# 
		$numberimoffleft = $imagesnum[0]; 
		$numberimoffright = $imagesnum[1]; 
		$numberimon = $imagesnum[2];
		$numberimoff = $numberimoffleft + $numberimoffright;
		echo "</div><div>On images:	".$numberimon;
		echo "</div><div>Off images:	".$numberimoff;
		echo "</div><div>Exposure time (s) :	".$exotime;
	}
	echo "</div></div>";
	if($pick[8]>-4.6 and $pick[8]!= 0){ # log RHK tells the perion that the star could be active
		echo "<span style=\"color:red;\">&#9888 Active star &#9888 </span>";
	}
	$plotpredict = 0; # sets a value for the predict plot so that it only runs once
	$sunangle = 102; # sets sun ngle at 102 degress, -12 degress
	for ($lenthtime = 0; $lenthtime<$lenth; $lenthtime++){ # per day loop
		$now = findnowtime ($long,$start,$lenthtime); # gets the now time in timestamp that is ajusted for loction its set to 12am midday local time but in UTC time. set at 12 am midday local time so that its during the day 
		$perdayset = array(); # array that will hold the transiting exoplanet and there details
		$perdayset = transittimes ($lat,$long,$now,$datacase,0,$transitbefore,$transitafter,$horzion,$perdayset,$sunangle); # gets the transit time form that day (0 = loop)
		$returntable = tablemakeplanet ($perdayset,$lat,$long,$now,$transitbefore,$transitafter,$typeoflist,$sunangle); # produces a table for that day 
		$yesbutno1 = $returntable[0]; 
		$starttime = $returntable[3][0][0];
		if($type3 != 0 and strlen($starttime)!= 0 ){
			$endtime = $returntable[3][0][1];
			array_push($create1,$starttime);
			array_push($create1,$endtime);
		}
		if($type4 != 0 and $yesbutno1 == 1){	
			$uncerarry = tablephase4 ($imagearry,$starttime,$long,$lat,$equation,$arry,$mtar,$mref,$pick,$exotime);
			if($plotpredict == 0){
				$predictplotdata = plotpredictdata($imagearry,$transitbefore,$transitafter,$arry,$exotime,$pick,$uncerarry);
				plotpredictit ($predictplotdata);
				$plotpredict = 2;
			}		
		}
		if($altazin == 1){
			altazfun ($lenthtime,$returntable,$long,$lat,$sunangle);
		}			
		if($yesbutno1 == 1){
			$yesbutno = 1;
			if($moonin == 1){
				moonallset($lenthtime,$now,$lat,$long,$returntable,$sunangle);
			}
		}
	}
	if (($yesbutno)== 0){
		array_push($errorarray,"Exoplanet does not transit in that period");
	}
}
if (count($errorarray)>0){
	foreach($errorarray as $errorst){
		echo $errorst;
		echo "<br>";
	}	
}
$create = array();
array_push($create,$create1);
array_push($create,$pick);
array_push($create,$arry);
$_SESSION["create"] = $create;
?>