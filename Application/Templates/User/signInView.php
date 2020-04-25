<script src="<?= BACKSTEP ?><?=APPLICATION?>Templates/User/signIn.js?v=<?= CURRENT_TIMESTAMP ?>"></script>
<main class="usr-crt-frm" >
    <div>
        <h3>
            <?= $message ?>
        </h3>
    </div>
    <form method="POST" id="signIn-form" action="<?= BACKSTEP ?>signIn/submit" name="signInForm" >

        <label for="signUp-email"><?= $emailLabel ?></label>
        <input id="signIn-email" type="email" name="signIn-email" value="<?= $email ?>" autofocus>

        <label for="signIn-pass"><?= $passwordLabel ?></label>
        <input id="signIn-pass" type="password" name="signIn-pass" minlength="4" name="signUp-pass" autocomplete="off" >
        
        <input type="submit" name="signIn-submit" id="signIn-submit" value="Submit" class="btn btn-save" title="Save">                
    </form>
</main>
