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
			$logged,
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
		return DB::select('CALL `getUserCount`()')[0];
	}

	private static function loadAnonim()
	{		
		// Anonim user id == 0!!!
		$sql = 'CALL getUser(:inUId, :inEmail, :inPass)';

		$params = [
			':inUId' => 1,
			':inEmail' => '', 			
			':inPass' => ''
			];

		$user = self::getUser($sql, $params);

		self::$id 				= $user->id;	
		self::$name 			= $user->name;
		self::$isAdmin			= false;
		self::$email 			= NULL;
		self::$loginDatetime	= NULL;
		self::$privilege 		= $user->privilege;
		self::$birth 			= $user->birth;
		self::$passwordLength 	= $user->paswordLength;
		self::$theme 			= $_COOKIE['theme'] ?? $user->theme;
		self::$refresh 			= NULL;
		
		// Van az anonimnak saját képe, hogy meg lehessen jeleníteni
		// az nback settings felületet.
		self::$imgBin 			= ImageConverter::BTB64($user->imgBin);

		// NOT IMPLEMENTED FEATUTRE
		self::$online 			= NULL;
		//--------------------------------------------------------------------------------------------				

		if (!isset($_COOKIE['gameMode'])) {
			setcookie('gameMode',$user->gameMode);
			self::$gameMode = $user->gameMode;
		} else
			self::$gameMode = $_COOKIE['gameMode'];
		

		// Game level
		if (!isset($_COOKIE['level'])) {
			setcookie('level', $user->level);
			self::$level = $user->level;
		} else
			self::$level = $_COOKIE['level'];
		

		// Tim between two event in seconds
		if (!isset($_COOKIE['seconds'])) {
			setcookie('seconds', $user->seconds);
			self::$seconds = $user->seconds;
		} else
			self::$seconds = $_COOKIE['seconds'];
		

		// Min trial has 25 events
		if (!isset($_COOKIE['trials'])) {
			setcookie('trials', $user->trials);
			self::$trials 	= $user->trials;
		} else
			self::$trials = $_COOKIE['trials'];
		

		// One event length in seconds 
		if (!isset($_COOKIE['eventLength'])) {
			setcookie('eventLength', $user->eventLength);
			self::$eventLength = $user->eventLength;
		} else
			self::$eventLength = $_COOKIE['eventLength'];
		

		// Event's color
		if (!isset($_COOKIE['color'])) {
			setcookie('color', $user->color);		
			self::$color = $user->color;
		} else
			self::$color = $_COOKIE['color'];
    }
	


	protected static function loadUser( int $userId )
    {	
		// Anonim felhasználóhoz nem tartoznak személyes adatok.
		if ($userId == 1) return false; 
		
		$sql = 'CALL getUser(:inUId, :inEmail, :inPass)';

		$params = [
			':inUId' => $userId,
			':inEmail' => '', 			
			':inPass' => ''
			];


		$user = self::getUser($sql, $params);

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
		self::$logged 			= true;		
	}

    public static function login( string $email, string $password ): string
    {				
		$params = [
			':inUId' => null,
			':inEmail' => $email, 			
			':inPass' => md5("salt".md5($password ))
		];

		$user = DB::select(
			'CALL getUser(:inUId, :inEmail, :inPass)'
			,$params
		);

		if (!is_array($user)) return false;
		
		if(count($user)) {							
			self::setSession($user[0]);		
			return self::$logged = true;
		}        		

		return self::$logged = false;	
	}

	private static function setSession( $result )
	{
		$_SESSION['userId'] = $result->id;
	}

    public function __call($name, $arguments)	
    {		
        switch($name) {
			case 'logged': 			return self::$logged; 			break;
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
		
		self::exportUser(			
			trim($user->getUser()),
			trim($email->getEmail()),
			$password->getPass(),
			trim($birthDAte->getDate()),
			$sex->getSex(),
			$privilege,
			$converter->cmpBin
		);

	}


	private static function getUser($sql, $params)
	{
		$user = DB::select($sql,$params); 
		if ($user == []) {
			$params[":inUId"] = 1;			
			if ($user = DB::select($sql, $params) == []) 
				self::setupAnonim();								
		}			

		return DB::select($sql, $params)[0];
	}


	private static function exportUser($name, $email, $pass, $birth, $sex, $privilege, $cmpBin)	
	{
		$params = [
			":name" => $name,
			":email" => $email,
			":password" => $pass,
			":dateOfBirth" => $birth,
			":sex" => $sex,
			":privilege" => $privilege,			
			":cmpBin" => $cmpBin
		];

		$sql = 'CALL createNewUserprocedure(
            :name,
            :email, 
            :password, 
            :dateOfBirth,
            :sex,
            :privilege,        
            :cmpBin)';

		DB::execute($sql, $params);
	}

	/**
	 *  Default user
	 *	----------------
	 *	Szükséges táblák
	 * 
	 *	users
	 *	images
	 *	nbackDatas
	 *	nbackSessions
	 *	userWrongSessions
	 */
	private static function setupAnonim()
	{				
		self::exportUser(
			"Anonim",
			"default@user.com",
			NULL,
			NULL,
			"male",
			0,
			(new ImageConverter("/var/www/html".APPROOT."/App/Images/userMale.png"))->cmpBin
		);
	}
}