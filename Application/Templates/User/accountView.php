<link rel="stylesheet" href="<?=BACKSTEP?><?=APPLICATION?>Style/account.css?v=<?= CURRENT_TIMESTAMP ?>">
<div class="modal" id="usr-modal"></div>
<main class="main-body">    
    <section class="user-profile-structure"> 
        <div class="title">Drawer</div>   
        <div class="user-g">Profile picture</div>              
        <div class="user-b">       
            <label for="">Name</label>                                                
            <p><?=$user->userName?></p>
            <label for="">Email address</label> 
            <p><?=$user->email?></p>
            <label for="">Ligin date</label> 
            <p><?=$user->loginDatetime?></p>
            <label for="">Your birth date</label> 
            <p><?=$user->birth?></p>
            <label for="">Your sex</label> 
            <p><?=$user->sex?></p>                                                        
        </div>            
        <div class="user-c" id="user-profile-image"><img class="big-user-image" id="output" src="data:image/*;base64,<?= $user->imgBin?>"></div>  
        <div class="user-f"></div> 
    </section>
</main>        


