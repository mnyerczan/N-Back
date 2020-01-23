<style>
	body{
		overflow: hidden;
	}
</style>
<div id="login_container">
	<div align="middle" id="login_label">Login</div>
	<div class="clear" ></div>
	<div id="login_input_box">
		<table id="login_form_label_box">
			<form  method="POST" _action="index.php" id="login_form" onsubmit="return Login_validate()">
				<tr>
					<td><label for="loggin_name"><b>User name:</b></label></td>
					<td><input type="text" name="login_u_name" id="loggin_name" placeholder="" autocomplete="off" onClick="login_form_message_change()" autofocus required></td>
				</tr>
				<tr>
					<td><label for="login_form_pass"><b>Password:</b></label></td>
					<td><input type="password" name="pass" id="login_form_pass" minlength="4" onClick="login_form_message_change()" required></td>
				</tr>
					<input name="case" type="text" value="loggin" readonly hidden>
			</form>
		</table>
		<div>
			<button type="submit" form="login_form" name="login_form_ok" value="Mehet" id="login_form_ok" style="pointer-events:auto; cursor: pointer;">
				<img src="img/login_blue.png" >
			</button>
		</div>
		<div id="loggin_form_message">
			<b id='message' style='color:red;'></b>
		</div>
	</div>
</div>