<link rel="stylesheet" href="<?=BACKSTEP?>Public/Style/settings.css?v=<?= RELOAD_INDICATOR ?>">
<main class="main-body">   
    <section class="settings-structure"> 
        <div class="settings-navbar">        
            <div class="settings-navbar-a">
                <img class="small-user-image" src="data:image/*;base64,<?= $user::$bin?>">
                <div>
                    <?php   
                        if (strlen($user::$name) <= 15) echo $user::$name;
                        else echo substr($user->name, 0, 15)."..";
                    ?>    
                <br> personal settings</div>
            </div>
            <?php foreach($settingsBar->submenus as $settingsMenu): ?>
                <?php if($settingsMenu["available"]): ?>
                    <a href="<?=APPROOT.'/'.$settingsMenu["url"]?>" class="<?=$settingsMenu["status"]?>"><?=$settingsMenu["name"]?>
                        <div class="settings-navbar-b"></div>
                    </a>
                <?php endif ?>                
            <?php endforeach ?>
        </div>       
        <?php include APPLICATION."Templates/Settings/{$views->item}View.php"; ?>        
    </section>
</main>        

