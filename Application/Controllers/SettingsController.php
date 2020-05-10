<?php

use DB\EntityGateway;

class SettingsController extends MainController

{

    public function __construct($matches)
    {
        $this->db  = EntityGateway::GetInstance();

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
            "title"     => 'N-back settings',            
            ]  
        );
    }

    private function personalUpdateAction()
    {   
        $date  = new ValidateDate($_POST['update-user-birth']);
        $user  = new ValidateUser($_POST['update-user-name'], $this->user->isAdmin);
        $email = new ValidateEmail($_POST['update-user-email']);
        $pass  = new ValidatePassword($_POST['update-user-password']);
        
        if ( !($email->isValid() && $user->isValid() && $pass->isValid() && $date->isValid()))
		{			            
            $this->setValues($user, $email, $pass, $date);

            $this->Response(
                $this->datas, 
                [
                'personal'  => 'active',
                'item'      => 'personal',
                'view'      => 'settings', 
                'module'    => 'Settings',
                "title"     => 'N-back settings',            
                ]  
            );
		}


        $result = $this->user->UpdateUser(
            $date->getDate(),
            $user->getUser(), 
            $email->getEmail(), 
            $pass->getPass()
        );

        if ($result)
        {
            $this->Response(['result' => true], ['view' => "redirect:account"]);
        }		
        else
        {
            $this->setValues($user, $email, $pass, $date);

            $this->Response(
                $this->datas, 
                [
                'modifyResult'  => false,                
                'personal'      => 'active',
                'item'          => 'personal',
                'view'          => 'settings', 
                'module'        => 'Settings',
                "title"         => 'N-back settings',            
                ]  
            );
        }
       
    }


     /**
     * A formban megjelenítendő adatok előállítását végző függvény
     */
    private function setValues( ValidateUser $user = null, ValidateEmail $email = null, ValidatePassword $pass = null, ValidateDate $date = null)
    {     

        $crName = $user     ? $user->getUser()      : null;
        $crEmail= $email    ? $email->getEmail()    : null;


        $this->datas['nameLabel']       = $user->errorMsg  ?? 'Your Name';
        $this->datas['emailLabel']      = $email->errorMsg ?? 'Email address';
        $this->datas['dateLabel']       = $date->errorMsg  ?? 'Date of birth';
        $this->datas['passwordLabel']   = $pass->errorMsg  ?? 'Password';
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