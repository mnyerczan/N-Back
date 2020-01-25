<?php

use DB\baseDbApi;
use DB\EntityGateway;
use DB\MySql;


?>
<script>
var theme = "<?= $user->theme  ?  $user->theme : (isset($_cookie_datas['theme']) ? $_cookie_datas['theme'] : 'white');?>",
	is_session = "<?= $user->id > 1 ? true : false ?>",
	get_get_datas = "<?php $get_string = Get_get_datas_to_sting(); echo $get_string = '' ? '' : '?'.$get_string; ?>",
	uid = "<?= $user->id ?>",
	js_theme = "<?= $user->theme; ?>";

</script>
<div class="main_header_style" >
	<div id="header_width">
		<div id="theme_radio_container" title="Chose theme">
			<label class="radio_container" style="pointer-events:auto;">
				<input type="radio" class="create_forum_item_radio" id="" name="theme" value="black"
				<?php echo $user->theme == 'black' ? 'checked' : 
					(isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'black' ? 'checked' : ''); ?> 
					onclick="Check_modify_theme_radio(this.value, uid)" >Dark
				<span class="circle" ></span>
			</label>
			<label class="radio_container" style="pointer-events:auto;">
				<input type="radio" class="create_forum_item_radio" name="theme" value="white" 				
				<?php echo $user->theme != NULL ? ($user->theme == 'white' ? 'checked' : '') : (isset($_COOKIE['theme']) ? (($_COOKIE['theme'] == 'white') ? 'checked' : '') : 'checked' ) ?> 			
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
		<div class="header_login_box">
		<?php if( $user->id !== 1 ):?>				
			<a href="index.php?exit=1" onclick="return confirm('Are you sure?')">
				<button class="header_button">  Logout
					<img src="<?=APPLICATION?>img/logout_white.png" class="header_button_img" style="width:20px;height:17px;">
				</button>
			</a>
			<div class="border"></div>
				<a href="index.php?index=6" >
					<button class="header_button" title="Go to profile"><b id="name_marker">										
						<?=$user->name?><img src="<?=APPLICATION?>users/<?=explode('_', $user->file_name)[0]?>/<?= $user->file_name?>"
						class="header_button_img" id="header_user_pics" ></b>
					</button>
				</a>
			<div class="border"></div>
			<?php if($user->privilege == $admin_privilege):?>						
				<a href='index.php?index=6&c=1'>
					<button class='header_button'> New account
						<img src='<?=APPLICATION?>img/add_user_blue.png' class='header_button_img' style='width:20px;height:17px;'>
					</button>
				</a>
				<div class='border'></div>
			<?php endif ?>
		<?php endif ?>
		<?php if( !$user->login_datetime && (!isset($_GET['index']) || $_GET['index'] != '1' )): ?>		
			<a href='index.php?index=1'>
				<button class='header_button'>  Sign in
					<img src='<?=APPLICATION?>img/login_white.png' class='header_button_img' style='width:20px;height:17px;'>
				</button>
			</a>
			<div class='border'></div>
		<?php endif ?>	
		<?php if( !$user->login_datetime && (  !isset($_GET['index']) || $_GET['index'] != 6 ) ): ?> 				
			<a href='index.php?index=6&c=1'>
				<button class='header_button'> Sign Up							
					<img src='<?=APPLICATION?>img/add_user_blue.png' class='header_button_img' style='width:20px;height:17px;'>
				</button>
			</a>
			<div class='border'></div>
		<?php endif ?>			
		</div>
		<div class="tooltip" id="chat_tooltip" style="width: 120px; float: right;" ></div>
		<div class="clear"></div>
	</div>
</div>
<script>	
	if(_$("serias_left_time"))
	{	
		_$("serias_left_time").addEventListener(
				"load", 
				SeriaLeftTexHandler(
					"<?= $seria->seria ?>", 
					_$("serias_left_time"), 
					"<?= isset($_GET["nb_common"]) && $_GET["nb_common"] == 1 ? 1 : 0; ?>"
				)
			);
	}
</script>
