<?php

use Classes\ImageConverter;
use DB\EntityGateway;



class signUpController extends MainController
{

    
    function __construct($matches)
    {                 
        $this->db  = EntityGateway::GetInstance();

        parent::__construct();
        $this->SetDatas();        
                       

        $action = $matches['action'].'Action';            
        $this->$action();        
    }



    function formAction()
    {     
        $this->setValues();                
        
        $this->Response( $this->datas, [ 'view' => 'signUp', 'module' => 'User'] );
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
        $user   = new ValidateUser(     @$_POST['create-user-name'], @$_SESSION['adminAuthenticate'] );
        $date   = new ValidateDate (    @$_POST['create-user-date']  );  
        $sex    = $_POST['sex'];
        
        
        //Ha valamelyik adat nem felel meg a mintának
        if ( $email->errorMsg || $pass->errorMsg || $user->errorMsg || $date->errorMsg )
        {      
            $this->setValues( $user, $email, $pass, $date);

            $this->Response( 
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
        
         // Konverter osztály paraméterei értékének megállapítása.
        if ($sex == 'male')
        {
            $image = APPLICATION.'Images/userMale.jpg';            
        }
        else
        {
            $image = APPLICATION.'Images/userFemale.jpg';
        }

        $mime  ='image/jpg';
 
        // Példányositásra kerül a konverter és a DB osztály.
         
        $converter  = new ImageConverter( $image, $mime );
                 

        //$this->db->StartTransaction();
        //és a userEntity userRegistry függvényén keresztül beírásra kerül az adatbázisba az új felhasználó.
        $result = $this->db->userRegistry( 
            [            
                ':email'            => trim( $email->getEmail() ),
                ':userName'         => trim( $user->getUser() ),
                ':password'         => md5( 'salt'.md5( trim( $pass->getPass() ) ) ),
                ':dateOfBirth'      => trim( $date->getDate() ),
                ':sex'              => $sex,
                ':privilege'        => $privilege,
                ':passwordLength'   => strlen($pass->getPass()),
                ':cmpBin'           => $converter->cmpBin
            ]
        );
    
        // Sikertelen registry esetén hiba üzenet és vissza a signUpView-ra
        if ( !$result )
        {            
            //$this->db->Rollback();

            $this->datas['errorMessage'] = 'Email is alredy exists!';
            $this->datas['userEmailValue'] = null;

            $this->setValues( $user, $email, $pass, $date);
            $this->Response( $this->datas, [ 'view' => 'signUp', 'module' => 'User'] );       

            return 3;
        }                       
        
        header("Location: ".APPROOT.'/');

        return 0;
    }







    /**
     * A formban megjelenítendő adatok előállítását végző függvény
     */
    private function setValues( $user = null, $email = null, $pass = null, $date = null)
    {     

        $crName = $user     ? $user->getUser()      : null;
        $crEmail= $email    ? $email->getEmail()    : null;

        $this->datas['signUpJsPath']    = BACKSTEP.'Public/js/signUp.js?v='.CURRENT_TIMESTAMP;

        $this->datas['nameLabel']       = $user->errorMsg  ?? 'Name';
        $this->datas['emailLabel']      = $email->errorMsg ?? 'E-mail';
        $this->datas['dateLabel']       = $date->errorMsg  ?? 'Date of birth';
        $this->datas['passwordLabel']   = $pass->errorMsg  ?? 'Password';
        $this->datas['privilegeLabel']  = 'Privilege';
        
        $this->datas['isAdmin']         = $this->user->getUsersCount()->num <= 1;
        $this->datas['errorMessage']    = null;

        $this->datas['userNameValue']   = $this->datas['isAdmin']  ?  'Admin' : $crName;
        $this->datas['userEmailValue']  = $crEmail;
     

        if ($this->datas['isAdmin'])
        {
            // autentikációs azonosító létrehozása az Admin név ismétlődésének elkerüléséhez
            $_SESSION['adminAuthenticate'] = 1;
        }
        else if (@$_SESSION['adminAuthenticate'])
        {
            unset($_SESSION['adminAuthenticate']);
        } 

        $this->datas['enableNameInput'] = @$_SESSION['adminAuthenticate'] ? 'readonly' : ''; 


    }
    
                 
}