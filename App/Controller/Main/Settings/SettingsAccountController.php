<?php


namespace App\Controller\Main\Settings;

use App\Core\BaseController;
use App\Model\SettingsBar;
use App\Classes\ImageConverter;
use App\DB\DB;
use App\Model\Sessions;
use App\Model\Seria;
use App\Model\Navbar;
use App\Model\Indicator;
use App\Model\Header;
use App\Model\User;
use App\Model\ViewParameters;
use App\Classes\ValidateAbout;
use App\Classes\ValidateEmail;
use App\Classes\ValidateUser;
use App\Classes\ValidatePassword;
use App\Classes\ValidateSex;
use LogicException;



class SettingsAccountController extends BaseController
{
    
    public function __construct()
    {
        parent::__construct();       
        $this->getEntitys();  
        // Átadásra kerül a frontend felé, hogy melyik almenű aktív.
        $this->datas['settingsBar'] = new SettingsBar('personalItem', User::$id);               
    }



    /**
     * Appear the personal settings form.
     */
    public function index()
    {
        $this->setPersonalValues(); 
        $this->Response( 
            $this->datas, 
            new ViewParameters(
                'settings',
                'text/html',
                '', 
                'Settings',
                'Personal settings',
                "",
                'personal'              
            )
        );
    }




    public function personalUpdate()
    {                
        $user  = new ValidateUser(  $_POST['update-user-name'], User::$isAdmin);
        $email = new ValidateEmail( $_POST['update-user-email']);
        $about = new ValidateAbout( $_POST['update-user-about']);
        $sex   = new ValidateSex(   $_POST['update-user-sex']);
                        
        if ( !($email->isValid() && $user->isValid() && $sex->isValid() && $about->isValid())) {
            $this->setPersonalValues($user, $email, $sex, NULL, NULL, $about);
            $this->Response(
                $this->datas, new ViewParameters(
                    'settings',
                    'text/html',
                    'main',
                    'Settings',
                    'N-back settings',
                    "",
                    "personal",)          
            );
		}

        $sqlParams = [
            ':userId'   => User::$id,
            ':userName' => $user->getUser(),
            ':email'    => $email->getEmail(),
            ':about'    => $about->getText(),            
            ':sex'      => $sex->getSex(),
            ':privilege' => User::$privilege
        ];

        try 
        {
            DB::execute('CALL `UpdateUserPersonalDatas`(
                :userId,
                :userName,
                :email,
                :about,            
                :sex,
                :privilege
            )', $sqlParams);
            
            $this->Response([], new ViewParameters("redirect:".APPROOT."/settings?sm=Profile updated successfully!"));    
        }            
        catch (LogicException $l){
            $this->setPersonalValues($user, $email, $sex);
            $this->Response(
                $this->datas, new ViewParameters(
                    'settings',
                    'text/html',
                    'main',
                    'Settings',
                    'N-back settings', 
                    'Cant\'t update your personal datas, but maybe the email addres already used.',
                    'personal',
                    'active'
                )                                                                
            );
        }       
    }
    

    public function validatePass($datas) 
    {     

        $oldPass     = new ValidatePassword($datas['update-user-old-password']);
        $pass        = new ValidatePassword($datas['update-user-password']);
        $retypePass  = new ValidatePassword($datas['update-user-retype-password']);        
        $errorMsg    = '';

        
        // A két új jelszó összehasonlítása, különben hiba!
        if ($pass->getPass() !== $retypePass->getPass()) 
            $errorMsg = 'Password and re-typed password are different';
        // Ha egyezik, megfelelő-e
        elseif (!$pass->isValid()) {
            $errorMsg = $pass->errorMsg;
        // Ha megfelelő, megpróbáljuk beinzertálni
        } elseif($oldPass->isValid()) {
            
            $updateResult = DB::select(
                'CALL `upgradePassword`(:userId, :newPassword,:oldPassword)',
                [
                    ':userId'       => User::$id,
                    ':newPassword'  => $pass->getPass(),
                    ':oldPassword'  => $oldPass->getPass()
                ]
            );      
         
            // Ha nem volt sikeres a módosítás, vagyis helytelen a régi
            // jelszó, hiba!
            if ($updateResult->result !== 'true')
                $errorMsg = "Old password is incorrect!"; 
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
     * 
     * 
     */
    public function passwordUpdate()
    {
        extract($this->validatePass($_POST));
        // Ha hiba nélkül visszatér a validátor, menjen az átirányítás.  
        if($errorMsg === '')        
            $this->Response([], new ViewParameters("redirect:".APPROOT."/settings?sm=Password modification is succesfull!")                );
        else {
            // Különben állítsa be a szükséges adatokat...
            $this->setPersonalValues(null, null, null, $pass, $oldPass);         
            // ... és hívja meg magát újra.
            $this->Response(
                $this->datas, new ViewParameters(
                    'settings',
                    "",                    
                    "main", 
                    'Settings',
                    'Personal settings',
                    $errorMsg,
                    'personal', 
                    'active',)
                );    
        }        
    }




    /**
     * Képfeltöltést kezelő action függvény. XxlMttpResponse objektummal van mehívva.
     * Json objektumot küld vissza a frontendre.
     * 
     * 2020.10.20.
     */
    function imageUpdate()
    {        
        // Lekérjük a képet a cgi változóból.
        $img = (object)$_FILES['image'];

        // Példányosítjuk az ImageConverter osztályt, ami elvégzi a szükséges tranzformálást.
        $converter = new ImageConverter($img->tmp_name, $img->type );

        // A módosító SQL script
        $sql = "UPDATE `images` SET `imgBin` = :cmpBin, `update` = CURRENT_TIMESTAMP WHERE `userID` = :userId";

        // Az ősosztályból kapott db objektummal végrehajtjuk a módosítást.
        try
        {
            DB::execute($sql, [
                ':cmpBin' => $converter->cmpBin,
                ':userId' => User::$id
            ]);

            // Az adatbázis művelet lementett eredményét visszaküldjük                            
            // a frontendnek.
            $this->Response(["uploadResult" => 1], new ViewParameters("", "application/json"));        
        }
        catch (LogicException $e)
        {
            $this->Response(["uploadResult" => 0], new ViewParameters("", "application/json"));        
        }
    }



    
     /**
     * A formban megjelenítendő adatok előállítását végző függvény
     */
    private function setPersonalValues ( 
        ?ValidateUser        $user       = null, 
        ?ValidateEmail       $email      = null, 
        ?ValidateSex         $sex        = null, 
        ?ValidatePassword    $pass       = null, 
        ?ValidatePassword    $oldPass    = null,
        ?ValidateAbout       $about    = null )
    {     
        $this->datas['nameLabel']       = $user->errorMsg  ?? 'Your Name';
        $this->datas['emailLabel']      = $email->errorMsg ?? 'Email address';
        $this->datas['passwordLabel']   = $pass->errorMsg  ?? 'New password';
        $this->datas['oldPasswordLabel']= $oldPass->errorMsg  ?? 'Old password';
        $this->datas['sexLabel']        = $sex->errorMsg   ?? 'Your sex';
        $this->datas['privilegeLabel']  = 'Privilege';        
        $this->datas['aboutLabel']      = $about->errorMsg ?? 'About you';
        $this->datas['isAdmin']         = User::$isAdmin;           
        $this->datas['userEmailValue']  = is_object($email) ? $email->getEmail() : null;           

        if ($this->datas['isAdmin']) {
            $this->datas['userNameValue']   = 'Admin';            
            $this->datas['enableNameInput'] = 'readonly'; 
            $this->datas['nameLabel']       = 'Can\'t modify admin\'s username';
        }
        else {
            $this->datas['userNameValue']   = is_object($user) ? $user->getUser() : null;
            $this->datas['enableNameInput'] = ''; 
        }        
    }




    private function getEntitys()
    {
        $this->datas = [ 
            'seria' => (new Seria( User::$id ))->seria, 
            'user'  => "App\Model\User",            
            'navbar'=> ( new Navbar() )->getDatas(),
            'indicator' => (
                Indicator::getInstance(
                    new Sessions( User::$id, 1 ),
                    User::$gameMode 
                )
            )->getDatas(),
            'header' => (new Header())->getDatas()
        ];       
    }   
}