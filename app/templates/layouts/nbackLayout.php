<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="icon" type="image/png" href="<?= BACKSTEP ?><?=APPLICATION?>images/favicon.png">
        <link rel="stylesheet" type="text/css" href="<?= BACKSTEP ?>public/Style/nback/nback-structure.css?v=<?= RELOAD_INDICATOR ?>" />  
        <link rel="stylesheet" type="text/css" href="<?= BACKSTEP ?>public/Style/theme/light-theme.css?v=<?= RELOAD_INDICATOR ?>" /> 
        <link rel="stylesheet" type="text/css" href="<?= BACKSTEP ?>public/Style/theme/dark-theme.css?v=<?= RELOAD_INDICATOR ?>" disabled/> 
        <script src="<?= BACKSTEP ?>public/js/helperFunctions.js?"></script>
        <script src="<?= BACKSTEP ?>public/js/nbackEngine.js?v=<?= RELOAD_INDICATOR ?>"></script>
        <script id="options"><?=$jsonOptions?></script>
        <title>Nback - <?=$views->title?></title>
    </head>
    <body>

        <!-- Header -->
        <header id="header">
            <?php if ( $header )    require_once APPLICATION."templates/headerView.php";  ?>
        </header>       
        <!--Center-->       
        <?php require_once APPLICATION."templates/{$views->module}/{$views->view}View.php";?>         
    </body>
</html>