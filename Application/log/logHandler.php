<?php
# Delete logfile after one day
if($myFile = fopen(APPROOT."log/thesis.log", 'a+'))
{
	$timeMark = strtotime(fread($myFile, 19));

	if($timeMark != '' && time() > $timeMark + 24 * 3600){
		unlink(APPROOT."log/thesis.log");
	}

	fclose($myFile);
}


?>
