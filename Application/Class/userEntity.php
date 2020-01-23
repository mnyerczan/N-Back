<?php
namespace Login;

use DB\EntityGateway;

class UserEntity
{
    private $result,
			$dbObject;
			
	private $datas = [];			


    function __get($name)
    {
        switch($name)
        {
			case 'result': 			return $this->result; 					break;

			case 'id': 				return $this->datas['id']; 				break;
			case 'name': 			return $this->datas['name']; 			break;
			case 'u_name': 			return $this->datas['u_name']; 			break;
			case 'email': 			return $this->datas['email']; 			break;
			case 'login_datetime': 	return $this->datas['login_datetime']; 	break;
			case 'privilege': 		return $this->datas['privilege']; 		break;
			case 'birth': 			return $this->datas['birth']; 			break;
			case 'pw_length': 		return $this->datas['pw_length']; 		break;
			case 'file_name': 		return $this->datas['file_name']; 		break;
			case 'theme': 			return $this->datas['theme']; 			break;
			case 'refresh': 		return $this->datas['refresh']; 		break;
			case 'online': 			return $this->datas['online']; 			break;

			case 'manual': 			return $this->datas['manual']; 			break;
			case 'level': 			return $this->datas['level']; 			break;			
			case 'seconds': 		return $this->datas['seconds']; 		break;
			case 'trials': 			return $this->datas['trials']; 			break;
			case 'event_length': 	return $this->datas['event_length']; 	break;
			case 'color': 			return $this->datas['color']; 			break;
        }
    }

    function __construct()
    {
		$this->dbObject = EntityGateway::getDB();
		
		$this->datas['id'] 				= 1;
		$this->datas['name'] 			= NULL;
		$this->datas['u_name'] 			= NULL;
		$this->datas['email'] 			= NULL;
		$this->datas['login_datetime']	= NULL;
		$this->datas['privilege'] 		= NULL;
		$this->datas['birth'] 			= NULL;
		$this->datas['pw_length'] 		= NULL;
		$this->datas['file_name'] 		= NULL;
		$this->datas['theme'] 			= @$_COOKIE['theme'] ? $_COOKIE['theme'] : 'white';
		$this->datas['refresh'] 		= NULL;

		// NOT IMPLEMENTED FEATUTRE
		$this->datas['online'] 			= NULL;
		//--------------------------------------------------------------------------------------------
		
		$this->datas['manual'] 			= NULL;
		$this->datas['level'] 			= NULL;
		$this->datas['seconds'] 		= NULL;
		$this->datas['trials'] 			= NULL;
		$this->datas['event_length']	= NULL;
		$this->datas['color'] 			= NULL;	
    }


	function Load( $name = 'default', $pass = NULL ): string
    {
		$sql=
		   "SELECT `users`.*, `n_back_datas`.*, current_timestamp AS refresh 
			FROM `users` JOIN `n_back_datas` 
				ON `users`.`id` = `n_back_datas`.`user_id` 
			WHERE `u_name` = :name 
			AND `password` = :pass ";
		

		$result = $this->dbObject->Select( $sql, [ ":name" => $name, ":pass" => $pass ]  );	
	
		if( 1 == count( $result ))
		{		
			$this->SetUser( $result );			
	
			return $this->result = "TRUE";
		}        		

		return $this->result = "FALSE";
	}

    function Login( string $name, string $pass ): string
    {
		$sql=
		   "SELECT `users`.*, `n_back_datas`.*, current_timestamp AS refresh 
			FROM `users` JOIN `n_back_datas` 
				ON `users`.`id` = `n_back_datas`.`user_id` 
			WHERE `u_name` = :name 
			AND `password` = :pass ";
		

		$result = $this->dbObject->Select( $sql, [ ":name" => $name, ":pass" => md5( "salt".md5( $pass ) ) ]  );	
	
		if( 1 == count( $result ))
		{		
			$this->SetUser( $result );			
			$this->SetSession( $result );	
	
			return $this->result = "TRUE";
		}        		

		return $this->result = "FALSE";
	
	}

	private function SetSession( $result )
	{
		session_start();

		$_SESSION['u_name']				= Include_special_characters($result[0]['u_name']);
		$_SESSION['password']			= $result[0]['password'];
	}



	private function SetUser( $result )
	{		
		$this->datas['id'] 				= $result[0]['id'];
		$this->datas['email']			= Include_special_characters($result[0]['email']);
		$this->datas['login_datetime']	= $result[0]['login_datetime'];
		$this->datas['name']			= Include_special_characters($result[0]['name']);
		$this->datas['u_name']			= Include_special_characters($result[0]['u_name']);
		$this->datas['privilege']		= $result[0]['privilege'];
		$this->datas['birth']			= $result[0]['birth'];
		$this->datas['pw_length']		= $result[0]['pw_length'];
		$this->datas['file_name']		= $result[0]['file_name'];
		$this->datas['theme']			= $result[0]['theme'];
		$this->datas['refresh'] 		= $result[0]['refresh'];

		// NOT IMPLEMENTED FEATUTRE
		$this->datas['online'] 			= $result[0]['online'];			

		//--------------------------------------------------------------------------------------------
		
		$this->datas['manual'] 			= $result[0]['manual'];
		$this->datas['level'] 			= $result[0]['level'];
		$this->datas['seconds']			= $result[0]['seconds'];
		$this->datas['trials'] 			= $result[0]['trials'];
		$this->datas['event_length']	= $result[0]['event_length'];
		$this->datas['color'] 			= $result[0]['color'];
	}
}