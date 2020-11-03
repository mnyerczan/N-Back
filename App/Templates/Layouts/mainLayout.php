<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="<?= BACKSTEP ?><?=APPLICATION?>Images/favicon.png">
    <link rel="stylesheet" type="text/css" href="<?= BACKSTEP ?>Public/Style/Main/main-structure.css?v=<?= RELOAD_INDICATOR ?>" />  
    <link rel="stylesheet" type="text/css" href="<?= BACKSTEP ?>Public/Style/Theme/light-theme.css?v=<?= RELOAD_INDICATOR ?>" /> 
    <link rel="stylesheet" type="text/css" href="<?= BACKSTEP ?>Public/Style/Theme/dark-theme.css?v=<?= RELOAD_INDICATOR ?>" disabled/> 
    <script src="<?= BACKSTEP ?>Public/js/helperFunctions.js"></script>
    <title>Nback - <?=$views->title?></title>
</head>
<body>

    <!-- Header -->
    <header id="header">
        <?php if ( $header )    require_once APPLICATION."Templates/headerView.php";  ?>
    </header>    
    <!-- Navbar -->
    <aside id="navbar">        
        <?php if ( $navbar )   require_once APPLICATION.'Templates/navbarView.php'; ?>
    </aside>    
    <!-- Inspector -->
    <div id="inspector">
        <?php if ( isset($indicator) )//require_once APPLICATION.'Templates/infoLabelView.php';?>
    </div>    
    <!--Center-->  
    <div id="center">	      
        <?php require_once APPLICATION."Templates/{$views->module}/{$views->view}View.php";?>         
    </div>                   
</body>
</html>