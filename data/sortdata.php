<?php

$var2= ""; # data from Czech Astronomical Society

$org = ""; # data from Exoplanet Data Explorer

$exonasa = ""; # data from NASA Exoplanet Archive

$bigarry = array(); # # array that will contain arrays that each will have the data for the exoplents

$org = (explode("\n",$org)); # splits the string by lines
foreach($org as $line){ 
	$string = $line; # gets the line and adds the source name
	$string .= ",";
	$string .= "Exoplanet Data Explorer";
	array_push($bigarry,$string); # add to the bigarry
}

$exonasa = (explode("\n",$exonasa));# splits the string by lines
foreach($exonasa as $line){
	$string = $line;# gets the line and adds the source name
	$string .= ",";
	$string .= "NASA Exoplanet Archive";
	array_push($bigarry,$string);# add to the bigarry
}


$var2 = (explode("\n",$var2));# splits the string by lines
foreach($var2 as $line){
	$string = $line;# gets the line and adds the source name
	$string .= ",";
	$string .= "Czech Astronomical Society";
	array_push($bigarry,$string);# add to the bigarry
}


sort($bigarry); # sorts the bigarry so that its in alphabet order
$lineset = (explode(",",$bigarry[0]));# splits the line by comma
$nameold = $lineset[0]; # sets the insiall value of the name 

foreach($bigarry as $line){ 
	$lineset = (explode(",",$line));# splits the line by comma
	$namenew = $lineset[0];# gets the new name of exoplanet
	if(strcmp($nameold,$namenew)==0){ # makes sure that if the exoplanet has been echoes before on a diffrent source it will not be reperted
	
	}else{
	echo $line; # echoes exoplanet data and than a new line
	echo "\n";
	
	$lineset = (explode(",",$line));# splits the line by comma
	$nameold = $lineset[0]; # sets the new name to the old 
	
	}
	
	
}
?>