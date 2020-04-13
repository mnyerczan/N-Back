<?php

use Model\Image\ImageConverter;
use DB\EntityGateway;

require_once APPLICATION.'Models/Validators/validator.php';
require_once APPLICATION.'Models/Validators/validateEmail.php';
require_once APPLICATION.'Models/Validators/validateUser.php';
require_once APPLICATION.'Models/Validators/validatePassword.php';
require_once APPLICATION.'Models/Validators/validateDate.php';
require_once APPLICATION.'Models/Image/ImageConverter.php';

require_once APPLICATION.'Core/MainController.php';


class signUpController extends MainController
{

    function __construct($matches)
    {                 
        $this->db  = EntityGateway::GetInstance();

        parent::__construct();
        $this->SetDatas();        
                   
        if ( @$matches['action'] )
        {
            $action = $matches['action'].'Action';            
            $this->$action();
        }
        else
        {
            $this->FormAction();
        }        
    }




    function FormAction()
    {     
        $this->setValues();        
        $this->View( $this->datas, [ 'view' => 'signUp', 'module' => 'User'] );
    }





    /**
     * @return mixed true if successful or integer if is error
     * 
     * errno:  
     *  1   bad method or user don't sent form
     *  2   invalid values
     *  3   database error(user datas)
     *  4   database erroe(image data)
     */
    private function submitAction()
    {                  
 
        $email  = new ValidateEmail(    @$_POST['create-user-email'] );
        $pass   = new ValidatePassword( @$_POST['create-user-pass']  );
        $user   = new ValidateUser(     @$_POST['create-user-name']  );
        $date   = new ValidateDate (    @$_POST['create-user-date']  );                
        

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
        
            return 2;
        }
    
     

        // Beállitásra kerül a jogosultség.
        $privilege  = $_POST['create-user-name'] == 'Admin' ? 3 : 1;

               
        //$this->db->StartTransaction();
        //és a userEntity userRegistry függvényén keresztül beírásra kerül az adatbázisba az új felhasználó.
        $result = $this->db->userRegistry( 
            [            
                ':email'        => trim( $email->getEmail() ),
                ':userName'     => trim( $user->getUser() ),
                ':password'     => md5( 'salt'.md5( trim( $pass->getPass() ) ) ),
                ':dateOfBirth'  => trim( $date->getDate() ),
                ':privilege'    => $privilege                
            ]
        );

        // Sikertelen registry esetén hiba üzenet és vissza a signUpView-ra
        if ( !$result )
        {            
            //$this->db->Rollback();

            $this->datas['errorMessage'] = 'Email is alredy exists!';
            $this->datas['userEmailValue'] = null;

            $this->setValues( $user, $email, $pass, $date);
            $this->FormAction();        

            return 3;
        }   
        

        // Konverter osztály paraméterei értékének megállapítása.
        //$image      = @$_FILES['create-user-file']['tmp_name']  ? $_FILES['create-user-file']['tmp_name'] : APPLICATION.'Images/user_blue.png';
        //$mime       = @$_FILES['create-user-file']['type']      ? $_FILES['create-user-file']['type'] : 'image/png';

        // Példányositásra kerül a konverter és a DB osztály.
        /*
        try
        {
            $converter  = new ImageConverter( $image, $mime );        

            if ($this->db->InsertImageFromSignUp($converter->cmpBin))
                $this->db->Rollback;
        }        
        catch (InvalidArgumentException $e)
        {
            $this->db->Commit();
        }
         
        */
        header("Location: ".APPROOT);

        return 0;
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


        $this->datas['enableNameInput'] = $this->datas['userNameValue'] === 'Admin' ? 'readonly' : ''; 


    }
    
                 
}