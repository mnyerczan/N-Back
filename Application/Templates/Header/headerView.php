<script src="<?= $header->javaScript ?>"></script>
<header class="main-header" id="main-header">
    <div class="radio-box" title="Chose theme">
        <label class="radio-container">
            <span class="emoji-container" >&#128161;</span>
            <input type="checkbox"  name="theme" value="black"
            <?= $header->theme == 'black' ? 'checked' : '' ?> 
                onclick="Check_modify_theme_radio(this.value, uid)" >                
            <span class="circle" ></span>            
        </label>        
    </div>
    <?php if(@$seria): ?>
		<div id='seria_text' title= "<?= $seria ?> day series">
			<img src="<?= $header->seriaIconPath ?>" id="seria_flame_img">
			<pre id="serias_left_time"></pre>
		</div>
	<?php endif; ?>
	<div class="hdr-coordinate-bx">

    <?php if( $header->userId > 1 ):?>	
        
        <ul id="header-list">
            <li title="Go to profile">		                  
                <span class="emoji-container">&#9776;</span>
                    <!--img class="profile-img" src="data:image/*;base64,<?= $header->imgBin?>"-->              
                <section class="drop-down-container">    
                    <a href="<?=APPROOT?>user" title="Go to profile">					
                        <b><?=$header->userName?></b>
                    </a>
                    <?php if($header->privilege == 3 && $view !== 'signUp' ):?>						
                        <a href="<?=APPROOT?>signUp/form">
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
                <li><a href='<?=APPROOT?>signUp/form'>Sign Up</a></li>
            <?php endif ?>	
        </ul>	
    </div>
    
    <nav class="inspector-container">	
        <a class="emoji-container" href="<?= APPROOT ?>nBack" tabIndex="-1">&#9889;</a>		        
        <a class="emoji-container" href="<?= APPROOT ?>documents" tabIndex="-1">&#128220;</a>	
        
    </nav>
</header>