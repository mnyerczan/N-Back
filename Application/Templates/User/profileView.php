<main class="main-body">
    <table class="user-datas-table">
        <tr>
            <th>Properties</th>
            <th>Values</th>
        </tr>
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
            <td>Password</td>
            <td><?php for($i=0;$i<$user->passwordLength;$i++){echo '*';}?></td>
        </tr>                           
    </table>
    <div id="show-file-path"></div>        
        
    <input id="cu-file" type="file" name="create-user-file"  accept="image/*">         
    <label for="cu-file" id="cu-file-lbl" class="btn btn-rng " >Choose an image</label>
</main>