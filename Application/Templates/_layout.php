<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="<?= BACKSTEP ?><?=APPLICATION?>Images/favicon.png">
    <link rel="stylesheet" type="text/css" href="<?= BACKSTEP ?><?=APPLICATION?>Style/common-new.css?v=<?= CURRENT_TIMESTAMP ?>" />
    <link rel="stylesheet" type="text/css" href="<?= BACKSTEP ?><?=APPLICATION?>Style/white.css?v=<?= CURRENT_TIMESTAMP ?>" />
    <link rel="stylesheet" type="text/css" href="<?= BACKSTEP ?><?=APPLICATION?>Style/black.css?v=<?= CURRENT_TIMESTAMP ?>" />  
    <script src="<?= BACKSTEP ?><?=APPLICATION?>Scripts/helperFunctions.js"></script>
    <title>Nback - <?=@$title?></title>
</head>
<body>
    <!-- Header -->
    <?php if ( @$seria )    require_once APPLICATION."Templates/Header/headerView.php";  ?>
	<!-- Info label -->
    <?php if ( @$indicator )//require_once APPLICATION.'Templates/infoLabelView.php';?>
	<!-- Nav bar -->
	<?php if ( @$navbar )   require_once APPLICATION.'Templates/navbarView.php'; ?>
	<!--Center-->    
    <?php require_once APPLICATION."Templates/{$module}/{$view}View.php";?>    
</body>
</html>