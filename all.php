<?php
// Start the session
session_start();
?><!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel = "stylesheet" href = "../main/stylemain.css" type = "text/css"/>
	<title>Predict - All - Transit Follow Up Tool</title>
</head>
<?php require_once('../main/title.html'); ?>
<h3>All</h3>
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
<div id="centertext">This page predicts the viewable transits from a specified location.</div>
<div class = "row">
<div>
<form method="post" action="allout.php">
Start in how many days <br>
<input type="number" name="start" min="-366" max="366" step="1" value="0" ><br>
Data set: <br>
<select name="csvname">
  <option value="confshort.csv">Confirmed</option>
  <option value="k2can.csv">K2 candidates</option>
  <option value="all.csv">Both</option>

</select>
<?php
$arry = $_SESSION["datarun"];
$long = $arry[1];
$LAT = $arry[0];
$horzion = $arry[2];
$lat =$LAT;
	
$typeofdata = strlen($long)* strlen($LAT)*strlen($horzion);

?>
<br>Time before transit (mins): <br>
<input type="number" name="transitbefore" min="1" max="300" step="1" value="30" ><br>
Time after transit (mins): <br>
<input type="number" name="transitafter" min="1" max="300" step="1" value="30" ><br>
</div>
<div>

Duration of the observations (in days)<br>
<input type="number" name="lenth" min="1" max="366" value="30"step="1" >
<?php
if ($typeofdata==0){
	echo "<br>";
	echo "latitude <br>";
	echo "<input type=\"number\" name=\"lat\" min=\"-90\" max=\"90\" step=\"0.00001\" value=\"51.4826\">";
	echo "<br>";
	echo "longitude <br>";
	echo "<input type=\"number\" name=\"long\" min=\"-180\" max=\"180\" step=\"0.00001\" value=\"-0.09\" >";
}
?>
</div>
</div>
<div id= "subm"><input type="submit"></div>
</form> 
</article>
</html>