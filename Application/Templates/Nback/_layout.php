<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="<?= BACKSTEP ?><?=APPLICATION?>Images/favicon.png">
    <link rel="stylesheet" type="text/css" href="<?= BACKSTEP ?><?=APPLICATION?>Style/Nback/nback-structure.css?v=<?= RELOAD_CONTROLLER ?>" />  
    <script src="<?= BACKSTEP ?><?=APPLICATION?>Scripts/helperFunctions.js"></script>
    <title>Nback - <?=@$title?></title>
</head>
<body>
    <!-- Header -->
    <header id="deader">
        <?php if ( @$header )    require_once APPLICATION."Templates/headerView.php";  ?>	
    </header>    
	<!--Center-->    
    <div id="center">
        <?php require_once APPLICATION."Templates/{$module}/{$view}View.php";?>    
    </div>    
</body>
</html>