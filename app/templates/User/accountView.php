<link rel="stylesheet" href="<?=BACKSTEP?>public/Style/account.css?v=<?= RELOAD_INDICATOR ?>">
<main class="main-body">    
    <section class="user-profile-structure"> 
        <div class="title">Drawer</div>   
        <div class="user-g">Profile picture</div>              
        <div class="user-b">       
            <label for="">Name</label>                                                
            <p><?=$user::$name?></p>
            <label for="">Email address</label> 
            <p><?=$user::$email?></p>
            <label for="">Ligin date</label> 
            <p><?=$user::$loginDatetime?></p>
            <label for="">Your birth date</label> 
            <p><?=$user::$birth?></p>
            <label for="">Your sex</label> 
            <p><?=$user::$sex?></p>                                                        
        </div>            
        <div class="user-c" id="user-profile-image"><img class="big-user-image" id="output" src="data:image/*;base64,<?= $user::$bin?>"></div>  
        <div class="user-f"></div> 
    </section>
</main>        


