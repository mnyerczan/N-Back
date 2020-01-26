<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="<?=APPLICATION?>Style/common-new.css" />
    <link rel="stylesheet" type="text/css" href="<?=APPLICATION?>Style/white.css" />
	<link rel="stylesheet" type="text/css" href="<?=APPLICATION?>Style/black.css" />
    <title>Document</title>
</head>
<body>
    <!-- Header -->
    <?php require_once APPLICATION."Templates/headerView.php";  ?>
	<!-- Info label -->
    <?php //require_once APPLICATION.'Templates/infoLabelView.php';?>
	<!-- Nav bar -->
	<?php require_once APPLICATION.'Templates/navbarView.php'; ?>
	<!--Center Box-->
    <main id="main-main"  tabindex="-1" <?= @$_GET['nb_common'] == 1 ? 'style="pointer-events: none;"' : ""?>>
    <?php //require_once APPLICATION."Templates/{$module}/{$view}View.php";?>
    </main>
</body>
</html>