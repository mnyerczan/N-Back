<?php

use DB\DB;
use Model\Sessions;

class SettingsAccountController extends BaseController
{


    public function __construct()
    {
        parent::__construct();

        $this->db  = DB::GetInstance();        
        $this->getEntitys();  
               
    }

    /**
     * Appear the personal settings form.
     */
    public function personal()
    {
        $this->setPersonalValues();
       

        $this->Response( 
            $this->datas, [
            'personal'  => 'active',
            'item'      => 'personal',
            'view'      => 'settings',
            'layout'    => 'Main', 
            'mime'      => 'text/html',
            'module'    => 'Settings',
            "title"     => 'Personal settings',            
            ]  
        );
    }


    public function personalUpdate()
    {   
             
        $user  = new ValidateUser(  $_POST['update-user-name'], $this->user->isAdmin);
        $email = new ValidateEmail( $_POST['update-user-email']);
        $about = new ValidateAbout( $_POST['update-user-about']);
        $sex   = new ValidateSex(   $_POST['update-user-sex']);
        
                
        if ( !($email->isValid() && $user->isValid() && $sex->isValid()))
		{			            
            $this->setPersonalValues($user, $email, $sex);

            $this->Response(
                $this->datas, 
                [
                    'succMsg'   =>  'Your personal data are updated!',
                    'personal'  => 'active',
                    'item'      => 'personal',
                    'view'      => 'settings', 
                    'module'    => 'Settings',
                    'mime'      => 'text/html',
                    'layout'    => 'Main',
                    "title"     => 'N-back settings',            
                ]  
            );
		}

        $sqlParams = [
            ':userId'   => $this->user->id,
            ':userName' => $user->getUser(),
            ':email'    => $email->getEmail(),
            ':about'    => $about->getText(),            
            ':sex'      => $sex->getSex(),
            ':privilege' => $this->user->privilege
        ];


        $result = $this->db->Execute('CALL `UpdateUserPersonalDatas`(
            :userId,
            :userName,
            :email,
            :about,            
            :sex,
            :privilege
        )', $sqlParams);



        if ($result)
        {

            $this->Response([],['view' => "redirect:".APPROOT."settings?sm=Profile updated successfully!"]);
        }	

        else
        {

            $this->setPersonalValues($user, $email, $sex);

            $this->Response(
                $this->datas, 
                [
                '$errorMsg' => 'Cant\'t update your personal datas, but maybe the email addres already used.',  
                'personal'  => 'active',
                'item'      => 'personal',
                'view'      => 'settings',
                'mime'      => 'text/html',
                'layout'    => 'Main', 
                'module'    => 'Settings',
                "title"     => 'N-back settings',            
                ]  
            );
        }       
    }


    

    public function validatePass( $datas ) 
    {     

        $oldPass     = new ValidatePassword($datas['update-user-old-password']);
        $pass        = new ValidatePassword($datas['update-user-password']);
        $retypePass  = new ValidatePassword($datas['update-user-retype-password']);        
        $errorMsg    = '';

        
        // A két új jelszó összehasonlítása, különben hiba!
        if ($pass->getPass() !== $retypePass->getPass()) 
        {

            $errorMsg = 'Password and re-typed password are different';
        }
        
        // Ha egyezik, megfelelő-e
        elseif($pass->isValid())
        {


           $updateResult = $this->db->Select(
                'CALL `upgradePassword`(:userId, :newPassword,:oldPassword)',
                [
                    ':userId'       => $this->user->id,
                    ':newPassword'   => $pass->getPass(),
                    ':oldPassword'  => $oldPass->getPass()
                ]
            );      
         

            // Ha nem volt sikeres a módosítás, vagyis helytelen a régi
            // jelszó, hiba!
            if ($updateResult[0]->result !== 'true')
            {                                
                $errorMsg = "Old password is incorrect!";   
            }
        }

        return [
            'errorMsg'  => $errorMsg,
            'oldPass'   => $oldPass,
            'pass'      => $pass
        ];
    }




    /**
     * A jelszómódostást kezelő függvény. A validatePass függvény
     * alapján dönti el, hogy milyen adatokat küld tovább és melyik
     * nézetnek.
     * 
     */
    public function passwordUpdate()
    {

        extract($this->validatePass($_POST));



        // Ha hiba nélkül visszatér a validátor, menjen az átirányítás.  
        if($errorMsg === '')
        {
            $this->Response([],[
                'view' => "redirect:".APPROOT.'/'."settings?sm=Password modification is succesfull!"
            ]);
        }
        else
        {
            // Különben állítsa be a szükséges adatokat...
            $this->setPersonalValues(null, null, null, $pass, $oldPass);       
                

            // ... és hívja meg magát újra.
            $this->Response(
            $this->datas, 
                [
                    'view'      => 'settings',
                    'errorMsg'  => $errorMsg,
                    'personal'  => 'active',
                    'item'      => 'personal',                
                    'layout'    => 'Main', 
                    'module'    => 'Settings',
                    "title"     => 'Personal settings',            
                ]  
            );    
        }        
    }

    
     /**
     * A formban megjelenítendő adatok előállítását végző függvény
     */
    private function setPersonalValues ( 
        ValidateUser        $user = null, 
        ValidateEmail       $email = null, 
                            $sex = null, 
        ValidatePassword    $pass = null, 
        ValidatePassword    $oldPass = null )
    {     

        $crName = $user     ? $user->getUser()      : null;
        $crEmail= $email    ? $email->getEmail()    : null;


        $this->datas['nameLabel']       = $user->errorMsg  ?? 'Your Name';
        $this->datas['emailLabel']      = $email->errorMsg ?? 'Email address';
        $this->datas['passwordLabel']   = $pass->errorMsg  ?? 'New password';
        $this->datas['oldPasswordLabel']= $oldPass->errorMsg  ?? 'Old password';
        $this->datas['sexLabel']        = $sex->errorMsg   ?? 'Your sex';
        $this->datas['privilegeLabel']  = 'Privilege';
        
        $this->datas['isAdmin']         = $this->user->isAdmin;    
       
        $this->datas['userEmailValue']  = $crEmail;
           

        if ($this->datas['isAdmin'])
        {
            $this->datas['userNameValue']   = 'Admin';            
            $this->datas['enableNameInput'] = 'readonly'; 
            $this->datas['nameLabel']       = 'Can\'t modify admin\'s username';
        }
        else
        {
            $this->datas['userNameValue']   = $crName;
            $this->datas['enableNameInput'] = ''; 
        }        


    }

    private function getEntitys()
    {
        $this->datas = [ 
            'seria' => (new Seria( $this->user->id ))->seria, 
            'user'  => $this->user,            
            'navbar'=> ( new Navbar( $this->user ) )->getDatas(),
            'indicator' => (
                Indicator::getInstance(
                    new Sessions( $this->user->id, 1 ),
                    $this->user->gameMode 
                )
            )->getDatas(),
            'header' => (new Header( $this->user ))->getDatas()
        ];       
    }
   
}