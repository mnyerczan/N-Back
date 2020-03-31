<?php

require_once APPLICATION.'Model/Validators/validator.php';
require_once APPLICATION.'Model/Validators/validateEmail.php';
require_once APPLICATION.'Model/Validators/validateUser.php';
require_once APPLICATION.'Model/Validators/validatePassword.php';
require_once APPLICATION.'Model/Validators/validateDate.php';


require_once APPLICATION.'Core/controller.php';

class signUpController extends Controller
{


    function __construct($matches)
    {                 
        parent::__construct();
        $this->SetDatas();        
                   
        if ( @$matches['action'] )
        {
            $action = $matches['action'].'Action';            
            $this->$action();
        }
        else
        {
            $this->Action();
        }        
    }




    function Action()
    {     
        $this->setValues();        
        $this->View( $this->datas, [ 'view' => 'signUp', 'module' => 'User'] );
    }






    private function submitAction()
    {
        //Ha nincsenek POST adatok
        if(!$_POST) 
        {
            header("Location: ".APPROOT); 
            return;
        }


        $email  = new ValidateEmail(    $_POST['create-user-email'] );
        $pass   = new ValidatePassword( $_POST['create-user-pass']  );
        $user   = new ValidateUser(     $_POST['create-user-name']  );
        $date   = new ValidateDate (    $_POST['create-user-date']  );                
    
        //Ha valamelyik adat nem felel meg a mintának
        if ( $email->errorMsg || $pass->errorMsg || $user->errorMsg || $date->errorMsg )
        {      
            $this->setValues( $user, $email, $pass, $date);

            $this->View( 
                $this->datas, 
                [ 
                    'view' => 'signUp', 
                    'module' => 'User'
                ]
            );
        
            return;
        }

        var_dump(file_get_contents($_FILES['create-user-file']['tmp_name']));
        var_dump($_POST);

        die;

        //különben beállitásra kerül a jogosultség
        $privilege = $_POST['create-user-name'] == 'Admin' ? 3 : 1;

        //és a userEntity userRegistry függvényén keresztül beírásra kerül az adatbázisba az új felhasználó.
        $result = $this->user->userRegistry( 
            [            
                ':email'        => trim( $email->getEmail() ),
                ':userName'     => trim( $user->getUser() ),
                ':password'     => md5( 'salt'.md5( trim( $pass->getPass() ) ) ),
                ':dateOfBirth'  => trim( $date->getDate() ),
                ':privilege'    => $privilege
            ]
        );

        // Sikertelen reg esetén hibaüzenet és vissza a signUpView-ra
        if ( !$result )
        {
            $this->datas['errorMessage'] = 'Email is alredy exists!';
            $this->datas['userEmailValue'] = null;

            $this->setValues( $user, $email, $pass, $date);
            $this->View( $this->datas, [ 'view' => 'signUp', 'module' => 'User'] );        

            return;
        }        
 
        header("Location: ".APPROOT);
    }



    



    /**
     * A formban megjelenítendő adatok előállítását végző függvény
     */
    private function setValues( $user = null, $email = null, $pass = null, $date = null)
    {     

        $crName = $user     ? $user->getUser()      : null;
        $crEmail= $email    ? $email->getEmail()    : null;


        $this->datas['nameLabel']       = $user->errorMsg  ?? 'Name';
        $this->datas['emailLabel']      = $email->errorMsg ?? 'E-mail';
        $this->datas['dateLabel']       = $date->errorMsg  ?? 'Date of birth';
        $this->datas['passwordLabel']   = $pass->errorMsg  ?? 'Password';
        $this->datas['privilegeLabel']  = 'Privilege';

        $this->datas['isAdmin']         = $this->user->getUsersCount()->num > 1;
        $this->datas['errorMessage']    = null;

        $this->datas['userNameValue']   = $this->datas['isAdmin']  ?  $crName : 'Admin';
        $this->datas['userEmailValue']  = $crEmail;


        $this->datas['enableNameInput'] = $this->datas['userNameValue'] ? 'readonly' : ''; 


    }
    
                 
}