<script src="<?= RELPATH ?><?=APPLICATION?>Templates/User/signUp.js"></script>
<main class="usr-crt-frm" >
    <div>
        <h3 align="center">
            <?php if ( $isAdmin ): ?> 
                Please create the admin user! 
            <?php else: ?>             
                Please fill out the form
            <?php endif ?>
        </h3>
    </div>
    <form method="POST" id="create-user-form" action="<?= RELPATH ?>signUp/submit" name="createUserForm"  enctype="multipart/form-data">

        <label for="cu-name"><?= $nameLabel ?></label>
        <input id="cu-name" type="text" name="create-user-name" value="<?=$isAdmin?>" <?= $enableNameInput ?> autofocus autocomplete="off" >

        <label for="cu-mail"><?= $emailLabel ?></label>
        <input id="cu-mail" type="email" name="create-user-mail" autocomplete="off" >

        <label for="cu-date"><?= $dateLabel ?></label>
        <input id="cu-date" type="date" name="create-user-date"  autocomplete="off" >
    
        <label for="cu-pass"><?= $passwordLabel ?></label>
        <input id="cu-pass" type="password" name="create-user-pass" minlength="4" name="create-user-pass" autocomplete="off" >


        <?php if ( $user->userName == 'Admin' ): ?>

        <label for="cu-privilege"><?= $privilegeLabel ?></label>
        <input id="cu-privilege" type="number" name="create-user-privilege" minlength="4" name="create-user-privilege" value="0" step="1" max="3" min="1" >    

        <?php endif ?>

        <div id="show-file-path"></div>        

        <input id="cu-file" type="file" name="create-user-file"  accept="image/*">         
        <label for="cu-file" id="cu-file-lbl" class="btn btn-rng " >Choose an image</label>

        <input type="submit" name="cu-submit" id="cu-submit" value="Save" class="btn btn-save" title="Save">                
    </form>
</main>
<div id="create-user-pic" >
    <img id="user-profile-image">
</div>