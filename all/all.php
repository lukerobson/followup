<?php
# Start the session
session_start();
?><!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>


<style>
.row{
	/*  creates a grid  2 by 2 with text in the center  */ 
	display:grid;
	grid-template-columns: 50% 50%;
	text-align: center;
}
.row> div{
	/*  have a gap between the 2 column of 2em  */ 
	grid-gap: 2em;
	

}

</style>

<article>
<div id="centertext">This page predicts the viewable transits from a specified location.</div>
<div class = "row"> <!––  creates 2 columns in the website with the frist div being the first column then 2nd column   ––> 
	<div>
	<form method="post" action="allout.php"> <!––  sends the data that is inputted to the page stated ––>
	Start in how many days <br>
	<input type="number" name="start" min="-366" max="366" step="1" value="0" ><br> <!––  takes in the number of day the user wants the program to start from this means you can skip foward or backwards  ––>
	Data set: <br>
	<!––  selects options to set which data sets the program picks    ––>
	<select name="csvname">
		<option value="confshort.csv">Confirmed</option>
		<option value="k2can.csv">K2 candidates</option>
		<option value="all.csv">Both</option>
	</select>

<!––  takes in the values of time before or after the transit  ––>

	<br>Time before transit (mins): <br>
	<input type="number" name="transitbefore" min="1" max="300" step="1" value="30" ><br>
	Time after transit (mins): <br>
	<input type="number" name="transitafter" min="1" max="300" step="1" value="30" ><br>
	</div><!––  end of first column and start of 2nd  ––>
<div>
<!––  takes in the number of days the program should check for transits   ––>
Duration of the observations (in days)<br>
<input type="number" name="lenth" min="1" max="366" value="30"step="1" >
<?php
# here to check if data has been entered before and if so later on it does not need to ask for lat and long again
$arry = $_SESSION["datarun"];
$long = $arry[1];
$LAT = $arry[0];
$horzion = $arry[2];
$lat =$LAT;
# takes the string lenth of the long, lat and horizon vales and multiples them, if any value has not been entered then the whloe answer would be zero 	
$typeofdata = strlen($long)* strlen($LAT)*strlen($horzion);

if ($typeofdata==0){ # if no data entered before then the answer is zero so displays the lat and long so that they can be entered.
# i.e. if entered on input then does not need to be entered again 
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