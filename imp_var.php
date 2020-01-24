<?php

if(!empty($_GET)) $_get_datas	= $_GET;

if(!empty($_POST)) $_post_datas = $_POST;

				$_cookie_datas  = isset($_COOKIE) ?	 $_COOKIE : 'empty';

						   $case = isset($_POST['case']) ? $_POST['case'] : (!isset($_GET['case']) ? '' : $_GET['case']);

						   $file = isset($_FILES['file']['error']) && $_FILES['file']['error'] != 0 ? 'none' : $_FILES;

					   $user_id = isset( $_POST['id']) &&  $_POST['id'] != '' ? $_POST['id'] : @$_SESSION['user_datas']['id'] ? $_SESSION['user_datas']['id'] : "0" ;

					  $img_name = (isset($_GET['file_name'])) ?  $_GET['file_name'] : isset($_SESSION['user_datas']['file_name']) ? $_SESSION['user_datas']['file_name'] : "";

						   $exit = -1;

								$e = '';
?>				