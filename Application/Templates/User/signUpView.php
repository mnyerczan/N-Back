<div id="cu_div">
    <h2 align="center">
        <?php if ( !$isAdmin ): ?> 
            Please create the admin user!
        <?php elseif ( @$_GET['error'] ): ?>
            To create a new profile, please fill out the form below  
        <?php else: ?>
            The user may exists!
        <?php endif ?>
    </h2>
    <img id="create_user_img" src="img/edit_1.png">
</div>
<div id="create_user_form" >
    <form method="POST" action="index.php?index=2" name="create_user_form" onsubmit="return Validation()" enctype="multipart/form-data">
        <div>
        <label><p id="cu_lbl_1">Name:</p></label>
            <input  id="cu_1" type="text"  name="create_user_name" class="create_user_input" placeholder="" autofocus autocomplete="off" _required>
        </div>
        <div><label><p id="cu_lbl_3">E-mail:</p></label>
            <input id="cu_3" type="email"  name="create_user_email" class="create_user_input" autocomplete="off" >
        </div>
        <div><label><p id="cu_lbl_4">Date of birth:</p></label>
            <input id="cu_4" type="date"   name="create_user_birth" class="create_user_input" autocomplete="off" >
        </div>
        <br><br>
        <div><label><p id="cu_lbl_5">Login name:</p></label>
            <input id="cu_5" type="text"   name="create_user_user" class="create_user_input" autocomplete="off"  _required>
        </div>
        <div><label><p id="cu_lbl_6">Password:</p></label>
            <input id="cu_6" type="password" name="create_user_pass" minlength="4" name="create_user_pass" class="create_user_input" _required>
        </div>
        <div class="wrap" >
            <div id="file_text"></div>
            <label for="cu_file" class="file_lbl" id="cu_lbl_7" >
                <input type="text" name="max_filesize" value="200000" readonly hidden>
                <input id="cu_file" type="file" name="file" class="inputfile" onchange="read_url(this, '#user_profile_image', '<?= $user->theme ?>'); Modify_create_user_pic();" accept="image/*">
                Choose a file
            </label>
        </div>
        <input name="case" type="text" value="create_user" readonly hidden>
        <div for="create_user_add_img" id="create_user_add_box" >
            <div id="create_user_remove_img" onclick="Delete();" data-placement="left"  title="Delete datas"></div>
            <div style="float: right; width: 50%;">
                <button type="submit" name="create_user_submit" value="Save" id="create_user_submit_id" title="Save">
                    <img class="user_modify_img" src="img/save_datas.png">
                </button>
            </div>
        </div>
    </form>
</div>
<div id="create_user_pic" >
    <img style="float:rigth;" id="user_profile_image">
</div>
<script type="text/javascript">
    function Modify_create_user_pic(){

        document.getElementById("create_user_pic").style.height = "600px";
        document.getElementById("create_user_pic").style.width = "400px";
        document.getElementById("create_user_pic").style.margin = "15px"

        if(theme == 'black') {
            document.getElementById("create_user_pic").style.backgroundImage = 'radial-gradient( white, black)';
        }
        else{
            document.getElementById("create_user_pic").style.backgroundImage = 'none';
        }
    }
</script>