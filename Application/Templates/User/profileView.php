<main class="main-body">
    <section class="center-container">
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

        <img class="big-user-image" src="data:image/*;base64,<?= $user->imgBin?>">        
        <div class="clear"></div>

        <div id="show-file-path"></div>        
            
        <input id="cu-file" type="file" name="create-user-file"  accept="image/*">         
        <label for="cu-file" id="cu-file-lbl" class="btn btn-rng " >Choose an image</label>
    </section>
</main>