<?php
namespace Login;

use DB\EntityGateway;

/**
 * UserEntity, this is a singleton
 */
class UserEntity
{
	private static 
			$loged,
			$dbObject,
	 		$INSTANCE = NULL,
			$datas = [];			


	public static function GetInstance(): object
    {
        if ( self::$INSTANCE == NULL )
        {
			self::$INSTANCE = new self();    
			self::$INSTANCE->Load();         						
        }               
        return self::$INSTANCE;
    }

	private function __construct()
    {
		self::$dbObject = EntityGateway::getDB();
		
		self::$datas['id'] 				= 1;
		self::$datas['name'] 			= 'Guest';
		self::$datas['userName'] 		= 'Guest';
		self::$datas['email'] 			= NULL;
		self::$datas['loginDatetime']	= NULL;
		self::$datas['privilege'] 		= 0;
		self::$datas['birth'] 			= NULL;
		self::$datas['passwordLength'] 	= NULL;
		self::$datas['fileName'] 		= NULL;
		self::$datas['theme'] 			= $_COOKIE['theme'] 		?? 'white';
		self::$datas['refresh'] 		= NULL;

		// NOT IMPLEMENTED FEATUTRE
		self::$datas['online'] 			= NULL;
		//--------------------------------------------------------------------------------------------
		
		self::$datas['gameMode'] 			= $_COOKIE['gameMode'] 		?? 'Position';
		// Game level
		self::$datas['level'] 			= $_COOKIE['level'] 		?? 1;
		// Tim between two event in seconds
		self::$datas['seconds'] 		= $_COOKIE['seconds'] 		?? 3;
		// Min trial has 25 events
		self::$datas['trials'] 			= $_COOKIE['trials'] 		?? 25;
		// One event length in seconds 
		self::$datas['eventLength']	= $_COOKIE['eventLength'] 	?? 0.75;
		// Event's color
		self::$datas['color'] 			= $_COOKIE['color'] 		?? 'blue';
    }


    function __get($name)
    {
        switch($name)
        {
			case 'loged': 			return self::$loged; 					break;

			case 'id': 				return self::$datas['id']; 				break;
			case 'name': 			return self::$datas['name']; 			break;
			case 'userName': 			return self::$datas['userName']; 			break;
			case 'email': 			return self::$datas['email']; 			break;
			case 'loginDatetime': 	return self::$datas['loginDatetime']; 	break;
			case 'privilege': 		return self::$datas['privilege']; 		break;
			case 'birth': 			return self::$datas['birth']; 			break;
			case 'passwordLength': 		return self::$datas['passwordLength']; 		break;
			case 'fileName': 		return self::$datas['fileName']; 		break;
			case 'theme': 			return self::$datas['theme']; 			break;
			case 'refresh': 		return self::$datas['refresh']; 		break;
			case 'online': 			return self::$datas['online']; 			break;

			case 'gameMode': 			return self::$datas['gameMode']; 			break;
			case 'level': 			return self::$datas['level']; 			break;			
			case 'seconds': 		return self::$datas['seconds']; 		break;
			case 'trials': 			return self::$datas['trials']; 			break;
			case 'eventLength': 	return self::$datas['eventLength']; 	break;
			case 'color': 			return self::$datas['color']; 			break;
        }
    }
    

	function Load( $name = 'default', $pass = NULL ): string
    {			
		$result = self::$dbObject->getUser( [ ":name" => $name, ":pass" => $pass ]  );	
			

		if( 1 == count( $result ))
		{		
			self::SetUser( $result );			
	
			return self::$loged = "TRUE";
		}        		

		return self::$loged = "FALSE";
	}

    function Login( string $name, string $pass ): string
    {		
		$result = self::$dbObject->getUser( [ ":name" => $name, ":pass" => md5( "salt".md5( $pass ) ) ]  );			

		if( 1 == count( $result ))
		{		
			self::SetUser( $result );			
			self::SetSession( $result );	
	
			return self::$loged = "TRUE";
		}        		

		return self::$loged = "FALSE";
	
	}

	private function SetSession( $result )
	{
		session_start();

		$_SESSION['userName']				= Include_special_characters($result[0]['userName']);
		$_SESSION['password']			= $result[0]['password'];
	}



	private function SetUser( $result )
	{		
		self::$datas['id'] 				= $result[0]['id'];
		self::$datas['email']			= Include_special_characters($result[0]['email']);
		self::$datas['loginDatetime']	= $result[0]['loginDatetime'];
		self::$datas['name']			= Include_special_characters($result[0]['name']);
		self::$datas['userName']			= Include_special_characters($result[0]['userName']);
		self::$datas['privilege']		= $result[0]['privilege'];
		self::$datas['birth']			= $result[0]['birth'];
		self::$datas['passwordLength']		= $result[0]['passwordLength'];
		self::$datas['fileName']		= $result[0]['fileName'];
		self::$datas['theme']			= $result[0]['theme'];
		self::$datas['refresh'] 		= $result[0]['refresh'];

		// NOT IMPLEMENTED FEATUTRE
		self::$datas['online'] 			= $result[0]['online'];			

		//--------------------------------------------------------------------------------------------
		
		self::$datas['gameMode'] 			= $result[0]['gameMode'];
		self::$datas['level'] 			= $result[0]['level'];
		self::$datas['seconds']			= $result[0]['seconds'];
		self::$datas['trials'] 			= $result[0]['trials'];
		self::$datas['eventLength']	= $result[0]['eventLength'];
		self::$datas['color'] 			= $result[0]['color'];
	}
}