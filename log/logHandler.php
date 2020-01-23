<?php
# Delete logfile after one day
if($myFile = fopen("log/thesis.log", 'r')){

	$timeMark = strtotime(fread($myFile, 19));

	if(time() > $timeMark + 24 * 3600){
		unlink("log/thesis.log");
	}

	fclose($myFile);
}

# if not exists log file, create it.
if(!file_exists("log/thesis.log"))
{
	# Operation not permitted
	chmod("log", 0775);
	chown("log", "www-data") or die();

	if(!fopen("log/thesis.log", "w")){

		echo "<h1>Can't created logfile<h1>";
		die();
	}
}

?>
