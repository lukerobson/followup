<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link rel = "stylesheet" href = "../main/stylemain.css" type = "text/css"/>
<article>
<?php
$arry = $_SESSION["datarun"]; # gets and checks if previous data has been entered in to the website (Phases)
$long = $arry[1];
$lat = $arry[0];
$horzion = $arry[2];
$typeofdata = strlen($long)* strlen($lat)*strlen($horzion); # if no vaules then the string lenth is zero 

# below is the input values set to values

$startstring = "Start in how many days <br> <input type=\"number\" name=\"start\" min=\"-366\" max=\"366\" step=\"1\" value=\"0\" ><br>";	
$durationstring ="Duration of the observations (in days)<br> <input type=\"number\" name=\"lenth\" min=\"1\" max=\"366\" value=\"30\"step=\"1\" >";
$latstring = "Latitude <br> <input type=\"number\" name=\"lat\" min=\"-90\" max=\"90\" step=\"0.00001\" value=\"51.4826\">";
$longstring = "Longitude <br> <input type=\"number\" name=\"long\" min=\"-180\" max=\"180\" step=\"0.00001\" value=\"-0.09\" >";
$beforestring = "Time before transit (mins): <br> <input type=\"number\" name=\"transitbefore\" min=\"1\" max=\"300\" step=\"1\" value=\"30\">";
$afterstring = "Time after transit (mins): <br>
<input type=\"number\" name=\"transitafter\" min=\"1\" max=\"300\" step=\"1\" value=\"30\" >";
$datatype = "Source: <br>
<select name=\"csvname\">
  <option value=\"http://observatory.herts.ac.uk/exotransitpredict/data/exovar2.php\">Exoplanet Transit Database</option>
  <option value=\"http://observatory.herts.ac.uk/exotransitpredict/data/exoorg.php\">Exoplanet Orbit Database</option>
  <option value=\"http://observatory.herts.ac.uk/exotransitpredict/data/confirmednasaout.php\">NASA </option>
  <option value=\"http://observatory.herts.ac.uk/exotransitpredict/tess/publictessmake.php\">TESS TOI</option> 
</select>";
$mmag = "Transit depth type: <br><select name=\"depthtype\">
  <option value=0>mmag</option>
  <option value=1>Magnitude</option>
</select>";
$mindepth = "Minimum transit depth (mmag): <br>
<input type=\"number\" name=\"mindepth\" min=\"0.1\" max=\"300\" step=\"0.1\" value=\"0.5\" >";
$typeoflist = "Data source: <br><select name=\"typeoflist\">
  <option value=0>Exoplanet Transit Database</option>
  <option value=1>NASA Exoplanet Archive</option>
  <option value=2>Exoplanet Orbit Database</option>
  <option value=3>TESS TOI</option>
</select>";#Var2 0, NASA 1, Exo.org 2, TESS TOI 3
if ($typeofdata==0){ # no data entered into the website
	echo"<div class = \"row3rds\">";
	echo "<div>" . $startstring . "</div>";
	echo "<div>" . $durationstring . "</div>";
	echo "<div>" . $mmag . "</div>";
	echo "<div>" . $beforestring . "</div>";
	echo "<div>" . $afterstring . "</div>";
	echo "<div>" . $mindepth . "</div>";
	echo "<div>" . $latstring  . "</div>";
	echo "<div>" . $typeoflist . "</div>";
	echo "<div>" . $longstring . "</div>";
	echo "</div>";	
}
else{ # typeofdata is not zero so data has been entered so no need for lat long to be echoed out
	echo"<div class = \"row3rds\">";
	echo "<div>" . $startstring . "</div>";
	echo "<div>" . $durationstring . "</div>";
	echo "<div>" . $mmag . "</div>";
	echo "<div>" . $beforestring . "</div>";
	echo "<div>" . $afterstring . "</div>";
	echo "<div>" . $mindepth . "</div>";
	echo "<div>" . $typeoflist . "</div>";
	echo "</div>";		
}

echo "<div id= \"subm\"><input type=\"submit\"></div>";
echo "<div class = \"row3rds\">";
echo "<div><input type=\"checkbox\" name=\"timelinein\" value=\"1\">Timeline</div>";
echo "<div><input type=\"checkbox\" name=\"altazin\" value=\"1\">Alt & Az</div>";
echo "<div><input type=\"checkbox\" name=\"moonin\" value=\"1\">Moon</div>";

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
   <OPTION value=\"26.6955;100.0311111;Asia/Shanghai;Vainu Bappu Observatory, India\">Gao Mei Gu Observatory (GMGO)</OPTION>
</optgroup>
<optgroup label=\"Australia / New Zealand\">
   <OPTION value=\"-31.277039;-210.933914;Australia/Sydney;Anglo-Australian Observatory / Siding Spring\">Anglo-Australian Observatory / Siding Spring</OPTION>
   <OPTION value=\"-43.986667;170.465;Pacific/Auckland;Mount John University Observatory, New Zealand\">Mount John University Observatory, New Zealand</OPTION>
   <OPTION value=\"-27.797861;151.855417;Australia/Brisbane;Mt. Kent Observatory\">Mt. Kent Observatory</OPTION>
   <OPTION value=\"-35.32065;-210.975667;Australia/Sydney;Mt. Stromlo Observatory\">Mt. Stromlo Observatory</OPTION>
   <OPTION value=\"-28.19063889;153.2666667;Australia/Brisbane;Spring Brook Observatory\">Spring Brook Observatory (SBO)</OPTION>
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
   <OPTION value=\"37.07027778;-119.4130556;America/Los_Angeles;Wyoming Infrared Observatory (WIRO)\">Sierra Remote Observatories (SRO)</OPTION>
   
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
</optgroup>"; # has the manual selected for manual lat long to be entered first
	echo "</select>";
	echo "</div><div><input type=\"checkbox\" name=\"timezonerun\" value=\"1\" checked >Local time </div>"; # local time or not

}
echo "<div>Altitude of Sun (Day/night)<SELECT  name=\"sunsetnumber\" >   <OPTION value=\"91\"  > &minus;1 degrees (sunset) </OPTION>
   <OPTION value=\"96\"  > &minus;6 degrees (civil twilight) </OPTION>
   <OPTION value=\"102\"    > &minus;12 degrees (nautical twilight)</OPTION>
   <OPTION value=\"108\"  selected = \"selected\">&minus;18 degrees (astronomical twilight) </OPTION></select></div>"; # Altitude of sun for sunset and sunrise
echo "</div>"; # cloes last div
?>

