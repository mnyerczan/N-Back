<script src="<?= BACKSTEP ?><?=APPLICATION?>Templates/User/signIn.js?v=<?= CURRENT_TIMESTAMP ?>"></script>
<div class="sugn-up-cntr">
    <div class="login-a">
        <img src="https://lh3.googleusercontent.com/proxy/EFu1Xx3CpbquclMxRETxAdXSV883WcUxS3c5MLJ40epZIIb2nb7xbE2HeioTLgCOjZEWUXlek4_gwQ2ILMWeEwi4FY9ib75vPVTcVPHvoszAbETvJJlHuwfxVw" alt="">
    </div>
    <div class="login-b">
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
                
                <input type="submit" name="signIn-submit" id="signIn-submit" value="Submit" class="btn btn-grn sml-xs-btn" title="Save">                
            </form>
        </main>
    </div>
</div>