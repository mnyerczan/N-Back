 <script src="<?=BACKSTEP?><?=APPLICATION?>Templates/User/account.js?v=<?= CURRENT_TIMESTAMP ?>"></script>
<link rel="stylesheet" href="<?=BACKSTEP?><?=APPLICATION?>Style/settings.css?v=<?= CURRENT_TIMESTAMP ?>">
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
            <div class="settings-navbar-b">
                <a href="<?=APPROOT?>settings/personal" class="<?=@$personal?>">Profile</a>            
            </div>  
            <div class="settings-navbar-c">
                <a href="<?=APPROOT?>settings/nback" class="<?=@$nback?>">N-Back</a>            
            </div>  
        </div>        
        <?php include APPLICATION."Templates/Settings/{$item}View.php"; ?>        
    </section>
</main>        

