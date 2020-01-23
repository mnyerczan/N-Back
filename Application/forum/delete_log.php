<?php
require_once('.././connect.php');
require_once('.././functions.php');

$sql_delete_log = 'DELETE FROM logs WHERE id = '.$_POST['logid'];

if(Sql_execute_query($sql_delete_log) == 0){

	echo 'Log deleted';
}
else{
	echo 'Lod delete faild';
}
?>
