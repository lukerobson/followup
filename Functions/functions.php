<?php
date_default_timezone_set('UTC'); #  makes sure that the time is UTC
#########################################################################
#The airmass function uses Rozenberg equation for airmass (Rozenberg, 1966).
#Where Z is the zenith angle and is related by the altitude by 90° = 𝐴𝐿𝑇 + 𝑍
#𝑋 = (cos 𝑍 + 0.025𝑒^-11(cos 𝑍) )^-1 
#As Beck`s (Beck, 2018) equation uses the airmass from the FITS header, one
#can compare the airmasses from the FITS header to see if the airmass function
#is working correctly. 
function airmasscal ($ALTstar){
		$ALTZ = (90 - $ALTstar);
		$cosz = COS($ALTZ*0.0174533);
		$cos11z=0.025*exp(-11*$cosz);
		$cosall = $cosz + $cos11z;
		$airmassalt = (1/$cosall);
		return $airmassalt;
	}
##################################################################
#In order to calculate the altitude of the object at a particular time, the formulas from Duffett-Smith
#were used (Duffett-Smith, 1989). This takes the right ascension (RA),
#declination (DEC), the observer`s latitude (LAT) and longitude (LONG) and 
#the time of observation to calculate the altitude of that object in the sky at that time.
#It first calculates the difference in time between the observation and J2000
#(1200 hrs UT on Jan 1st 2000 AD) to calculate the Local Siderial Time (LST)
#of the observation. Where d is the days from J2000 to the observed date, with
#the fraction of a day included. The UT is the hours in Universal Time with
#decimals calculated by adding the minutes divided by 60 to get it to hours with decimals.
#𝐿𝑆𝑇 = 100.46 + (0.985647 ∗ 𝑑) + 𝐿𝑂𝑁𝐺 + 15 ∗ 𝑈𝑇
#Then it calculates the hour angle (HA) by taking the right ascension and taking
#it away from the local siderial time. PHP, sin and cos functions use radians
#rather than degrees so the code divides all the variables by π/180. Then it uses
#the equation below to get the altitude.
#sin(𝐴𝐿𝑇) = sin(𝐷𝐸𝐶) ∗ sin(𝐿𝐴𝑇) + cos(𝐷𝐸𝐶) ∗ cos(𝐿𝐴𝑇) ∗ cos(𝐻𝐴)
#After converting the arcsin (ALT) to degrees by dividing it by π/180,

#Duffett-Smith, P. (1989, May 27). Practical Astronomy with your Calculator.
#Cambridge: Cambridge University Press. Retrieved from Stargazing
#Network: http://www.stargazing.net/kepler/altaz.html 


function altitude($pick,$time,$long,$lat) {
		$RA = $pick[2];
		$DEC = $pick[3];
        
		$LAT = $lat;
		$j2000 = 946728000;
		$dif = $time - $j2000 ;
		$dif2 = $dif /(86400);
		$hour = gmdate("H", $time);
		$min = gmdate("i", $time);
		$hourtime = ($hour + ($min/60))*15;
		$difft = 0.985647 * $dif2;
		$LST = 100.46 + ($difft) + $long + $hourtime;
		$HA = $LST - $RA;
		$pi180 = 0.01745329252;
		$sALT = sin(($DEC*$pi180))*sin(($LAT*$pi180))+cos(($DEC*$pi180))*cos(($LAT*$pi180))*cos(($HA*$pi180));
		$ALT = asin($sALT);
		$ALT = $ALT/$pi180;
		return $ALT;
    }

#################################################################
#This function tries to estimate the peak ADU value on the CCD from the
# magnitude of a star, airmass and exposure time. 
#
function peakccd ($objmag,$airmass,$exotime,$arry,$equation){
		
	$bgcountexp = $arry[12];
	$k = $arry[18];      # extention of the filter being used 
	$seeing = $arry[17]; # astronomal seeing at sight
	$pxsize = $arry[16]; # pix size befor binning
	$binning = $arry[5]; # binning 
	$flength = $arry[14]; #  focal lenth in
	$scale = $pxsize*$binning / ($flength/ 206264.8062 ) / 1000; # works out the scale of per pixel in arcseconds
	$pedestal=70;
		
	# 5 step process to work out the total counts expected on the target	
	$first = ($equation[0] *$objmag); 
	$third = -($equation[2]*(1-$airmass)); # removes some due to airmass extiontion 
	$tot = $first + $equation[1] + $third; # adds the componats 
	$power = $tot*(-2/5);    # all per second
	$objcount = $exotime *((pow(10,$power))); # times by exoposure time.     

	$seeingpx = $seeing/ $scale;
	
	$bgcount =($bgcountexp*$exotime)+$pedestal;	# works out  the background count with a constant
	$peakval = ($objcount / (2*3.14159265*(0.51*$seeingpx)*(0.51*$seeingpx)))+$bgcount;#
	$peakval = intval($peakval); # removes decimal as cannot get a decimal value  
	return $peakval;
		}
################################################
# function that takes in julian Data in form of 2457697.485 and caculates the unixtime stamp by working out the number of days since that data to J2000 ()
function dateare ($jdate){
	$j2000 = 946684800;# unix time on j2000 
	$jd2000= 2451544.5;# jd date of j2000
	$jdats = ($jdate-$jd2000)*60*60*24; # caculates the number of seconds between j2000 and the input date
	$times = $j2000 + $jdats;# adds the number of seconds from j2000 to the caculate the full unix time
	return $times;
}



?>