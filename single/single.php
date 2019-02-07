<?php
// Start the session
session_start();
?><!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<style>
.row{

}
.row> div{
	
}

</style>

<article>
<div id="centertext">This page predicts the viewable transits for a selected exoplanet.</div>
<div class = "row"> <!––  creates 2 columns in the website with the frist div being the first column then 2nd column   ––>
<div>
	<form method="post" action="singleout.php">  <!––  sends the data that is inputted to the page stated ––>
<?php
# this page has 3 diffrent fronts depending on the infomation has been entered before
# not entered anything before 
# if lat long and horizon entered (type1 not zero)
# if complete data all 22 data points (type2 not zero)
$arry = $_SESSION["datarun"]; # sets the datarun session data and  sets lat long and horiozn to varibles
$long = $arry[1];
$LAT = $arry[0];
$horzion = $arry[2];

$csvname = "all.csv"; # the csv name file 
$csvfile = file_get_contents($csvname); # gets the data in the csv and sets it to a varible
$file = (explode("\n",$csvfile)); # splits the csv file per line

$type1 = strlen($long)* strlen($LAT)*strlen($horzion);
$type2 = strlen ($arry[8]) * strlen ($arry[9]) * strlen ($arry[10]) * strlen ($arry[11]) * strlen ($arry[12]) *strlen ($arry[13]) * strlen ($arry[14]) * strlen ($arry[15]) * strlen ($arry[16]) *  strlen ($arry[17]) *  strlen ($arry[18]) *  strlen ($arry[19]) *  strlen ($arry[20]) *  strlen ($arry[21]) ;
if ($type1 == 0){#no info entered into input data
	
	echo "Select the name of the target star<br>";
	echo"<select name=\"star\">";
	foreach ($file as $line) { # selects the star name / transit name and wites it out as a selecter
		$each = (explode(",",$line));
		$starname= $each[9];
		echo "<option value=\"" . $starname .  "\">" . $starname . "</option>";
	}
	echo "</select><br>";
	# the defult value of lat and long are of bayfordbury
	
	echo "Latitude:<br> "; 
	echo "<input type=\"number\" name=\"lat\" min=\"-90\" max=\"90\" step=\"0.0001\" value=\"51.4826\">";
	
	echo "<br>Longitude:<br>";
	echo "<input type=\"number\" name=\"long\" min=\"-180\" max=\"180\" step=\"0.0001\" value=\"-0.09\">";
	
	echo"</div><div>"; # end of first column and start of 2nd  
	
	
	echo "How many months of observations (1-12):<br>";
	echo "<input type=\"number\" name=\"mouth\" min=\"1\" max=\"12\" value=\"12\" step=\"1\"><br>";

}else{ # entered data before 

	if ($type2 == 0){ #entered lat,long and horiozon only. 
	
		echo "Select the name of the target star<br>";
		echo"<select name=\"star\">";
	
		foreach ($file as $line) {  # selects the star name / transit name and wites it out as a selecter
			$each = (explode(",",$line));
			$starname= $each[9];
			echo "<option value=\"" . $starname .  "\">" . $starname . "</option>";
		}
		echo "</select>";
		echo"</div><div>"; #   end of first column and start of 2nd 
	
		echo "How many months of observations (1-12):<br>";
		echo "<input type=\"number\" name=\"mouth\" min=\"1\" max=\"12\" value=\"12\" step=\"1\"><br>";
	
	}else{# only if all  22 data has been entered before
	
		echo "Select the name of the target star<br>";
		echo"<select name=\"star\">";
	
		foreach ($file as $line) { # selects the star name / transit name and wites it out as a selecter
			$dataline = array();
			$each = (explode(",",$line));
			$starname= $each[9];
			echo "<option value=\"" . $starname .  "\">" . $starname . "</option>";	
		}
	
		echo "</select><br>";
	
		echo "Enter target star catalogue magnitude<br>";# <!-- gets the target magnitude from user//-->
		echo "<input type=\"number\" name=\"mtar\" min=\"4\" max=\"20\" step=\"0.001\">"; 
		echo "<br>Enter reference star catalogue magnitude<br>"; # <!-- gets the reference magnitude from user//-->
		echo "<input type=\"number\" name=\"mref\" min=\"4\" max=\"20\" step=\"0.001\">"; 

	
	
		echo"</div><div>"; #   end of first column and start of 2nd 
		echo "Select exposure time in seconds <br>";
		echo "<select name=\"exotime\">";#  option menu with exoser times of bayfordbury could be changed to and input to have any exopure time 
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
	
		echo "<br>How many months of observations (1-12):<br>"; # how long the progrm should look for transits
		echo "<input type=\"number\" name=\"mouth\" min=\"1\" max=\"12\" value=\"12\" step=\"1\"><br>";
		
	} 
	
}
?><!––  takes in the values of time before or after the transit ––>
Time before transit (mins): <br>
<input type="number" name="transitbefore" min="1" max="300" step="1" value="30" ><br>
Time after transit (mins): <br>
<input type="number" name="transitafter" min="1" max="300" step="1" value="30" ><br>


</div> <!–– closes the 2nd column then the grid ––>
</div>

<div id= "subm"><input type="submit"></div>
</form> <!–– sumbit button with style through "subm" ––>
</article>
</html>