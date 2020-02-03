<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="<?= RELPATH ?><?=APPLICATION?>Images/favicon.png">
    <link rel="stylesheet" type="text/css" href="<?= RELPATH ?><?=APPLICATION?>Style/common-new.css" />
    <link rel="stylesheet" type="text/css" href="<?= RELPATH ?><?=APPLICATION?>Style/white.css" />
    <link rel="stylesheet" type="text/css" href="<?= RELPATH ?><?=APPLICATION?>Style/black.css" />
    <title>Nback - <?=$view?></title>
</head>
<body>
    <!-- Header -->
    <?php if ( $seria )  require_once APPLICATION."Templates/headerView.php";  ?>
	<!-- Info label -->
    <?php if ( $indicator )  require_once APPLICATION.'Templates/infoLabelView.php';?>
	<!-- Nav bar -->
	<?php if ( $navbar ) require_once APPLICATION.'Templates/navbarView.php'; ?>
	<!--Center Box-->    
    <?php require_once APPLICATION."Templates/{$module}/{$view}View.php";?>    
</body>
</html>