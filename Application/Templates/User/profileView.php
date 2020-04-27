<script src="<?=BACKSTEP?><?=APPLICATION?>Templates/User/profile.js?v=<?= CURRENT_TIMESTAMP ?>"></script>
<main class="main-body">    
    <section class="user-profile-structure"> 
        <p class="title">Drawer</p>       
        <div class="user-datas-tbl">
            <table class="user-datas-table">
                <caption>Yout personal datas</caption>     
                <tr>
                    <td>Name</td>
                    <td><?=$user->userName?></td>
                </tr>            
                <tr>
                    <td>E-mail</td>
                    <td><?=$user->email?></td>
                </tr>            
                <tr>
                    <td>Login date</td>
                    <td><?=$user->loginDatetime?></td>
                </tr>            
                <tr>
                    <td>Birth</td>
                    <td><?=$user->birth?></td>
                </tr>    
                <tr>
                    <td>Sex</td>
                    <td><?=$user->sex?></td>
                </tr>          
                <tr>
                    <td>Password</td>
                    <td><?php for($i=0;$i<$user->passwordLength;$i++){echo '*';}?></td>
                </tr>                               
            </table>
        </div>    
        <div class="user-image" id="user-profile-image">
            <img class="big-user-image" id="output" src="data:image/*;base64,<?= $user->imgBin?>">              
        </div>  
        <div class="d">Change your privates datas whenever you want! <a href="<?=APPLICATION?>/user/df"> Go!</a></div>
        <div class="user-img-chng-cell">
            <form action="/user/update">                 
                <input id="update-img" type="file" name="create-user-file"  accept="image/*">         
                <label for="update-img" class="btn btn-gray " >Change</label>
            </form>
        </div>                  
        <div class="line"></div> 
    </section>
</main>        