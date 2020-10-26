<script src="<?= $header->javaScript ?>"></script>
<nav class="inspector-container">	
    <a class="emoji-container" href="<?= APPROOT.'/' ?>nBack" tabIndex="-1" title="Go to the game">&#9889;</a>		        
    <a class="emoji-container" href="<?= APPROOT.'/' ?>documents" tabIndex="-1" title="Go to the documents">&#128220;</a>	
    <div class="radio-box" title="Chose theme">
        <label class="radio-container">
            <span class="emoji-container" >&#128161;</span>
            <input type="checkbox"  name="theme" value="black"
            <?= $header->theme == 'black' ? 'checked' : '' ?> 
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
<?php if( $header->userId > 1 ):?>	
    
    <div id="header-list">        	                  
        <span class="emoji-container">&#9776;</span>
        <section class="drop-down-container">    
            <div id="drop-dw-name-cnt">Signed in as <br><b>
                <?php   
                    if (strlen($header->userName) <= 15) echo $header->userName;
                    else echo substr($header->userName, 0, 15)."..";
                ?>
                </b></div>
            <hr>
            <a href="<?=APPROOT.'/'?>account" title="Go to profile">					
                Your profile
            </a>
            <a href="<?=APPROOT.'/'?>settings" title="Go to settings">
                Settings
            </a>
            <?php if($header->privilege == 3 && $views->view !== 'signUp' ):?>						
                <a href="<?=APPROOT.'/'?>signUp/form">
                    New account
                </a>				
            <?php endif ?>                    
            <hr>
            <a href="<?=APPROOT.'/'?>logUot" onclick="return confirm('Are you sure?')">
                Sign out<img src="<?= $header->logoutIconPath ?>" >									
            </a>
        </section>            
    </div>

    <?php endif ?>
    <ul>
        <?php if( !$header->loginDatetime && $views->view !== 'signIn' ): ?>		
            <li><a href='<?=APPROOT.'/'?>signIn'  class="login">Sign in</a></li>			
        <?php endif ?>	
        <?php if( !$header->loginDatetime && $views->view !== 'signUp' ): ?> 				
            <li><a href='<?=APPROOT.'/'?>signUp/form' class="login">Sign Up</a></li>
        <?php endif ?>	
    </ul>	
</div>       
