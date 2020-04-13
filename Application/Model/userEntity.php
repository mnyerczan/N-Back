<?php
namespace Login;

use DB\EntityGateway;
use InvalidArgumentException;

/**
 * UserEntity, this is a singleton
 */
class UserEntity
{
	private static 
			$INSTANCE = NULL;
	private
			$loged,
			$object,	 		
			$datas = [];			


	public static function GetInstance(): object
    {
		if ( self::$INSTANCE == NULL )		
        {			
			session_start();

			self::$INSTANCE = new self();    
			
			// Csak akkor tölt be adatbázisból, ha van bejelentkezés.
			// Egyébként default + cookie, ha van.
			if (@$_SESSION['userId'])
				self::$INSTANCE->LoadUser( $_SESSION['userId'] );
			else
			self::$INSTANCE->LoadAnonim();
							
        }               
        return self::$INSTANCE;
	}
	
	public function getUsersCount()
	{		
		return $this->object->getUsersCount()[0];
	}


	private function __construct()
    {		
		$this->object = EntityGateway::GetInstance();		
	}
	private function LoadAnonim()
	{		
		$this->datas['id'] 				= 1;		
		$this->datas['userName'] 		= 'Anonim';
		$this->datas['email'] 			= NULL;
		$this->datas['loginDatetime']	= NULL;
		$this->datas['privilege'] 		= 0;
		$this->datas['birth'] 			= NULL;
		$this->datas['passwordLength'] 	= NULL;
		$this->datas['fileName'] 		= 'user_blue.png';
		$this->datas['theme'] 			= $_COOKIE['theme'] 		?? 'white';
		$this->datas['refresh'] 		= NULL;
		$this->datas['imgBin'] 			= NULL;

		// NOT IMPLEMENTED FEATUTRE
		$this->datas['online'] 			= NULL;
		//--------------------------------------------------------------------------------------------				

		if (!@$_COOKIE['gameMode']) 
		{
			setcookie('gameMode','Position');
			$this->datas['gameMode'] = 'Position';
		}			
		else
			$this->datas['gameMode'] 		= $_COOKIE['gameMode'];
		

		// Game level
		if (!@$_COOKIE['level'])
		{
			setcookie('level', 1);
			$this->datas['level'] = 1;
		}			
		else
			$this->datas['level'] 			= $_COOKIE['level'];
		

		// Tim between two event in seconds
		if (!@$_COOKIE['seconds'])
		{
			setcookie('seconds', 3);
			$this->datas['seconds'] = 3;
		}
		else
			$this->datas['seconds'] 		= $_COOKIE['seconds'];
		

		// Min trial has 25 events
		if (!@$_COOKIE['trials'])
		{
			setcookie('trials', 25);
			$this->datas['trials'] 	= 25;
		}
		else
			$this->datas['trials'] 			= $_COOKIE['trials'];
		

		// One event length in seconds 
		if (!@$_COOKIE['eventLength'])
		{
			setcookie('eventLength', 0.75);
			$this->datas['eventLength'] = 0.75;
		}
		else
			$this->datas['eventLength']		= $_COOKIE['eventLength'];
		

		// Event's color
		if (!@$_COOKIE['color'])
		{
			setcookie('color','Position');		
			$this->datas['color'] = 'blue';
		}
		else
			$this->datas['color'] 			= $_COOKIE['color'];

    }
	


	function LoadUser( int $userId ): string
    {	
		// Anonim felhasználóhoz nem tartoznak személyes adatok.
		if ($userId == 1) return false; 
		
		// return $user, $image
		extract($this->object->getUser( [ ':userId' => $userId ]  ));


		$this->datas['id'] 				= $user->id;
		$this->datas['email']			= Include_special_characters($user->email);
		$this->datas['loginDatetime']	= $user->loginDatetime;		
		$this->datas['userName']		= Include_special_characters($user->userName);
		$this->datas['privilege']		= $user->privilege;
		$this->datas['birth']			= $user->birth;
		$this->datas['passwordLength']	= $user->passwordLength;

		$this->datas['imgBin']			= $image->imgBin ?? null;		
		$this->datas['theme']			= $user->theme;
		$this->datas['refresh'] 		= $user->refresh;

		// NOT IMPLEMENTED FEATUTRE
		$this->datas['online'] 			= $user->online;

		//--------------------------------------------------------------------------------------------
		
		$this->datas['gameMode'] 		= $user->gameMode;
		$this->datas['level'] 			= $user->level;
		$this->datas['seconds']			= $user->seconds;
		$this->datas['trials'] 			= $user->trials;
		$this->datas['eventLength']		= $user->eventLength;
		$this->datas['color'] 			= $user->color;			
									

		return $this->loged = true;
	}






    function Login( string $email, string $password ): string
    {		
		$result = $this->object->getUser( [ ":email" => $email, ":password" => md5( "salt".md5( $password ) ) ]  );					
	

		if( is_object($result['user']))
		{		
			//$this->SetUser( $result[0] );			
			$this->SetSession( $result['user'] );	
	
			return $this->loged = true;
		}        		

		return $this->loged = false;
	
	}





	private function SetSession( $result )
	{
		$_SESSION['userId']		= $result->id;
		//$_SESSION['userName']	= Include_special_characters($result->['userName']);
		//$_SESSION['password']	= $result->['password'];
	}



    function __get($name)
    {
		
        switch($name)
        {
			case 'loged': 			return $this->loged; 					break;

			case 'id': 				return $this->datas['id']; 				break;			
			case 'userName': 		return $this->datas['userName']; 		break;
			case 'email': 			return $this->datas['email']; 			break;
			case 'loginDatetime': 	return $this->datas['loginDatetime']; 	break;
			case 'privilege': 		return $this->datas['privilege']; 		break;
			case 'birth': 			return $this->datas['birth']; 			break;
			case 'passwordLength': 	return $this->datas['passwordLength']; 	break;
			case 'fileName': 		return $this->datas['fileName']; 		break;
			case 'theme': 			return $this->datas['theme']; 			break;
			case 'refresh': 		return $this->datas['refresh']; 		break;
			case 'online': 			return $this->datas['online']; 			break;
			case 'imgBin': 			return $this->datas['imgBin']; 			break;

			case 'gameMode': 		return $this->datas['gameMode']; 		break;
			case 'level': 			return $this->datas['level']; 			break;			
			case 'seconds': 		return $this->datas['seconds']; 		break;
			case 'trials': 			return $this->datas['trials']; 			break;
			case 'eventLength': 	return $this->datas['eventLength']; 	break;
			case 'color': 			return $this->datas['color']; 			break;
        }
    }
}