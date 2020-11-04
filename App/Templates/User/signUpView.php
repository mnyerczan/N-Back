<script src="<?=$signUpJsPath?>"></script>
<main class="usr-crt-container">
    <div class="usr-crt-frm" >
        <div>
            <h3 style="text-align:center">
                <?php if ( !$errorMessage ): ?>
                    <?php if ( $isAdmin ): ?> 
                        Please create the admin user! 
                    <?php else: ?>             
                        Please fill out the form
                    <?php endif ?>
                <?php else: ?>
                    <?= $errorMessage ?>
                <?php endif ?>
            </h3>
        </div>
        <form method="POST" id="create-user-form" action="<?= BACKSTEP ?>signUp/submit" name="createUserForm"  enctype="multipart/form-data">

            <label for="cu-name"><?= $nameLabel ?></label>
            <input id="cu-name" type="text" name="create-user-name" value="<?=$userNameValue?>" <?= $enableNameInput ?> autofocus autocomplete="off" >

            <label for="cu-mail"><?= $emailLabel ?></label>
            <input id="cu-mail" type="email" name="create-user-email" value="<?=$userEmailValue?>" autocomplete="off" >

            <label for="cu-date"><?= $dateLabel ?></label>
            <input id="cu-date" type="date" name="create-user-date"  autocomplete="off" >
        
            <label for="cu-pass"><?= $passwordLabel ?></label>
            <input id="cu-pass" type="password" name="create-user-pass" minlength="4" name="create-user-pass" autocomplete="off" >

            <label for="">Sex</label>
            <div class="sex-container">
                <div class="radio-box">
                    <label class="radio-container">  
                        <span class="emoji-container">&#128104;</span>                  
                        <input type="radio"  name="sex" value="male" checked>                    
                        <span class="circle" ></span>                    
                    </label>        
                </div>
                <div class="radio-box" title="Chose theme">
                    <label class="radio-container">  
                        <span class="emoji-container">&#128105;</span>                  
                        <input type="radio"  name="sex" value="female" >                                        
                        <span class="circle" ></span>                    
                    </label>        
                </div>
            </div>        
                
            <?php if ( $user::$name == 'Admin' ): ?>

                <label for="cu-privilege"><?= $privilegeLabel ?></label>
                <input id="cu-privilege" type="number" name="create-user-privilege" minlength="4" name="create-user-privilege" value="1" step="1" max="3" min="1" >    

            <?php endif ?>

        
            <input type="submit" name="cu-submit" id="cu-submit" value="Save" class="btn btn-grn sml-xs-btn" title="Save">                
        </form>
    </div>
</main>