<!DOCTYPE html>
<html lang="en" tabindex="-1">
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<meta name="viewport" content="user-scalable=no">
<title>Position N-Back by Brain Workshop</title>
<link rel="stylesheet" type="text/css" href="sciprts/bootsrtap/3_0_4.css" />
<script type="text/javascript" src="scripts/m_emory.js"></script>
<script type="text/javascript" scr="scripst/bootstrap/3_0_4.js"></script>
<script type="text/javascript" src="scripts/jQuery/jquery-3.3.1.min.js"></script>
<script>var jsLogLevel = true;</script>
<link rel="stylesheet" type="text/css" href="style/style_black.css" />
<link rel="stylesheet" type="text/css" href="style/style_white.css" />
<link rel="stylesheet" type="text/css" href="style/common.css" />
<link rel="stylesheet" id="theme_link" type="text/css" href="style/style_<?= $main_theme ?>.css" />

</head>
<body  class="main_body" id="main" <?= $bodyParams ?> tabindex="-1">

<!-- Header -->
<?php require_once('header.php'); ?>

<!-- Info label -->
<?php require_once('info_label.php');?>

<!-- Main Page -->
<div class="main_page" id="main_page">

<!-- Nav bar -->
<?php require_once('nav_bar.php'); ?>

<!--Center Box-->
<main id="main_page_center_box" unselectable="on" tabindex="-1" <?= @$_GET['nb_common'] == 1 ? 'style="pointer-events: none;"' : ""?>>

<?php

switch($page)
{
	case 1: if(!@$_SESSION['u_name'] ) 
				require_once("login/login_form.php");
			else 
				header("Location: index.php"); /*Ha esetleg hibás lenne a login form action-je*/ 	break;
	case 2:  require_once("execute.php"); 															break;
	case 3:  require_once("n_back.php"); 															break;
	case 5:  require_once("modify_user.php"); 														break;
	case 6:  require_once("user.php"); 																break;
	case 7:  require_once("forum/index.php"); 														break;
	case 8:  require_once("profiles.php"); 															break;
	case 9:  require_once("report.php"); 															break;
	case 10: require_once("forum/edit_forum.php"); 													break;
	case 11: require_once("edit_start_page.php"); 													break;
	default: require_once("start_page.php");
}
?>

</main>
</div>


<?php

unset($_POST);
unset($_GET);
mysqli_close($conn);
$conn_pdo = null;
ob_end_flush();
if($error_level > 0)  file_put_contents($logfile,
"[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'][MAIN][EXECUTE] CASE:'.$case. "\n".
"[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR'].'][MAIN][EXECUTE] EXIT: '.$exit."\n".
"[".$now->format("Y-m-d H:i:s.u")."][".$_SERVER['REMOTE_ADDR']."][MAIN] *** END *** \n", $log_param_1);


?>

</body>
</html>

