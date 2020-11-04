<script src="<?= $header->javaScript ?>"></script>
<nav class="inspector-container">	
    <a class="emoji-container" href="<?= APPROOT.'/' ?>nback" tabIndex="-1" title="Go to the game">&#9889;</a>		        
    <a class="emoji-container" href="<?= APPROOT.'/' ?>documents" tabIndex="-1" title="Go to the documents">&#128220;</a>	
    <div class="radio-box" title="Chose theme">
        <label class="radio-container">
            <span class="emoji-container" >&#128161;</span>
            <input type="checkbox"  name="theme" value="black"
            <?= $user::$theme == 'black' ? 'checked' : '' ?> 
                onclick="Check_modify_theme_radio(this.value, uid)" >                
            <span class="circle" ></span>            
        </label>        
    </div>
    <?php if($seria): ?>
        <div id='seria_text' title= "<?= $seria ?> day series">
            <img src="<?= $header->seriaIconPath ?>" id="seria_flame_img">
            <pre id="serias_left_time"></pre>
        </div>
    <?php endif; ?>
</nav>    

<?php if ($views->errorMsg): ?>
    <div class="error-line"><?=$views->errorMsg?></div>
<?php elseif (isset($_GET['sm'])): ?>
    <div class="successfully-line"><?=$_GET['sm']?></div>
<?php endif ?> 

<div class="hdr-coordinate-bx">	    
    <div id="header-list">        	                  
        <span class="emoji-container">&#9776;</span>
        <section class="drop-down-container">    
        <?php if($user::$loged): ?>
            <div id="drop-dw-name-cnt">Signed in as <br>
                <b><?= strlen($user::$name) <= 15 ? $user::$name : substr($user::$name, 0, 15).".." ?></b>
            </div>
            <hr>
            <a href="<?=APPROOT.'/'?>account" title="Go to profile">					
                Your profile
            </a>
            <a href="<?=APPROOT.'/'?>settings" title="Go to settings">
                Settings
            </a>
            <?php if($user::$privilege == 3 && $views->view !== 'signUp' ):?>						
            <a href="<?=APPROOT.'/'?>signUp/form">
                New account
            </a>				
            <?php endif ?>                    
            <hr>
            <a href="<?=APPROOT.'/'?>logUot" onclick="return confirm('Are you sure?')">
                Sign out<img src="<?= $header->logoutIconPath ?>" >									
            </a>
        <?php else: ?>
            <div id="drop-dw-name-cnt">You are not logged <br>
                <b><?= strlen($user::$name) <= 15 ? $user::$name : substr($user::$name, 0, 15).".." ?></b>
            </div>
            <hr>
            <a href="<?=APPROOT.'/'?>settings/nback" title="Go to settings">
                Settings
            </a>
            <a href="<?=APPROOT.'/'?>signUp/form">
                New account
            </a>
            <a href='<?=APPROOT.'/'?>signIn'  class="login">
                Sign in
            </a>
        <?php endif ?>
        </section>            
    </div> 
</div>       
