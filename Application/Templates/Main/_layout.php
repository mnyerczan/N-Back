<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
		<meta name="viewport" content="user-scalable=no">
		<title>Position N-Back by Brain Workshop</title>
		<link rel="stylesheet" type="text/css" href="sciprts/bootsrtap/3_0_4.css" />
		<script type="text/javascript" src="scripts/m_emory.js"></script>
		<script type="text/javascript" src="scripst/bootstrap/3_0_4.js"></script>
		<script type="text/javascript" src="scripts/jQuery/jquery-3.3.1.min.js"></script>
		<script>var jsLogLevel = true;</script>
		<link rel="stylesheet" type="text/css" href="<?=APPROOT?>Style/style_white.css" />
		<link rel="stylesheet" type="text/css" href="<?=APPROOT?>Style/style_black.css" />		
		<link rel="stylesheet" type="text/css" href="<?=APPROOT?>Style/common.css" />
		<link rel="stylesheet" id="theme_link" type="text/css" href="<?=APPROOT?>Style/style_<?= $user->theme ?>.css" />
	</head>
	<body  class="main_body" id="main" tabindex="-1">
		<!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
		<!-- Header -->
		<?php require_once APPROOT."Templates/Main/headerView.php";  ?>
		<!-- Info label -->
		<?php //require_once('info_label.php');?>
		<!-- Nav bar -->
		<?php require_once APPROOT.'Templates/Main/navbarView.php'; ?>
		<!-- Main Page -->
		<div class="main_page" id="main_page">
			<!--Center Box-->
			<main id="main_page_center_box" unselectable="on" tabindex="-1" <?= @$_GET['nb_common'] == 1 ? 'style="pointer-events: none;"' : ""?>>
	
				<?php require_once APPROOT."Templates/Main/{$view}View.php";
/* 
switch($page)
{
	case 1: if(!@$_SESSION['u_name'] ) 
				require_once("login/login_form.php");
			else 
				header("Location: index.php"); /*Ha esetleg hibás lenne a login form action-je	 	break;
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
} */
		 ?></main>
		</div>
<?php /* ob_end_flush();*/ ?>
	</body>
</html>

