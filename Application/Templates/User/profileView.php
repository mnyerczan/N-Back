<script src="<?=BACKSTEP?><?=APPLICATION?>Templates/User/profile.js?v=<?= CURRENT_TIMESTAMP ?>"></script>
<div class="modal" id="usr-modal"></div>
<main class="main-body">    
    <section class="user-profile-structure"> 
        <p class="title">Drawer</p>       
        <div class="user-datas-tbl">
            <div class="model-content-container" id="usr-mdl-content">
                <form action="<?=APPROOT?>user/update" method="POST">
                    <table class="user-datas-table">
                        <caption>Your personal datas</caption>     
                        <tr>
                            <td colspan="2">
                                <label for="update-user-name" id="update-user-name-lbl"></label>
                            </td>
                        </tr>   
                        <tr>
                            <td>Name</td>
                            <td>
                                <input name="update-user-name" class="persona-data-input" type="text" value="<?=$user->userName?>" readonly>
                            </td>
                        </tr>   
                        <tr>
                            <td colspan="2">
                                <label  for="update-user-email" id="update-user-email-lbl"></label>
                            </td>
                        </tr>         
                        <tr>
                            <td>E-mail</td>
                            <td>                                
                                <input name="update-user-email" class="persona-data-input" type="email" value="<?=$user->email?>" readonly>
                            </td>
                        </tr>                          
                        <tr>
                            <td>Login date</td>
                            <td>                          
                                <?=$user->loginDatetime?>
                            </td>
                        </tr> 
                        <tr>
                            <td colspan="2">
                                <label for="update-user-birth" id="update-user-birth-lbl"></label>
                            </td>
                        </tr>              
                        <tr>
                            <td>Birth</td>
                            <td>                         
                                <input name="update-user-birth" class="persona-data-input" type="date" value="<?=$user->birth?>" readonly>
                            </td>
                        <tr>
                            <td>Sex</td>
                            <td>                                
                                <input name="update-user-sex" class="persona-data-input" type="text" value="<?=$user->sex?>" readonly>
                            </td>
                        </tr>  
                        <tr>
                            <td colspan="2">
                                <label for="update-user-password" id="update-user-password-lbl"></label>
                            </td>
                        </tr>           
                        <tr>
                            <td>Password</td>
                            <td>                         
                                <input name="update-user-password" class="persona-data-input" type="password" value="<?php for($i=0;$i<$user->passwordLength;$i++){echo '*';} ?>" id="update-user-pw" readonly required>
                            </td>
                        </tr> 
                        <tr> 
                            <td></td>                           
                            <td id="show-pw" >Show my password</td>
                        </tr>    
                        <tr>
                            <td><input type="reset" value="Reset" class="controlInputs"></td>
                            <td><input type="submit" value="Send" class="controlInputs"></td>
                        </tr>                               
                    </table>
                </form>
            </div>
        </div>    
        <div class="user-image" id="user-profile-image">
            <img class="big-user-image" id="output" src="data:image/*;base64,<?= $user->imgBin?>">              
        </div>  
        <div class="d">Change your privates datas whenever you want! <a href="#" id="opn-usr-mdl"> Change</a></div>
        <div class="user-img-chng-cell">
            <form action="/user/update">                 
                <input id="update-img" type="file" name="create-user-file"  accept="image/*">         
                <label for="update-img" class="btn btn-gray " >Change</label>
            </form>
        </div>                  
        <div class="line"></div> 
    </section>
</main>        