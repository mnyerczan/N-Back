<?php

namespace App\Controller;

use App\Core\MainController;
use App\Model\ViewParameters;
use App\Classes\ValidateEmail;
use App\Classes\ValidateUser;
use App\Classes\ValidatePassword;
use App\Classes\ValidateDate;
use App\Classes\ValidateSex;
use App\Classes\ImageConverter;
use App\DB\DB;



class SignUpController extends MainController
{

    
    function __construct($matches)
    {                 
        $this->db  = DB::GetInstance();

        parent::__construct();
        $this->SetDatas();                                     
    }



    function index()
    {     
        $this->setValues();                
        
        $this->Response( 
            $this->datas, 
            new ViewParameters(
                "signUp", 
                "text/html", 
                "Main", 
                "User")            
        );
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
    public function submit()
    {                  
 
        $email  = new ValidateEmail(    $_POST['create-user-email'] );
        $pass   = new ValidatePassword( $_POST['create-user-pass']  );
        $user   = new ValidateUser(     $_POST['create-user-name'], @$_SESSION['adminAuthenticate'] );
        $date   = new ValidateDate (    $_POST['create-user-date']  );  
        $sex    = new ValidateSex(      $_POST['sex']);
  
    
        //Ha valamelyik adat nem felel meg a mintának
        if (    $email->errorMsg || 
                $pass->errorMsg  || 
                $user->errorMsg  || 
                $date->errorMsg  ||
                $sex->errorMsg )                
        {      
            $this->setValues( $user, $email, $pass, $date);

            $this->Response( 
                $this->datas,
                new ViewParameters(
                    "sugnUp", 
                    "text/html",
                    "Main",
                    "User",
                    "Bad parameters",
                    "Patameters are invalid")
                );            
            return 2;
        }         

        // Beállitásra kerül a jogosultság. Ha az adminform lett kitöltve, létezik privilege mező,
        // különben a felhasználónév alapján dől el a jogosultság.
        // Kezdetben az adminé alapértelmezetten 3, mindenki másé 1.
        if (isset($_POST['cu-privilege']))
        {
            $Privilege = $_POST['cu-privilege'] < 4 && $_POST['cu-privilege'] > 0 ? $_POST['cu-privilege'] : 1;
        }    
        else
        {
            $privilege = $_POST['create-user-name'] == 'Admin' ? 3 : 1;
        }            
        


         // Konverter osztálynak átadandó pathok. Alapértelmezett avatar képek.
        if ($sex == 'male')
        {
            $image = APPLICATION.'Images/userMale.jpg';            
        }
        else
        {
            $image = APPLICATION.'Images/userFemale.jpg';
        }         



        // Példányositásra kerül a konverter és a DB osztály. Az alapértelmezett képek
        // Mime típusa image/jpg, amit később megváltoztathatnak.
        $converter  = new ImageConverter( $image, 'image/jpg' );
        
        
       
        //és a userEntity userRegistry függvényén keresztül beírásra kerül az adatbázisba az új felhasználó.                ;    
        // Sikertelen registry esetén hiba üzenet és vissza a signUpView-ra
        if ( !$this->user->userRegistry(
            $email,
            $user,
            $pass,
            $date,
            $sex,
            $privilege,
            $converter) )
        {                        

            $this->datas['errorMessage'] = 'Email is alredy exists!';
            $this->datas['userEmailValue'] = null;

            // Ha valamelyik adat nem helyes, arról a setValues függvény értesítést tesz a $this->datas
            // változóba, és megkapja a frontend.
            $this->setValues( $user, $email, $pass, $date);
            
            $this->Response( 
                $this->datas, 
                new ViewParameters(
                    "signUp",
                    "text/html",
                    "Main",
                    "User",
                    "Create new account",
                    "Parameters are invalid")
             );       

            return 3;
        }                       
        
        $this->Response([], 
            new ViewParameters(
                "redirect:".APPROOT."/?sm=New account is succesfully!!")
            );

        return 0;
    }







    /**
     * A formban megjelenítendő adatok előállítását végző függvény
     */
    private function setValues( $user = null, $email = null, $pass = null, $date = null)
    {     

        $crName = $user     ? $user->getUser()      : null;
        $crEmail= $email    ? $email->getEmail()    : null;

        $this->datas['signUpJsPath']    = BACKSTEP.'Public/js/signUp.js?v='.RELOAD_INDICATOR;

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