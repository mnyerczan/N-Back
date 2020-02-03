<script sr c="<?= RELPATH ?><?=APPLICATION?>Templates/User/signUp.js"></script>
<main class="usr-crt-frm" >
    <div>
        <h3 align="center">
            <?php if ( $isAdmin ): ?> 
                Please create the admin user!              
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

        <div id="show-file-path"></div>        

        <input id="cu-file" type="file" name="create-user-file"  accept="image/*">         
        <label for="cu-file" id="cu-file-lbl" class="btn btn-rng " >Choose an image</label>

        <input type="submit" name="cu-submit" id="cu-submit" value="Save" class="btn btn-save" title="Save">                
    </form>
</main>
<div id="create-user-pic" >
    <img id="user-profile-image">
</div>