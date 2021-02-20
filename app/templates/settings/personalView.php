<script src="<?=BACKSTEP?>public/js/personlSettings.js?v=<?= RELOAD_INDICATOR ?>"></script>
<form action="<?=APPROOT.'/'?>settings/personalUpdate" method="POST" id="personalForm"></form> 
<div class="personal-setting-contents">
    <div class="title">Drawer</div>  
    <div class="user-b">Profile picture</div>               
    <div class="user-c update">       
        <label for=""><?=$nameLabel?></label>                                                
        <input type="text" value="<?=$user::$name?>" name="update-user-name" form="personalForm" <?=$enableNameInput?> autofocus>

        <label for=""><?=$emailLabel?></label>             
        <input type="email" value="<?=$user::$email?>" name="update-user-email" form="personalForm">
             
        <label for="">Your sex</label>     
        <div class="sex-container">
            <div class="radio-box">
                <label class="radio-container">  
                    <span class="emoji-container">&#128104;</span>                  
                    <input type="radio"  name="update-user-sex" value="male" <?= $user::$sex == 'male' ? 'checked' : '' ?> form="personalForm">                    
                    <span class="circle" ></span>                    
                </label>        
            </div>
            <div class="radio-box" title="Chose theme">
                <label class="radio-container">  
                    <span class="emoji-container">&#128105;</span>                  
                    <input type="radio"  name="update-user-sex" value="female" <?= $user::$sex == 'female' ? 'checked' : '' ?> form="personalForm">
                    <span class="circle" ></span>                    
                </label>        
            </div>
        </div>
        <label for=""><?=$aboutLabel?></label>
        <textarea id="" cols="30" rows="10" name="update-user-about" data-warning-text="{{remaining}} remaining"  data-input-max-length="255" placeholder="Tell us a litle bit about yourself" form="personalForm"><?=$user::$about?></textarea>
        <input type="submit" id="opn-usr-mdl" class="btn btn-grn sml-btn" value="update profile" form="personalForm">
    </div> 
    <div class="user-h"></div>  
    <div class="user-d">
        <form action="<?=APPROOT.'/'?>settings/passwordUpdate" method="POST">
            <label for=""><?=$oldPasswordLabel?></label>  
            <input type="password" placeholder="<?php for($i=0;$i<$user::$passwordLength;$i++){echo '*';} ?>" name="update-user-old-password" required>
            <label for=""><?=$passwordLabel?></label>             
            <input type="password" placeholder="" name="update-user-password" required>
            <label for="">Re-type new password</label>  
            <input type="password" placeholder="" name="update-user-retype-password" required>
            <br>
            <input type="submit" id="opn-usr-mdl" class="btn btn-std sml-btn" value="Update password">
        </form>                
    </div>     
    <div class="user-f" id="user-profile-image">
        <img class="big-user-image" id="output" src="data:image/*;base64,<?= $user::$bin?>">
        <label for="update-img" class="btn btn-blk sml-btn" >&#128394; Edit</label>
        <form action="/settings/imageUpdate" id="imageForm" enctype="multipart/form-data">                 
            <input id="update-img" type="file" name="user-image"  accept="image/*">         
        </form>
    </div>    
    <div class="user-g"></div>                  
<div>

