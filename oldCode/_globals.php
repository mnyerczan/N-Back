<?php



$admin_privilege = 3;
	$error_level = 3;
		$logfile = "Log/thesis.log";
		   $time = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));			

#paraméterei
# LOCK_EX
# FILE_APPEND
# FILE_USE_INCLUDE_PATH

	$log_param_1 = FILE_APPEND;


?>