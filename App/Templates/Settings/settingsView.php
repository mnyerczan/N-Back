<link rel="stylesheet" href="<?=BACKSTEP?>Public/Style/settings.css?v=<?= RELOAD_INDICATOR ?>">
<main class="main-body">   
    <section class="settings-structure"> 
        <div class="settings-navbar">        
            <div class="settings-navbar-a">
                <img class="small-user-image" src="data:image/*;base64,<?= $user->imgBin?>">
                <div>
                    <?php   
                        if (strlen($user->userName) <= 15) echo $user->userName;
                        else echo substr($user->userName, 0, 15)."..";
                    ?>    
                <br> personal settings</div>
            </div>
            <?php if($settingsBar->personalItem->available): ?>
                <div class="settings-navbar-b">
                    <a href="<?=APPROOT.'/'?>settings" class="<?=$settingsBar->personalItem->status?>">Profile</a>            
                </div>  
            <?php endif ?>
            <?php if($settingsBar->nbackItem->available): ?>
                <div class="settings-navbar-c">
                    <a href="<?=APPROOT.'/'?>settings/nback" class="<?=$settingsBar->nbackItem->status?>">N-Back</a>            
                </div>  
            <?php endif ?>
        </div>        
        <?php include APPLICATION."Templates/Settings/{$views->item}View.php"; ?>        
    </section>
</main>        

