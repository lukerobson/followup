<?php
$arry = $_SESSION["datarun"];
$lat = $arry[0];
$long = $arry[1];
$horzion = $arry[2];
$typeofdata = strlen($long)* strlen($lat)*strlen($horzion);
$type4 = strlen ($arry[8]) * strlen ($arry[9]) * strlen ($arry[10]) * strlen ($arry[11]) * strlen ($arry[12]) *strlen ($arry[13]) * strlen ($arry[17]) * strlen ($arry[18]) * strlen ($arry[19]) ;
if($typeofdata == 0){
	$lat = 51.4826;
	$long = -0.09;
}
$startstring = "Start in how many days <br> <input type=\"number\" name=\"start\" min=\"-366\" max=\"366\" step=\"1\" value=\"0\" ><br>";	
	
$durationstring ="Duration of the observations (in days)<br> <input type=\"number\" name=\"lenth\" min=\"1\" max=\"366\" value=\"30\"step=\"1\" >";


$latstring = "Latitude <br> <input type=\"number\" name=\"lat\" min=\"-90\" max=\"90\" step=\"0.00001\" value=\"$lat\">";

$longstring = "Longitude <br> <input type=\"number\" name=\"long\" min=\"-180\" max=\"180\" step=\"0.00001\" value=\"$long\" >";
	
$beforestring = "Time before transit (mins): <br> <input type=\"number\" name=\"transitbefore\" min=\"1\" max=\"300\" step=\"1\" value=\"30\">";
$afterstring = "Time after transit (mins): <br>
<input type=\"number\" name=\"transitafter\" min=\"1\" max=\"300\" step=\"1\" value=\"30\" >";



echo "<div class=\"row3rds\">
<div> $startstring </div>
<div> $durationstring</div>
<div> $beforestring </div>
<div> $afterstring </div>";

if ($typeofdata==0){
 
echo "<div>" . $latstring . "</div>";
echo "<div>" . $longstring . "</div>";
}
elseif ($type4 != 0){
$targetmag = "Target star catalogue magnitude<br><input type=\"number\" name=\"mtar\" min=\"4\" max=\"20\" step=\"0.001\">"; 
$refmag = "Enter reference star catalogue magnitude<br><input type=\"number\" name=\"mref\" min=\"4\" max=\"20\" step=\"0.001\">"; 

echo "<div>" . $targetmag . "</div>";
echo "<div>" . $refmag . "</div>";	
}
?>

</div><br>
<div class="row4th">
<div style="cursor:pointer" onclick="openNav()" class = "optionclik" id="menuset1">Exoplanet Transit Database</div>
<div style="cursor:pointer" onclick="openNav2()" class = "optionclik" id="menuset2">NASA Exoplanet Archive</div>
<div style="cursor:pointer" onclick="openNav3()" class = "optionclik" id="menuset3">Exoplanet Orbit Database</div>
<div style="cursor:pointer" onclick="openNav4()" class = "optionclik" id="menuset4">TESS TOI</div>
</div>
<div class="row3rds" >
<div></div>
<div>
<select name="typeoflist0" class="selectopent" id="myNav1">
<optgroup label="Exoplanet Transit Database">
</select>
<select name="typeoflist1" class="selectopent" id="myNav2">
<optgroup label="NASA Exoplanet Archive">
</select>
<select name="typeoflist2" class="selectopent" id="myNav3">
<optgroup label="Exoplanet Orbit Database">
</select>
<select name="typeoflist3" class="selectopent" id="myNav4">
<optgroup label="TESS TOI">
</select>
</div></div>
<div id= "subm"><input type="submit"></div>
<?php
echo "<div class = \"row3rds\">";

echo "<div>Alt & Az<br><input type=\"checkbox\" name=\"altazin\" value=\"1\"></div>";
echo "<div>Moon<br><input type=\"checkbox\" name=\"moonin\" value=\"1\"></div>";

if ($typeofdata==0){
echo "<div>";
echo "Observatory or manual<br>";
echo "<SELECT  name=\"observatorystring\" >
<optgroup label=\"Africa\">
   <OPTION value=\"-29.038889;-332.594444;Africa/Johannesburg;Boyden Observatory, Bloemfontein, South Africa\">Boyden Observatory, Bloemfontein, South Africa</OPTION>
   <OPTION value=\"-32.379444;-339.189306;Africa/Johannesburg;South African Astronomical Observatory\">South African Astronomical Observatory</OPTION>
</optgroup>
<optgroup label=\"Asia\">
   <OPTION value=\"29.36;-280.54361;Asia/Kolkata;Aryabhatta Research Institute, India\">Aryabhatta Research Institute, India</OPTION>
   <OPTION value=\"40.393333;-242.425;Asia/Shanghai;Beijing XingLong Observatory, China\">Beijing XingLong Observatory, China</OPTION>
   <OPTION value=\"32.7794;-281.03583;Asia/Kolkata;Indian Astronomical Observatory, Hanle\">Indian Astronomical Observatory, Hanle</OPTION>
   <OPTION value=\"12.57666;-281.1734;Asia/Kolkata;Vainu Bappu Observatory, India\">Vainu Bappu Observatory, India</OPTION>
</optgroup>
<optgroup label=\"Australia / New Zealand\">
   <OPTION value=\"-31.277039;-210.933914;Australia/Sydney;Anglo-Australian Observatory / Siding Spring\">Anglo-Australian Observatory / Siding Spring</OPTION>
   <OPTION value=\"-43.986667;170.465;Pacific/Auckland;Mount John University Observatory, New Zealand\">Mount John University Observatory, New Zealand</OPTION>
   <OPTION value=\"-27.797861;151.855417;Australia/Brisbane;Mt. Kent Observatory\">Mt. Kent Observatory</OPTION>
   <OPTION value=\"-35.32065;-210.975667;Australia/Sydney;Mt. Stromlo Observatory\">Mt. Stromlo Observatory</OPTION>
</optgroup>
<optgroup label=\"Europe\">
   <OPTION value=\"37.223611;-2.54625;Europe/Madrid;Calar Alto Observatory, Spain\">Calar Alto Observatory, Spain</OPTION>
   <OPTION value=\"38.398333;27.275;Europe/Istanbul;Ege University Observatory, Izmir, Turkey\">Ege University Observatory, Izmir, Turkey</OPTION>
   <OPTION value=\"45.848589;-348.418867;Europe/Rome;Mt. Ekar Observatory, Asiago, Italy\">Mt. Ekar Observatory, Asiago, Italy</OPTION>
   <OPTION value=\"41.693056;-335.256111;Europe/Sofia;National Astronomical Observatory Rozhen - Bulgaria\">National Astronomical Observatory Rozhen - Bulgaria</OPTION>
   <OPTION value=\"37.064167;-3.384722;Europe/Madrid;Observatorio de Sierra Nevada, Spain\">Observatorio de Sierra Nevada, Spain</OPTION>
   <OPTION value=\"50.16276;-6.85;Europe/Berlin;Observatorium Hoher List (Universitaet Bonn) - Germany\">Observatorium Hoher List (Universitaet Bonn) - Germany</OPTION>
   <OPTION value=\"28.758333;-17.88;Atlantic/Canary;Roque de los Muchachos, La Palma\">Roque de los Muchachos, La Palma</OPTION>
   <OPTION value=\"37.691667;-345.026667;Europe/Rome;SLN - Catania Astrophysical Observatory, Italy\">SLN - Catania Astrophysical Observatory, Italy</OPTION>
   <OPTION value=\"36.825;30.333333;Europe/Istanbul;Tubitak National Observatory, Turkey\">Tubitak National Observatory, Turkey</OPTION>
</optgroup>
<optgroup label=\"North America - East\">
   <OPTION value=\"40.921667;-78.005;EST5EDT;Black Moshannon Observatory, State College PA\">Black Moshannon Observatory, State College PA</OPTION>
   <OPTION value=\"41.378333;-83.659167;EST5EDT;Bowling Green State Univ. Observatory, Ohio\">Bowling Green State Univ. Observatory, Ohio</OPTION>
   <OPTION value=\"42.719546;-73.751433;EST5EDT;Breyo Observatory, Siena College, NY\">Breyo Observatory, Siena College, NY</OPTION>
   <OPTION value=\"44.56667;-69.656378;EST5EDT;Collins Observatory, Colby College, Maine\">Collins Observatory, Colby College, Maine</OPTION>
   <OPTION value=\"37.878333;-78.693333;EST5EDT;Fan Mountain Observatory, VA\">Fan Mountain Observatory, VA</OPTION>
   <OPTION value=\"42.81651;-75.532568;EST5EDT;Foggy Bottom Observatory, Colgate Univ., NY\">Foggy Bottom Observatory, Colgate Univ., NY</OPTION>
   <OPTION value=\"42.295;-71.485;EST5EDT;George R. Wallace, Jr. Astrophysical Observatory, MA\">George R. Wallace, Jr. Astrophysical Observatory, MA</OPTION>
   <OPTION value=\"42.3766;-71.1169;EST5EDT;Harvard Clay Telescope, Cambridge, MA\">Harvard Clay Telescope, Cambridge, MA</OPTION>
   <OPTION value=\"38.033333;-78.523333;EST5EDT;Leander McCormick Observatory, Univ. of Virginia\">Leander McCormick Observatory, Univ. of Virginia</OPTION>
   <OPTION value=\"40.20398;-77.19786;EST5EDT;Michael L. Britton Observatory, Dickinson College, PA\">Michael L. Britton Observatory, Dickinson College, PA</OPTION>
   <OPTION value=\"44.0134;-73.1813;EST5EDT;Mittelman Observatory, Middlebury College, VT\">Mittelman Observatory, Middlebury College, VT</OPTION>
   <OPTION value=\"38.344792;-85.528475;EST5EDT;Moore Observatory, Univ. of Louisville, Kentucky\">Moore Observatory, Univ. of Louisville, Kentucky</OPTION>
   <OPTION value=\"42.505261;-71.558144;EST5EDT;Oak Ridge Observatory, Harvard, MA\">Oak Ridge Observatory, Harvard, MA</OPTION>
   <OPTION value=\"41.378889;-72.105278;EST5EDT;Olin Observatory, Connecticut College, CT\">Olin Observatory, Connecticut College, CT</OPTION>
   <OPTION value=\"39.9071;-75.35555;EST5EDT;Peter van de Kamp Observatory, Swarthmore College, PA\">Peter van de Kamp Observatory, Swarthmore College, PA</OPTION>
   <OPTION value=\"42.317036;-72.639514;EST5EDT;Smith College Observatory, Northampton, MA\">Smith College Observatory, Northampton, MA</OPTION>
   <OPTION value=\"40.66632;-74.32327;EST5EDT;Sperry Observatory, Union County College, NJ\">Sperry Observatory, Union County College, NJ</OPTION>
   <OPTION value=\"41.555;-72.659167;EST5EDT;Van Vleck Observatory, Wesleyan University, CT\">Van Vleck Observatory, Wesleyan University, CT</OPTION>
   <OPTION value=\"41.683011;-73.890604;EST5EDT;Vassar College Observatory, Poughkeepsie, NY\">Vassar College Observatory, Poughkeepsie, NY</OPTION>
   <OPTION value=\"42.295;-71.305833;EST5EDT;Whitin Observatory, Wellesley College, MA\">Whitin Observatory, Wellesley College, MA</OPTION>
   <OPTION value=\"42.7115;-73.2052;EST5EDT;Williams College Observatory, MA\">Williams College Observatory, MA</OPTION>
</optgroup>
<optgroup label=\"North America - Central, West, and Hawaii\">
   <OPTION value=\"32.78;-105.82;MST7MDT;Apache Point Observatory\">Apache Point Observatory</OPTION>
   <OPTION value=\"48.521667;-123.416667;America/Vancouver;Dominion Astrophysical Observatory\">Dominion Astrophysical Observatory</OPTION>
   <OPTION value=\"31.963333;-111.6;America/Phoenix;Kitt Peak National Observatory\">Kitt Peak National Observatory</OPTION>
   <OPTION value=\"37.343333;-121.636667;PST8PDT;Lick Observatory\">Lick Observatory</OPTION>
   <OPTION value=\"35.096667;-111.535;America/Phoenix;Lowell Observatory\">Lowell Observatory</OPTION>
   <OPTION value=\"31.688333;-110.885;America/Phoenix;MMT Observatory\">MMT Observatory</OPTION>
   <OPTION value=\"19.828333;-155.478333;Pacific/Honolulu;Mauna Kea (Keck, Gemini, CFHT, Subaru, IRTF, etc.)\">Mauna Kea (Keck, Gemini, CFHT, Subaru, IRTF, etc.)</OPTION>
   <OPTION value=\"30.671667;-104.021667;CST6CDT;McDonald Observatory\">McDonald Observatory</OPTION>
   <OPTION value=\"32.701667;-109.891667;America/Phoenix;Mount Graham Observatory\">Mount Graham Observatory</OPTION>
   <OPTION value=\"32.416667;-110.731667;America/Phoenix;Mount Lemmon\">Mount Lemmon</OPTION>
   <OPTION value=\"31.029167;-115.486944;America/Tijuana;Observatorio Astronomico Nacional, San Pedro Martir\">Observatorio Astronomico Nacional, San Pedro Martir</OPTION>
   <OPTION value=\"19.032778;-98.313889;America/Mexico_City;Observatorio Astronomico Nacional, Tonantzintla\">Observatorio Astronomico Nacional, Tonantzintla</OPTION>
   <OPTION value=\"33.356;-116.863;PST8PDT;Palomar Observatory\">Palomar Observatory</OPTION>
   <OPTION value=\"41.17642;-105.57403;MST7MDT;Red Buttes Observatory, Wyoming\">Red Buttes Observatory, Wyoming</OPTION>
   <OPTION value=\"31.680944;-110.8775;America/Phoenix;Whipple Observatory\">Whipple Observatory</OPTION>
   <OPTION value=\"41.09706;-105.97653;MST7MDT;Wyoming Infrared Observatory (WIRO)\">Wyoming Infrared Observatory (WIRO)</OPTION>
</optgroup>
<optgroup label=\"South America\">
   <OPTION value=\"-23.029;-67.755;America/Santiago;ALMA\">ALMA</OPTION>
   <OPTION value=\"-30.165278;-70.815;America/Santiago;Cerro Tololo Interamerican Observatory\">Cerro Tololo Interamerican Observatory</OPTION>
   <OPTION value=\"-31.799167;-69.295;America/Argentina/San_Juan;Complejo Astronomico El Leoncito, San Juan, Argentina\">Complejo Astronomico El Leoncito, San Juan, Argentina</OPTION>
   <OPTION value=\"-31.598333;-64.545833;America/Argentina/Cordoba;Estacion Astrofisica Bosque Alegre, Cordoba, Argentina\">Estacion Astrofisica Bosque Alegre, Cordoba, Argentina</OPTION>
   <OPTION value=\"-29.256667;-70.73;America/Santiago;European Southern Observatory: La Silla\">European Southern Observatory: La Silla</OPTION>
   <OPTION value=\"-24.625;-70.403333;America/Santiago;European Southern Observatory: Paranal\">European Southern Observatory: Paranal</OPTION>
   <OPTION value=\"-30.24075;-70.736693;America/Santiago;Gemini South Observatory\">Gemini South Observatory</OPTION>
   <OPTION value=\"-22.534444;-45.5825;America/Sao_Paulo;Laboratorio Nacional de Astrofisica, Brazil\">Laboratorio Nacional de Astrofisica, Brazil</OPTION>
   <OPTION value=\"-29.003333;-70.701667;America/Santiago;Las Campanas Observatory\">Las Campanas Observatory</OPTION>
   <OPTION value=\"8.79;-70.866667;America/Caracas;National Observatory of Venezuela\">National Observatory of Venezuela</OPTION>
   <OPTION value=\"-34.906751;-57.932299;America/Argentina/Buenos_Aires;Observatorio Astronomico de La Plata, Buenos Aires\">Observatorio Astronomico de La Plata, Buenos Aires</OPTION>
</optgroup>
<optgroup label=\"Manual coordinate entry:\">
   <OPTION value=\"999\" selected = \"selected\" >Enter latitude longitude</OPTION>
</optgroup>";
	
	
}elseif($type4 != 0){
	echo "<div>";
	echo "Exposure time in seconds<br>";
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
}

?>

<script>



var x = document.getElementById("myNav1");
var y = document.getElementById("myNav2");
var z = document.getElementById("myNav3");
var w = document.getElementById("myNav4");

<?php
$arrtype = array("x","y","z","w");
$x = 0 ;
for ($p=0; $p < (4); $p++ ){
	$datacase = getdatacsv($p,0);#Var2 0
	$count0 = count($datacase);
	
	for ($c=0; $c < ($count0); $c++ ){
		$x = $x + 1;
		echo "var option" . $x . " = document.createElement(\"option\");\n";
		$starname = $datacase[$c][7];
		echo "option" . $x . ".text = \"" . $starname . "\";\n" . $arrtype[$p] . ".add(option" . $x . ");";
	}
	
}
?>

function openNav() {
  document.getElementById("myNav1").style.opacity = "1";
  document.getElementById("myNav1").style.width = "auto";
  document.getElementById("myNav1").disabled = false;
  
  document.getElementById("menuset1").style.textDecoration="underline";
  document.getElementById("menuset2").style.textDecoration="none";
  document.getElementById("menuset3").style.textDecoration="none";
  document.getElementById("menuset4").style.textDecoration="none";
  
  document.getElementById("myNav2").disabled = true;
  document.getElementById("myNav2").style.opacity = "0";
  document.getElementById("myNav2").style.width = "0%";
  
  document.getElementById("myNav3").disabled = true;
  document.getElementById("myNav3").style.opacity = "0";
  document.getElementById("myNav3").style.width = "0%";
  
  document.getElementById("myNav4").disabled = true;
  document.getElementById("myNav4").style.opacity = "0";
  document.getElementById("myNav4").style.width = "0%";
}
function openNav2() {
  
  document.getElementById("myNav2").style.opacity = "1";
  document.getElementById("myNav2").style.width = "auto";
  document.getElementById("myNav2").disabled = false;
  
  document.getElementById("menuset2").style.textDecoration="underline";
  document.getElementById("menuset1").style.textDecoration="none";
  document.getElementById("menuset3").style.textDecoration="none";
  document.getElementById("menuset4").style.textDecoration="none";
  
  document.getElementById("myNav1").disabled = true;
  document.getElementById("myNav1").style.opacity = "0";
  document.getElementById("myNav1").style.width = "0%";
  
  document.getElementById("myNav3").disabled = true;
  document.getElementById("myNav3").style.opacity = "0";
  document.getElementById("myNav3").style.width = "0%";
  
  document.getElementById("myNav4").disabled = true;
  document.getElementById("myNav4").style.opacity = "0";
  document.getElementById("myNav4").style.width = "0%";
  
}
function openNav3() {
  
  document.getElementById("myNav3").style.opacity = "1";
  document.getElementById("myNav3").style.width = "auto";
  document.getElementById("myNav3").disabled = false;
  
  document.getElementById("menuset3").style.textDecoration="underline";
  document.getElementById("menuset2").style.textDecoration="none";
  document.getElementById("menuset1").style.textDecoration="none";
  document.getElementById("menuset4").style.textDecoration="none";
  
  document.getElementById("myNav1").disabled = true;
  document.getElementById("myNav1").style.opacity = "0";
  document.getElementById("myNav1").style.width = "0%";
  
  document.getElementById("myNav2").disabled = true;
  document.getElementById("myNav2").style.opacity = "0";
  document.getElementById("myNav2").style.width = "0%";
  
  document.getElementById("myNav4").disabled = true;
  document.getElementById("myNav4").style.opacity = "0";
  document.getElementById("myNav4").style.width = "0%";
  
}

function openNav4() {
  
  document.getElementById("myNav4").style.opacity = "1";
  document.getElementById("myNav4").style.width = "auto";
  document.getElementById("myNav4").disabled = false;
  
  document.getElementById("menuset4").style.textDecoration="underline";
  document.getElementById("menuset2").style.textDecoration="none";
  document.getElementById("menuset3").style.textDecoration="none";
  document.getElementById("menuset1").style.textDecoration="none";
  
  document.getElementById("myNav1").disabled = true;
  document.getElementById("myNav1").style.opacity = "0";
  document.getElementById("myNav1").style.width = "0%";
  
  document.getElementById("myNav2").disabled = true;
  document.getElementById("myNav2").style.opacity = "0";
  document.getElementById("myNav2").style.width = "0%";
  
  document.getElementById("myNav3").disabled = true;
  document.getElementById("myNav3").style.opacity = "0";
  document.getElementById("myNav3").style.width = "0%";
  
}
document.getElementById("loadart").style.display = "block";
document.getElementById("loadw").style.display = "none";
</script>
</form>