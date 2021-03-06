<?php


namespace App\Controller\Main\Settings;


use App\Model\SettingsBar;
use App\Classes\ImageConverter;
use App\Model\Seria;
use App\Model\Navbar;
use App\Model\Header;
use App\Model\ViewParameters;
use App\Classes\ValidateAbout;
use App\Classes\ValidateEmail;
use App\Classes\ValidateUser;
use App\Classes\ValidatePassword;
use App\Classes\ValidateSex;
use App\Controller\Main\MainController;
use App\Services\User;
use App\Services\DB;
use LogicException;



class SettingsAccountController extends MainController
{


    protected ?ValidatePassword $newPassV = NULL;
    protected ?ValidatePassword $oldPassV = NULL;
    protected ?ValidateUser $userV = NULL;
    protected ?ValidateEmail $emailV = NULL; 
    protected ?ValidateSex $sexV = NULL;
    protected ?ValidateAbout $aboutV = NULL;

    protected string $errorMsg = "";


    public function __construct()
    {
        $this->setDatas();  
        // Átadásra kerül a frontend felé, hogy melyik almenű aktív.
        $this->put("settingsBar", new SettingsBar('personalItem', User::$id));
    }



    /**
     * Appear the personal settings form.
     */
    public function index()
    {
        $this->setPersonalValues(); 
        $this->Response(
            new ViewParameters(
                'settings',
                'text/html',
                '', 
                'settings',
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
                new ViewParameters(
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
            
            $this->Response(new ViewParameters("redirect:".APPROOT."/settings?sm=Profile updated successfully!"));    
        }            
        catch (LogicException $l){
            $this->setPersonalValues($user, $email, $sex);
            $this->Response(
                new ViewParameters(
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

    /**
     * A jelszómódostást kezelő függvény. A validatePass függvény
     * alapján dönti el, hogy milyen adatokat küld tovább és melyik
     * nézetnek.
     * 
     */
    public function passwordUpdate()
    {                
        // Ha hiba nélkül visszatér a validátor, menjen az átirányítás. 
        
        $oldPass = $_POST['update-user-old-password'] ?? NULL;
        $newPass = $_POST['update-user-password'] ?? NULL;
        $rePass  = $_POST['update-user-retype-password'] ?? NULL;        
        
        if($this->validatePass($oldPass, $newPass, $rePass)) {
 
            $updateResult = DB::select(
                'CALL `upgradePassword`(:userId, :newPassword,:oldPassword)',
                [
                    ':userId'       => User::$id,
                    ':newPassword'  => $this->newPassV->getPass(),
                    ':oldPassword'  => $this->oldPassV->getPass()
                ]
            );      
        
            // Ha nem volt sikeres a módosítás, vagyis helytelen a régi
            // jelszó, hiba!
            if ($updateResult->result !== 'true')
                $this->errorMsg = "Old password is incorrect!"; 

            if ($this->errorMsg === "")
                return $this->Response(new ViewParameters("redirect:".APPROOT."/settings?sm=Password modification is succesfull!"));
            
        }                

        // Különben állítsa be a szükséges adatokat...
        $this->setPersonalValues();         
        // ... és hívja meg magát újra.
        $this->Response(
            new ViewParameters(
                'settings',
                "",                    
                "main", 
                'Settings',
                'Personal settings',
                $this->errorMsg,
                'personal', 
                'active',)
            );        
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
        $converter->compress(250, 250);
        // A módosító SQL script
        $sql = "UPDATE `images` SET `bin` = :bin, `update` = CURRENT_TIMESTAMP WHERE `userID` = :userId";
        // Az ősosztályból kapott db objektummal végrehajtjuk a módosítást.
        try{
            DB::execute($sql, [
                ':bin' => $converter->bin,
                ':userId' => User::$id
            ]);
            // Siker esetén egy igaz értéket küldünk vissza jsonban. amivel kezdhet valamit.
            $this->put("uploadResult", true);
            $this->Response(new ViewParameters("", "application/json"));        
        } catch (LogicException $e) {
            $this->put("uploadResult", false);
            $this->Response(new ViewParameters("", "application/json"));        
        }
    }
    
     /**
     * A formban megjelenítendő adatok előállítását végző függvény
     */
    private function setPersonalValues ()
    {     
        $this->put("nameLabel",         $this->userV->errorMsg  ?? "Your Name");
        $this->put("emailLabel",        $this->emailV->errorMsg ?? "Email address");
        $this->put("passwordLabel",     $this->newPassV->errorMsg ?? "New password");
        $this->put("oldPasswordLabel",  $this->newPassV->errorMsg ?? "Old password");
        $this->put("sexLabel",          $this->sexV->errorMsg ?? "Your sex");
        $this->put("privilegeLabel",    "Privilege");
        $this->put("aboutLabel",        $this->aboutV->errorMsg ?? "About you");
        $this->put("isAdmin",           User::$isAdmin);
        $this->put("userEmailValue",    is_object($this->emailV) ? $this->emailV->getEmail() : NULL);

        if ($this->get("isAdmin")) {
            $this->put("userNameValue", "Admin");
            $this->put("enableNameInput","readonly"); 
            $this->put("nameLabel",     "Can't modify admin's username");
        }
        else {
            $this->put("userNameValue",  is_object($this->userV) ? $this->userV->getUser() : NULL);
            $this->put("enableNameInput", ""); 
        }        
    }


    public function validatePass(string $oldPass, string $newPass, string $retypedPass) 
    {            
        $this->oldPassV = new ValidatePassword($oldPass);
        $this->newPassV = new ValidatePassword($newPass);
        $retypePass  = new ValidatePassword($retypedPass);
        
        // A két új jelszó összehasonlítása, különben hiba!
        if ($this->newPassV->getPass() !== $retypePass->getPass()) {
            $this->errorMsg = 'Password and re-typed password are different';            
            return false;
        }
        // Ha egyezik, megfelelő-e
        elseif (!$this->newPassV->isValid()) {
            $this->errorMsg = $this->newPassV->errorMsg;
            return false;
        // Ha megfelelő, megpróbáljuk beinzertálni
        } elseif(!$this->oldPassV->isValid()) {
            $this->errorMsg = $this->oldPassV->errorMsg;
            return false;
        }
        return true;        
    } 
}
