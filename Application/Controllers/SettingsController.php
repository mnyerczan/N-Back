<?php

use DB\DB;

class SettingsController extends MainController

{

    public function __construct($matches)
    {
        $this->db  = DB::GetInstance();

        parent::__construct();
        $this->SetDatas(); 

        if (array_key_exists('action',$matches))
        {
            $action = $matches['action'].'Action';
            $this->$action();
        }  
        else
        {
            $this->personalAction();
        }               
    }


    private function personalAction()
    {
        $this->setValues();
       

        $this->Response( 
            $this->datas, [
            'personal'  => 'active',
            'item'      => 'personal',
            'view'      => 'settings',
            'layout'    => 'Main', 
            'module'    => 'Settings',
            "title"     => 'Personal settings',            
            ]  
        );
    }

    private function nbackAction()
    {
        $this->setValues();

        $this->Response( 
            $this->datas, [
            'nback'     => 'active',
            'item'      => 'nback',
            'view'      => 'settings', 
            'module'    => 'Settings',
            'layout'    => 'Main',
            "title"     => 'N-back settings',            
            ]  
        );
    }

    private function passwordUpdateAction()
    {
        $oldPass     = new ValidatePassword($_POST['update-user-old-password']);
        $pass        = new ValidatePassword($_POST['update-user-password']);
        $retypePass  = new ValidatePassword($_POST['update-user-retype-password']);        
        $errorMsg    = '';

        if ($pass->isValid() && $retypePass->isValid() && $oldPass->isValid() && $pass->getPass() === $retypePass->getPass())
        {            

            $updateResult = $this->db->Select(
                'CALL `upgradePassword`(:userId, :newPassword,:oldPassword)',
                [
                    ':userId'       => $this->user->id,
                    ':newPassword'   => $pass->getPass(),
                    ':oldPassword'  => $oldPass->getPass()
                ]
            );      

            if ($updateResult[0]->result === 'true')
            {                                
                $this->Response([],['view' => "redirect:".APPROOT.'/'."settings?sm=Password modification is succesfull!"]);
            }

            $errorMsg = "Old password is incorrect!";
                        
        }
     
        $this->setValues(null, null, null, $pass, $oldPass);

        

        if ( !$errorMsg && $pass->getPass() !== $retypePass->getPass())
        {
            $errorMsg = 'Password and re-typed password are different';
        }           
        

        $this->Response(
            $this->datas, 
            [
                'errorMsg'  => $errorMsg,
                'personal'  => 'active',
                'item'      => 'personal',
                'view'      => 'settings',
                'layout'    => 'Main', 
                'module'    => 'Settings',
                "title"     => 'Personal settings',            
            ]  
        );    
    }





    private function personalUpdateAction()
    {   
             
        $user  = new ValidateUser(  $_POST['update-user-name'], $this->user->isAdmin);
        $email = new ValidateEmail( $_POST['update-user-email']);
        $about = new ValidateAbout( $_POST['update-user-about']);
        $sex   = new ValidateSex(   $_POST['update-user-sex']);
        
                
        if ( !($email->isValid() && $user->isValid() && $sex->isValid()))
		{			            
            $this->setValues($user, $email, $sex);

            $this->Response(
                $this->datas, 
                [
                    'succMsg'   =>  'Your personal data are updated!',
                    'personal'  => 'active',
                    'item'      => 'personal',
                    'view'      => 'settings', 
                    'module'    => 'Settings',
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
            $this->setValues($user, $email, $sex);

            $this->Response(
                $this->datas, 
                [
                '$errorMsg' => 'Cant\'t update your personal datas, but maybe the email addres already used.',  
                'personal'  => 'active',
                'item'      => 'personal',
                'view'      => 'settings',
                'layout'    => 'Main', 
                'module'    => 'Settings',
                "title"     => 'N-back settings',            
                ]  
            );
        }
       
    }


     /**
     * A formban megjelenítendő adatok előállítását végző függvény
     */
    private function setValues( ValidateUser $user = null, ValidateEmail $email = null, $sex = null, ValidatePassword $pass = null, ValidatePassword $oldPass = null)
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
   
}