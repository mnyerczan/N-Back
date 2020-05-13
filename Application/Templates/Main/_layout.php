<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="<?= BACKSTEP ?><?=APPLICATION?>Images/favicon.png">
    <link rel="stylesheet" type="text/css" href="<?= BACKSTEP ?><?=APPLICATION?>Style/Main/main-structure.css?v=<?= CURRENT_TIMESTAMP ?>" />  
    <link rel="stylesheet" type="text/css" href="<?= BACKSTEP ?><?=APPLICATION?>Style/Theme/light-theme.css?v=<?= CURRENT_TIMESTAMP ?>" /> 
    <link rel="stylesheet" type="text/css" href="<?= BACKSTEP ?><?=APPLICATION?>Style/Theme/dark-theme.css?v=<?= CURRENT_TIMESTAMP ?>" disabled/> 
    <script src="<?= BACKSTEP ?><?=APPLICATION?>Scripts/helperFunctions.js"></script>
    <title>Nback - <?=@$title?></title>
</head>
<body>
    <!-- Header -->
    <header id="header">
        <?php if ( @$header )    require_once APPLICATION."Templates/headerView.php";  ?>
    </header>    
    <!-- Navbar -->
    <aside id="navbar">        
        <?php if ( @$navbar )   require_once APPLICATION.'Templates/navbarView.php'; ?>
    </aside>    
    <!-- Inspector -->
    <div id="inspector">
        <?php if ( @$indicator )//require_once APPLICATION.'Templates/infoLabelView.php';?>
    </div>    
    <!--Center-->  
    <div id="center">	      
        <?php require_once APPLICATION."Templates/{$module}/{$view}View.php";?>         
    </div>                   
</body>
</html>