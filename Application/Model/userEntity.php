<?php
namespace Login;

use DB\EntityGateway;

/**
 * UserEntity, this is a singleton
 */
class UserEntity
{
	private static 
			$INSTANCE = NULL;
	private
			$loged,
			$dbObject,	 		
			$datas = [];			


	public static function GetInstance(): object
    {
		if ( self::$INSTANCE == NULL )		
        {			
			session_start();

			self::$INSTANCE = new self();    

			self::$INSTANCE->Load( $_SESSION['userId'] ?? 1 );    		
						
        }               
        return self::$INSTANCE;
    }

	private function __construct()
    {
		$this->dbObject = EntityGateway::getDB();
		
		$this->datas['id'] 				= 1;		
		$this->datas['userName'] 		= 'Guest';
		$this->datas['email'] 			= NULL;
		$this->datas['loginDatetime']	= NULL;
		$this->datas['privilege'] 		= 0;
		$this->datas['birth'] 			= NULL;
		$this->datas['passwordLength'] 	= NULL;
		$this->datas['fileName'] 		= NULL;
		$this->datas['theme'] 			= $_COOKIE['theme'] 		?? 'white';
		$this->datas['refresh'] 		= NULL;

		// NOT IMPLEMENTED FEATUTRE
		$this->datas['online'] 			= NULL;
		//--------------------------------------------------------------------------------------------
		
		$this->datas['gameMode'] 		= $_COOKIE['gameMode'] 		?? 'Position';
		// Game level
		$this->datas['level'] 			= $_COOKIE['level'] 		?? 1;
		// Tim between two event in seconds
		$this->datas['seconds'] 		= $_COOKIE['seconds'] 		?? 3;
		// Min trial has 25 events
		$this->datas['trials'] 			= $_COOKIE['trials'] 		?? 25;
		// One event length in seconds 
		$this->datas['eventLength']		= $_COOKIE['eventLength'] 	?? 0.75;
		// Event's color
		$this->datas['color'] 			= $_COOKIE['color'] 		?? 'blue';
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

			case 'gameMode': 		return $this->datas['gameMode']; 		break;
			case 'level': 			return $this->datas['level']; 			break;			
			case 'seconds': 		return $this->datas['seconds']; 		break;
			case 'trials': 			return $this->datas['trials']; 			break;
			case 'eventLength': 	return $this->datas['eventLength']; 	break;
			case 'color': 			return $this->datas['color']; 			break;
        }
    }
    

	function Load( $userId ): string
    {			
		$result = $this->dbObject->getUser( [ ':userId' => $userId ]  );	
			

		if( 1 == count( $result ))
		{		
			$this->SetUser( $result[0] );			
	
			return $this->loged = true;
		}        		

		return $this->loged = false;
	}

    function Login( string $email, string $password ): string
    {		
		$result = $this->dbObject->getUser( [ ":email" => $email, ":password" => md5( "salt".md5( $password ) ) ]  );					

		if( 1 == count( $result ))
		{		
			//$this->SetUser( $result[0] );			
			$this->SetSession( $result[0] );	
	
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



	private function SetUser( $result )
	{		
		$this->datas['id'] 				= $result->id;
		$this->datas['email']			= Include_special_characters($result->email);
		$this->datas['loginDatetime']	= $result->loginDatetime;		
		$this->datas['userName']		= Include_special_characters($result->userName);
		$this->datas['privilege']		= $result->privilege;
		$this->datas['birth']			= $result->birth;
		$this->datas['passwordLength']	= $result->passwordLength;

		$fileName = $result->fileName == 'none' ? 'user_blue.png' : explode('_', $result->fileName)[0].'/'.$result->fileName;


		$this->datas['fileName']		= $fileName;
		$this->datas['theme']			= $result->theme;
		$this->datas['refresh'] 		= $result->refresh;

		// NOT IMPLEMENTED FEATUTRE
		$this->datas['online'] 			= $result->online;

		//--------------------------------------------------------------------------------------------
		
		$this->datas['gameMode'] 		= $result->gameMode;
		$this->datas['level'] 			= $result->level;
		$this->datas['seconds']			= $result->seconds;
		$this->datas['trials'] 			= $result->trials;
		$this->datas['eventLength']		= $result->eventLength;
		$this->datas['color'] 			= $result->color;
	}
}