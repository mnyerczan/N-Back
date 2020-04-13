<script src="<?= $header->javaScript ?>"></script>
<header class="main-header" id="main-header">
    <div class="radio-box" title="Chose theme">
        <label class="radio-container">
            <input type="radio"  name="theme" value="black"
            <?= $header->theme == 'black' ? 'checked' : '' ?> 
                onclick="Check_modify_theme_radio(this.value, uid)" >Dark
            <span class="circle" ></span>
        </label>
        <label class="radio-container">
            <input type="radio" name="theme" value="white" 				
            <?= $header->theme == 'black' ? '' : 'checked' ?> 			
            onclick="Check_modify_theme_radio(this.value, uid)">Light
            <span class="circle"></span>
        </label>
    </div>
    <?php if($seria->seria > 0): ?>
		<div id='seria_text' title= "<?= $seria->seria ?> day series">
			<img src="<?= $header->seriaIconPath ?>" id="seria_flame_img">
			<pre id="serias_left_time"></pre>
		</div>
	<?php endif; ?>
	<div class="hdr-coordinate-bx">

    <?php if( $header->userId > 1 ):?>	
        
        <ul id="header-list">
            <li title="Go to profile">		      
                <?php if ($header->imgBin): ?>          
                    <img class="profile-img" src="data:image/*;base64,<?= $header->imgBin?>">
                <?php else: ?>
                    <img class="profile-img" src="<?=APPLICATION?>Images/user_blue.png">
                <?php endif ?>
                <section class="drop-down-container">    
                    <a href="<?=APPROOT?>user" title="Go to profile">					
                        <b><?=$header->userName?></b>
                    </a>
                    <?php if($header->privilege == 3 && $view !== 'signUp' ):?>						
                        <a href="<?=APPROOT?>signUp">
                            New account
                        </a>				
                    <?php endif ?>                    
                    <hr>
                    <a href="<?=APPROOT?>logUot" onclick="return confirm('Are you sure?')">
                        Logout<img src="<?= $header->logoutIconPath ?>" >									
                    </a>
                </section>
            </li>
        </ul>

    <?php endif ?>
    <ul>
        <?php if( !$header->loginDatetime && $view !== 'signIn' ): ?>		
            <li><a href='<?=APPROOT?>signIn'>Sign in</a></li>			
        <?php endif ?>	
        <?php if( !$header->loginDatetime && $view !== 'signUp' ): ?> 				
            <li><a href='<?=APPROOT?>signUp'>Sign Up</a></li>
        <?php endif ?>	
    </ul>	
	</div>
</header>