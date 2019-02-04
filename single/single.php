<?php
// Start the session
session_start();
?><!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel = "stylesheet" href = "../main/stylemain.css" type = "text/css"/>
	<title>Predict - Single - Transit Follow Up Tool</title>
</head>
<?php require_once('../main/title.html'); ?>
<h3>Single</h3>
<?php require_once('../main/navtop.html'); ?>

<style>
.row{
	display:grid;
	grid-template-columns: 50% 50%;
	text-align: center;
}
.row> div{
	grid-gap: 2em;
	text-align: center;
}

</style>

<article>
<div id="centertext">This page predicts the viewable transits for a selected exoplanet.</div>
<div class = "row">
<div>
<form method="post" action="singleout.php">
<?php
$arry = $_SESSION["datarun"];
$long = $arry[1];
$LAT = $arry[0];
$horzion = $arry[2];
$lat =$LAT;

$csvname = "all.csv";
$csvfile = file_get_contents($csvname);
$file = (explode("\n",$csvfile));
$datacase = array();

$type1 = strlen($long)* strlen($LAT)*strlen($horzion);
$type2 = strlen ($arry[8]) * strlen ($arry[9]) * strlen ($arry[10]) * strlen ($arry[11]) * strlen ($arry[12]) *strlen ($arry[13]) * strlen ($arry[14]) * strlen ($arry[15]) * strlen ($arry[16]) *  strlen ($arry[17]) *  strlen ($arry[18]) *  strlen ($arry[19]) *  strlen ($arry[20]) *  strlen ($arry[21]) ;
if ($type1 == 0){#no info
	
	echo "Select the name of the target star<br>";
	echo"<select name=\"star\">";
	
	foreach ($file as $line) {
	$dataline = array();
	$each = (explode(",",$line));
			$eachrun= $each[9];
			array_push($datacase,$eachrun);
	}
	

	foreach ($datacase as $starname) {
	
	echo "<option value=\"" . $starname .  "\">" . $starname . "</option>";
	}
	echo "</select><br>";
	
	echo "Latitude:<br> ";
	echo "<input type=\"number\" name=\"lat\" min=\"-90\" max=\"90\" step=\"0.0001\" value=\"51.4826\">";
	
	echo "<br>Longitude:<br>";
	echo "<input type=\"number\" name=\"long\" min=\"-180\" max=\"180\" step=\"0.0001\" value=\"-0.09\">";
	
	echo"</div><div>";
	
	
	echo "How many months of observations (1-12):<br>";
	echo "<input type=\"number\" name=\"mouth\" min=\"1\" max=\"12\" value=\"12\" step=\"1\"><br>";

}else{
	if ($type2 == 0){ #1
	
	echo "Select the name of the target star<br>";
	echo"<select name=\"star\">";
	
	foreach ($file as $line) {
	$dataline = array();
	$each = (explode(",",$line));
			$decrun= $each[5];
			if ($decrun > ($LAT-90)){
			$eachrun= $each[9];
			array_push($datacase,$eachrun);
			}
	}
	

	foreach ($datacase as $starname) {
	
	echo "<option value=\"" . $starname .  "\">" . $starname . "</option>";
	}
	echo "</select>";
	
	echo"</div><div>";
	
	
	
	echo "How many months of observations (1-12):<br>";
	echo "<input type=\"number\" name=\"mouth\" min=\"1\" max=\"12\" value=\"12\" step=\"1\"><br>";
	
	}else{#4 & 5
	
		echo "Select the name of the target star<br>";
	echo"<select name=\"star\">";
	
	foreach ($file as $line) {
	$dataline = array();
	$each = (explode(",",$line));
			$decrun= $each[5];
			if ($decrun > ($LAT-90)){
			$eachrun= $each[9];
			array_push($datacase,$eachrun);
			}
	}
	

	foreach ($datacase as $starname) {
	
	echo "<option value=\"" . $starname .  "\">" . $starname . "</option>";
	}
	echo "</select><br>";
	
	echo "Enter target star catalogue magnitude<br>";# <!-- gets the target magnitude from user//-->
	echo "<input type=\"number\" name=\"mtar\" min=\"4\" max=\"20\" step=\"0.001\">"; 
	echo "<br>Enter reference star catalogue magnitude<br>"; # <!-- gets the reference magnitude from user//-->
	echo "<input type=\"number\" name=\"mref\" min=\"4\" max=\"20\" step=\"0.001\">"; 

	
	
	echo"</div><div>";
	echo "Select exposure time in seconds <br>";
	echo "<select name=\"exotime\">";# <!-- option menu with exoser times of bayfordbury //-->
	echo "<option value=1>1</option>		
	<option value=2>2</option>
	<option value=3>3</option>
	<option value=4>4</option>
	<option value=5>5</option>
	<option value=10>10</option>
	<option value=15>15</option>
	<option value=20>20</option>
	<option value=30>30</option>
	<option value=45>45</option>
	<option value=60>60</option>
	<option value=90>90</option>
	<option value=120>120</option>
	<option value=180>180</option>
	<option value=240>240</option>
	<option value=300>300</option>
</select><br>";
	
	
	
	echo "<br>How many months of observations (1-12):<br>";
	echo "<input type=\"number\" name=\"mouth\" min=\"1\" max=\"12\" value=\"12\" step=\"1\"><br>";
		
	}
	
}
?>
Time before transit (mins): <br>
<input type="number" name="transitbefore" min="1" max="300" step="1" value="30" ><br>
Time after transit (mins): <br>
<input type="number" name="transitafter" min="1" max="300" step="1" value="30" ><br>


</div>
</div>

<div id= "subm"><input type="submit"></div>
</form> 
</article>
</html>