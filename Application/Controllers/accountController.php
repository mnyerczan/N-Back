<?php

use Login\UserEntity;



class accountController extends MainController
{    

    function __construct($matches)
    {        
        parent::__construct();

        $this->setDatas();

        if (array_key_exists('action',$matches))
        {
            $action = $matches['action'].'Action';
            $this->$action();
        }            
        else
        {
            $this->Action();
        }            
    }


    private function Action()
    {              
        $this->Response( 
            $this->datas, [
            'view'      => 'profile', 
            'module'    => 'User',
            "title"     => 'User',            
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
			var_dump($email->errorMsg);
			var_dump($email->getEmail());
			var_dump($user->errorMsg);
			var_dump($user->getUser());
			var_dump($pass->errorMsg);
			var_dump($pass->getPass());
			var_dump($date->errorMsg);
            var_dump($date->getDate());
            
            $this->setValues($user, $email, $pass, $date);
		}


        $result = $this->user->UpdateUser($date->getDate(),$user->getUser(), $email->getEmail(), $pass->getPass());

		var_dump($_POST);
       
    }

    private function personalFormAction()
    {
        $this->setValues();

        $this->Response( 
            $this->datas, [
            'view'      => 'personal', 
            'module'    => 'User',
            "title"     => 'User',            
            ]  
        );
    }




     /**
     * A formban megjelenítendő adatok előállítását végző függvény
     */
    private function setValues( $user = null, $email = null, $pass = null, $date = null)
    {     

        $crName = $user     ? $user->getUser()      : null;
        $crEmail= $email    ? $email->getEmail()    : null;


        $this->datas['nameLabel']       = $user->errorMsg  ?? 'Your Name';
        $this->datas['emailLabel']      = $email->errorMsg ?? 'Email address';
        $this->datas['dateLabel']       = $date->errorMsg  ?? 'Date of birth';
        $this->datas['passwordLabel']   = $pass->errorMsg  ?? 'Password';
        $this->datas['privilegeLabel']  = 'Privilege';
        
        $this->datas['isAdmin']         = $this->user->isAdmin;    

        $this->datas['userNameValue']   = $this->datas['isAdmin']  ?  'Admin' : $crName;
        $this->datas['userEmailValue']  = $crEmail;
           

        $this->datas['enableNameInput'] = @$_SESSION['isAdmin'] ? 'readonly' : ''; 


    }
   

}