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
class User
{

	static 
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

	
	
	public static function setup()
    {				 
		if (isset($_SESSION['userId']))
			self::loadUser( $_SESSION['userId'] );				 
		else			
			self::loadAnonim();
	}


	public static function getUsersCount()
	{		
		return DB::select('CALL `GetUserCount`()')[0];
	}

	private static function loadAnonim()
	{		
		// Anonim user id == 0!!!
		self::$id 				= 1;	
		self::$name 			= 'Anonim';
		self::$isAdmin			= false;
		self::$email 			= NULL;
		self::$loginDatetime	= NULL;
		self::$privilege 		= 0;
		self::$birth 			= NULL;
		self::$passwordLength 	= NULL;
		self::$theme 			= $_COOKIE['theme'] 		?? 'white';
		self::$refresh 			= NULL;
		
		// Van az anonimnak saját képe, hogy meg lehessen jeleníteni
		// az nback settings felületet.
		self::$imgBin 			= ImageConverter::BTB64(
			DB::select("SELECT imgBin FROM images WHERE userID = 1")[0]->imgBin
		);

		// NOT IMPLEMENTED FEATUTRE
		self::$online 			= NULL;
		//--------------------------------------------------------------------------------------------				

		if (!isset($_COOKIE['gameMode'])) {
			setcookie('gameMode','Position');
			self::$gameMode = 'Position';
		} else
			self::$gameMode = $_COOKIE['gameMode'];
		

		// Game level
		if (!isset($_COOKIE['level'])) {
			setcookie('level', 1);
			self::$level = 1;
		} else
			self::$level = $_COOKIE['level'];
		

		// Tim between two event in seconds
		if (!isset($_COOKIE['seconds'])) {
			setcookie('seconds', 3);
			self::$seconds = 3;
		} else
			self::$seconds = $_COOKIE['seconds'];
		

		// Min trial has 25 events
		if (!isset($_COOKIE['trials'])) {
			setcookie('trials', 25);
			self::$trials 	= 25;
		} else
			self::$trials = $_COOKIE['trials'];
		

		// One event length in seconds 
		if (!isset($_COOKIE['eventLength'])) {
			setcookie('eventLength', 0.5);
			self::$eventLength = 0.5;
		} else
			self::$eventLength = $_COOKIE['eventLength'];
		

		// Event's color
		if (!isset($_COOKIE['color'])) {
			setcookie('color','blue');		
			self::$color = 'blue';
		} else
			self::$color = $_COOKIE['color'];
    }
	


	protected static function loadUser( int $userId )
    {	
		// Anonim felhasználóhoz nem tartoznak személyes adatok.
		if ($userId == 1) return false; 
		
		$sql = 'CALL GetUser(:inUId, :inEmail, :inPass)';

		$params = [
			':inUId' => $userId,
			':inEmail' => '', 			
			':inPass' => ''
			];

		$user = DB::select($sql,$params)[0]; 
		
		self::$id 				= $user->id;
		self::$email			= $user->email;
		self::$loginDatetime	= $user->loginDatetime;		
		self::$name				= $user->name;
		self::$isAdmin			= self::$name == 'Admin' ? true : false;
		self::$privilege		= $user->privilege;
		self::$birth			= $user->birth;
		self::$sex				= $user->sex;
		self::$passwordLength	= $user->passwordLength;
		self::$imgBin			= ImageConverter::BTB64($user->imgBin);		
		self::$theme			= $user->theme;
		self::$about			= $user->about;	
		// NOT IMPLEMENTED FEATUTRE
		self::$online 			= $user->online;
		//--------------------------------------------------------------------------------------------		
		self::$gameMode 		= $user->gameMode;
		self::$level 			= $user->level;
		self::$seconds			= $user->seconds;
		self::$trials 			= $user->trials;
		self::$eventLength		= $user->eventLength;
		self::$color 			= $user->color;												
		self::$loged 			= true;		
	}

    public static function login( string $email, string $password ): string
    {				
		$params = [
			':inUId' => null,
			':inEmail' => $email, 			
			':inPass' => md5("salt".md5($password ))
		];

		$user = DB::select(
			'CALL GetUser(:inUId, :inEmail, :inPass)'
			,$params
		);

		if (!is_array($user)) return false;
		
		if(count($user)) {							
			self::setSession( $user[0] );	
	
			return self::$loged = true;
		}        		

		return self::$loged = false;	
	}

	private static function setSession( $result )
	{
		$_SESSION['userId']		= $result->id;
	}

    public function __call($name, $arguments)	
    {		
        switch($name) {
			case 'loged': 			return self::$loged; 			break;
			case 'id': 				return self::$id; 				break;			
			case 'name': 			return self::$name; 			break;
			case 'isAdmin': 		return self::$isAdmin; 			break;
			case 'email': 			return self::$email; 			break;
			case 'loginDatetime': 	return self::$loginDatetime; 	break;
			case 'privilege': 		return self::$privilege; 		break;
			case 'birth': 			return self::$birth; 			break;
			case 'sex': 			return self::$sex; 				break;
			case 'passwordLength': 	return self::$passwordLength; 	break;			
			case 'theme': 			return self::$theme; 			break;
			case 'refresh': 		return self::$refresh; 			break;
			case 'online': 			return self::$online; 			break;
			case 'about':			return self::$about;			break;
			case 'imgBin': 			return self::$imgBin; 			break;
			case 'gameMode': 		return self::$gameMode; 		break;
			case 'level': 			return self::$level; 			break;			
			case 'seconds': 		return self::$seconds; 			break;
			case 'trials': 			return self::$trials; 			break;
			case 'eventLength': 	return self::$eventLength; 		break;
			case 'color': 			return self::$color; 			break;
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
	public static function userRegistry(
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
		DB::execute($sql, $params);
	}
}