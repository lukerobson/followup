Transit Follow Up Tool
Website code in PHP to predict transit events with the ability to include off transit time before and after. Also the ability to predict the uncertainty of the photometry on CCD cameras.  Live website at: http://observatory.herts.ac.uk/exotransitpredict

There are two main sections: All tool or Single tool. With another page of functions.

There are two main parts to the website: the form that accepts the input parameters (observing location, any constraints), and the code that takes this input, calculates the visibility of events, and generates the output. The input has just the name of the file and the output has "out" at the end of the same filename. e.g. all.php is input and allout.php is the output.

The website has HTML and CSS code to style and format the website, all the code that the tools use is PHP version 5.6.
