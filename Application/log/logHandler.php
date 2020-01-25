<?php
# Delete logfile after one day
if($myFile = fopen(APPLICATION."log/thesis.log", 'a+'))
{
	$timeMark = strtotime(fread($myFile, 19));

	if($timeMark != '' && time() > $timeMark + 24 * 3600){
		unlink(APPLICATION."log/thesis.log");
	}

	fclose($myFile);
}


?>
