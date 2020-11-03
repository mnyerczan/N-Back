<?php

namespace App\Model;



use App\DB\DB;
use App\Classes\ImageConverter;
use App\Classes\ValidateDate;
use App\Classes\ValidateEmail;
use App\Classes\ValidatePassword;
use App\Classes\ValidateSex;
use App\Classes\ValidateUser;
use InvalidArgumentException;
use LogicException;

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
			/**
			 * Anonim user id = 0
			 * Regisztrál user id = 1
			 * a 2 fenntartva??
			 * Admin user ud = 3 		
			 */
			$id,
			$name,
			$isAdmin,
			$email,
			$loginDatetime,
			$privilege,
			$birth,
			$sex,
			$passwordLength,
			$theme,
			$refresh,
			$online,
			$about,
			$imgBin,
			// NBACK OPTIONS
			$gameMode,
			$level,
			$seconds,
			$trials,
			$eventLength,
			$color;					




	public static function GetInstance(): object
    {		
		if (self::$INSTANCE == NULL) {						
			self::$INSTANCE = new self();    
			
			// Csak akkor tölt be adatbázisból, ha van bejelentkezés.
			// Egyébként default + cookie, ha van.
			if (isset($_SESSION['userId']))
				self::$INSTANCE->LoadUser( $_SESSION['userId'] );				 
			else			
				self::$INSTANCE->LoadAnonim();
							
		}        		   
        return self::$INSTANCE;
	}
	
	private function __construct()
    {				 
		$this->db = DB::GetInstance();		
	}

	public function getUsersCount()
	{		
		return $this->db->Select('CALL `GetUserCount`()')[0];
	}

	private function LoadAnonim()
	{		
		// Anonim user id == 0!!!
		$this->id 				= 0;	
		$this->name 			= 'Anonim';
		$this->isAdmin			= false;
		$this->email 			= NULL;
		$this->loginDatetime	= NULL;
		$this->privilege 		= 0;
		$this->birth 			= NULL;
		$this->passwordLength 	= NULL;
		$this->fileName 		= 'user_blue.png';
		$this->theme 			= $_COOKIE['theme'] 		?? 'white';
		$this->refresh 			= NULL;
		
		// Van az anonimnak saját képe, hogy meg lehessen jeleníteni
		// az nback settings felületet.
		$this->imgBin 			= ImageConverter::BTB64(
			$this->db->select("SELECT imgBin FROM images WHERE userID = 1")[0]->imgBin
		);

		// NOT IMPLEMENTED FEATUTRE
		$this->online 			= NULL;
		//--------------------------------------------------------------------------------------------				

		if (!isset($_COOKIE['gameMode'])) {
			setcookie('gameMode','Position');
			$this->gameMode = 'Position';
		} else
			$this->gameMode = $_COOKIE['gameMode'];
		

		// Game level
		if (!isset($_COOKIE['level'])) {
			setcookie('level', 1);
			$this->level = 1;
		} else
			$this->level = $_COOKIE['level'];
		

		// Tim between two event in seconds
		if (!isset($_COOKIE['seconds'])) {
			setcookie('seconds', 3);
			$this->seconds = 3;
		} else
			$this->seconds = $_COOKIE['seconds'];
		

		// Min trial has 25 events
		if (!isset($_COOKIE['trials'])) {
			setcookie('trials', 25);
			$this->trials 	= 25;
		} else
			$this->trials = $_COOKIE['trials'];
		

		// One event length in seconds 
		if (!isset($_COOKIE['eventLength'])) {
			setcookie('eventLength', 0.7);
			$this->eventLength = 0.7;
		} else
			$this->eventLength = $_COOKIE['eventLength'];
		

		// Event's color
		if (!isset($_COOKIE['color'])) {
			setcookie('color','blue');		
			$this->color = 'blue';
		} else
			$this->color = $_COOKIE['color'];
    }
	


	function LoadUser( int $userId )
    {	
		// Anonim felhasználóhoz nem tartoznak személyes adatok.
		if ($userId == 1) return false; 
		
		$sql = 'CALL GetUser(:inUId, :inEmail, :inPass)';

		$params = [
			':inUId' => $userId,
			':inEmail' => '', 			
			':inPass' => ''
			];

		$user = $this->db->Select($sql,$params)[0]; 
		
		$this->id 				= $user->id;
		$this->email			= $user->email;
		$this->loginDatetime	= $user->loginDatetime;		
		$this->name				= $user->name;
		$this->isAdmin			= $this->name == 'Admin' ? true : false;
		$this->privilege		= $user->privilege;
		$this->birth			= $user->birth;
		$this->sex				= $user->sex;
		$this->passwordLength	= $user->passwordLength;
		$this->imgBin			= ImageConverter::BTB64($user->imgBin);		
		$this->theme			= $user->theme;
		$this->about			= $user->about;	
		// NOT IMPLEMENTED FEATUTRE
		$this->online 			= $user->online;
		//--------------------------------------------------------------------------------------------		
		$this->gameMode 		= $user->gameMode;
		$this->level 			= $user->level;
		$this->seconds			= $user->seconds;
		$this->trials 			= $user->trials;
		$this->eventLength		= $user->eventLength;
		$this->color 			= $user->color;												
		$this->loged 			= true;		
	}

    function Login( string $email, string $password ): string
    {				
		$params = [
			':inUId' => null,
			':inEmail' => $email, 			
			':inPass' => md5("salt".md5($password ))
		];

		$user = $this->db->Select(
			'CALL GetUser(:inUId, :inEmail, :inPass)'
			,$params
		);

		if (!is_array($user)) return false;
		
		if(count($user)) {							
			$this->SetSession( $user[0] );	
	
			return $this->loged = true;
		}        		

		return $this->loged = false;	
	}

	private function SetSession( $result )
	{
		$_SESSION['userId']		= $result->id;
	}

    function __get($name)
    {		
        switch($name) {
			case 'loged': 			return $this->loged; 			break;
			case 'id': 				return $this->id; 				break;			
			case 'name': 			return $this->name; 			break;
			case 'isAdmin': 		return $this->isAdmin; 			break;
			case 'email': 			return $this->email; 			break;
			case 'loginDatetime': 	return $this->loginDatetime; 	break;
			case 'privilege': 		return $this->privilege; 		break;
			case 'birth': 			return $this->birth; 			break;
			case 'sex': 			return $this->sex; 				break;
			case 'passwordLength': 	return $this->passwordLength; 	break;			
			case 'theme': 			return $this->theme; 			break;
			case 'refresh': 		return $this->refresh; 			break;
			case 'online': 			return $this->online; 			break;
			case 'about':			return $this->about;			break;
			case 'imgBin': 			return $this->imgBin; 			break;
			case 'gameMode': 		return $this->gameMode; 		break;
			case 'level': 			return $this->level; 			break;			
			case 'seconds': 		return $this->seconds; 			break;
			case 'trials': 			return $this->trials; 			break;
			case 'eventLength': 	return $this->eventLength; 		break;
			case 'color': 			return $this->color; 			break;
			default:
				throw new InvalidArgumentException("The needed variable doese't exist: \"{$name}\"");
        }
	}
		
	/**
	 * Felhasználó beírása az adatbázisba. 2020.10.19
	 * 
	 * @param ValidateEmail 	$email,
	 * @param ValidateUser 		$user
	 * @param ValidatePassword	$password
	 * @param ValidateDate		$birthDAte
	 * @param string			$sex
	 * @param int				$privilege
	 * @param ImageConverter	$converter
	 * 
	 */
	public function userRegistry(
		 ValidateEmail $email, 
		 ValidateUser $user, 
		 ValidatePassword $password, 
		 ValidateDate $birthDAte, 
		 ValidateSex $sex, 
		 $privilege, 
		 ImageConverter $converter) {
		// Létrehozzuk az SQL bind-olási paramétereket.	
		$params = [            
            ':email'            => trim( $email->getEmail() ),
            ':userName'         => trim( $user->getUser() ),
            ':password'         => $password->getPass(),
            ':dateOfBirth'      => trim( $birthDAte->getDate() ),
            ':sex'              => $sex->getSex(),
            ':privilege'        => $privilege,
            ':passwordLength'   => strlen($password->getPass()),
            ':cmpBin'           => $converter->cmpBin
		];     
		
		$sql = 'CALL CreateNewUserprocedure(
            :userName,
            :email, 
            :password, 
            :dateOfBirth,
            :sex,
            :privilege, 
            :passwordLength,
            :cmpBin)';

		// Ha a művelet nem sikerül, kivételt dob, amit nem kapunk el.
		$this->db->Execute($sql, $params);
	}
}