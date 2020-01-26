<header class="main-header">
    <div class="radio-box" title="Chose theme">
        <label class="radio-container">
            <input type="radio"  name="theme" value="black"
            <?php echo $user->theme == 'black' ? 'checked' : 
                (isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'black' ? 'checked' : ''); ?> 
                onclick="Check_modify_theme_radio(this.value, uid)" >Dark
            <span class="circle" ></span>
        </label>
        <label class="radio-container">
            <input type="radio" name="theme" value="white" 				
            <?= $user->theme != NULL ? ($user->theme == 'white' ? 'checked' : '') : (isset($_COOKIE['theme']) ? (($_COOKIE['theme'] == 'white') ? 'checked' : '') : 'checked' ) ?> 			
            onclick="Check_modify_theme_radio(this.value, uid)">Light
            <span class="circle"></span>
        </label>
    </div>
    <?php if($seria->seria > 0): ?>
		<div id='seria_text' title= "<?= $seria->seria, $seria->seria > 1 ? " days series" : " day series" ?>">
			<img src="<?=APPLICATION?>img/fat_flame_red.png" id="seria_flame_img">
			<pre id="serias_left_time"></pre>
		</div>
		<?php endif; ?>
		<div class="hdr-coordinate-bx">
		<?php if( $user->id !== 1 ):?>				
        <a href="<?=APPROOT?>exit" onclick="return confirm('Are you sure?')">
            Logout<img src="<?=APPLICATION?>img/logout_white.png" class="header_button_img" style="width:20px;height:17px;">									
        </a>
        <a href="<?=APPROOT?>user" title="Go to profile">					
            <?=$user->name?><img src="<?=APPLICATION?>users/<?=explode('_', $user->fileName)[0]?>/<?= $user->fileName?>"
            class="header_button_img" id="header_user_pics" ></b>
        </a>
        <?php if($user->privilege == $admin_privilege):?>						
        <a href="<?=APPROOT?>signUp/admin">
            New account
        </a>				
        <?php endif ?>
		<?php endif ?>
		<?php if( !$user->loginDatetime && (!isset($_GET['index']) || $_GET['index'] != '1' )): ?>		
        <a href='<?=APPROOT?>signIn'>
            Sign in
        </a>			
		<?php endif ?>	
		<?php if( !$user->loginDatetime && (  !isset($_GET['index']) || $_GET['index'] != 6 ) ): ?> 				
        <a href='<?=APPROOT?>signUp'>
            Sign Up
        </a>
		<?php endif ?>			
		</div>
		<div class="tooltip" id="chat_tooltip" style="width: 120px; float: right;" ></div>
		<div class="clear"></div>
	</div>
</header>