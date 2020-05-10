<script src="<?=BACKSTEP?><?=APPLICATION?>Templates/User/account.js?v=<?= CURRENT_TIMESTAMP ?>"></script>
<main class="main-body">    
    <form action="<?=APPROOT?>account/personalUpdate" method="POST" id="personalForm"></form>  
    <section class="user-update-structure">        
        <div class="title">Drawer</div>  
        <div class="user-g">Profile picture</div>               
        <div class="user-b update">       
            <label for=""><?=$nameLabel?></label>                                                
            <input type="text" value="<?=$user->userName?>" name="update-user-name" form="personalForm" <?=$enableNameInput?>>

            <label for=""><?=$emailLabel?></label>             
            <input type="email" value="<?=$user->email?>" name="update-user-email" form="personalForm">
            
            <label for="">Ligin date</label>             
            <p><?=$user->loginDatetime?></p>

            <label for=""><?=$dateLabel?></label>             
            <input type="date" value="<?=$user->birth?>" name="update-user-birth" form="personalForm">

            <label for="">Your sex</label>     
            <div class="sex-container">
                <div class="radio-box">
                    <label class="radio-container">  
                        <span class="emoji-container">&#128104;</span>                  
                        <input type="radio"  name="sex" value="male" <?= $user->sex == 'male' ? 'checked' : '' ?> form="personalForm">                    
                        <span class="circle" ></span>                    
                    </label>        
                </div>
                <div class="radio-box" title="Chose theme">
                    <label class="radio-container">  
                        <span class="emoji-container">&#128105;</span>                  
                        <input type="radio"  name="sex" value="female" <?= $user->sex == 'female' ? 'checked' : '' ?> form="personalForm">
                        <span class="circle" ></span>                    
                    </label>        
                </div>
            </div>                    

            <label for=""><?=$passwordLabel?></label>             
            <input type="password" placeholder="<?php for($i=0;$i<$user->passwordLength;$i++){echo '*';} ?>" name="update-user-password" form="personalForm" required>
        </div>                
        <div class="user-d">
            <input type="submit" id="opn-usr-mdl" class="btn btn-grn sml-btn" form="personalForm">
        </div>                        
        <div class="user-f"></div> 
        <div class="user-c" id="user-profile-image">
            <img class="big-user-image" id="output" src="data:image/*;base64,<?= $user->imgBin?>">
            <label for="update-img" class="btn btn-blk sml-btn" >&#128394; Edit</label>
            <form action="/user/update">                 
                <input id="update-img" type="file" name="create-user-file"  accept="image/*">         
            </form>
        </div>  
    </section>
</main>        

