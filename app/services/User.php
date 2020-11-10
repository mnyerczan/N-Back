<?php

namespace App\Services;



use App\Services\DB;
use App\Classes\ImageConverter;
use App\Classes\ValidateDate;
use App\Classes\ValidateEmail;
use App\Classes\ValidatePassword;
use App\Classes\ValidateSex;
use App\Classes\ValidateUser;
use InvalidArgumentException;
use LogicException;



abstract class User
{

	static  bool $logged = false;	
	static	bool $isAdmin = false;	
	static	string $name;	
	static	?string $email;
	static	?string $loginDatetime;
	static	?string $birth;
	static	string $sex;
	static	string $theme;
	static	string $about;	
	/**
	 * A user profilképe
	 * @var $bin
	 */
	static	string $bin;
	/**
	 * Anonim user privilege = 0
	 * Regisztrál user privilege = 1
	 * a 2 fenntartva??
	 * Admin user privilege = 3
	 * @var $privilege
	 */	
	static	int $privilege;
	static	int $id;	
	static	?int $passwordLength;		
	/**
	 * Nem implementált funkció
	 * @var $online	 
	 */
	static	?int $online;		
	// NBACK OPTIONS	
	static	int $level;
	static	int $seconds;
	static	int $trials;
	static	float $eventLength;
	static	string $color;
	static	string $gameMode;

	
	
	public static function setup()
    {			
		if (isset($_SESSION['userId']))
			self::loadUser( $_SESSION['userId'] );				 
		else
			self::loadAnonim();
	}


	private static function loadAnonim()
	{						
		// Anonim user id = 1!!!			
		$user = self::getUser(null, null, 1);

		self::$id 				= $user->id;	
		self::$name 			= $user->name;		
		self::$email 			= NULL;
		self::$loginDatetime	= NULL;
		self::$privilege 		= $user->privilege;
		self::$birth 			= $user->birth;
		self::$passwordLength 	= $user->paswordLength;
		self::$theme 			= $_COOKIE['theme'] ?? $user->theme;
		
		// Van az anonimnak saját képe, hogy meg lehessen jeleníteni
		// az nback settings felületet.
		self::$bin 				= ImageConverter::BTB64($user->bin);
		//--------------------------------------//
		// NOT IMPLEMENTED FEATUTRE
		self::$online 			= NULL;
		//--------------------------------------//		

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
	


	protected static function loadUser(int $userId)
    {	
		$user = self::getUser(null, null, $userId);

		self::$logged 			= true;	
		self::$id 				= $user->id;
		self::$email			= $user->email;
		self::$loginDatetime	= $user->loginDatetime;		
		self::$name				= $user->name;
		self::$isAdmin			= self::$name == 'Admin' ? true : false;
		self::$privilege		= $user->privilege;
		self::$birth			= $user->birth;
		self::$sex				= $user->sex;
		self::$passwordLength	= $user->passwordLength;
		self::$bin				= ImageConverter::BTB64($user->bin);		
		self::$theme			= $user->theme;
		self::$about			= $user->about;	
		//--------------------------------------//
		// NOT IMPLEMENTED FEATUTRE		
		self::$online 			= $user->online;
		//--------------------------------------//
		self::$gameMode 		= $user->gameMode;
		self::$level 			= $user->level;
		self::$seconds			= $user->seconds;
		self::$trials 			= $user->trials;
		self::$eventLength		= $user->eventLength;
		self::$color 			= $user->color;															
	}

	/**
	 * Logining user by email and password
	 * @param string $email
	 * @param string password
	 * @return bool
	 */
    public static function login( string $email, string $password ): bool
    {				
		// $user must be a object
		$user = self::getUser($email, $password);
		
		if($user->id != 1) {
			self::setSession($user->id);
			return self::$logged = true;
		}        		

		return self::$logged = false;	
	}

	/**
	 * Session handler
	 */
	private static function setSession(int $id): void
	{
		$_SESSION['userId'] = $id;
	}

	/**
	 * Static getter for static class property
	 * 
	 * @param $name Name of the property
	 * @param $arguments Arguments if we can call a class method
	 * 
	 * @throws InvalidArgumentException If the named property does not exists
	 */
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
			case 'online': 			return self::$online; 			break;
			case 'about':			return self::$about;			break;
			case 'bin': 			return self::$bin; 				break;
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
	 * @throws LogicException
	 * 
	 */
	public static function userRegistry(
		 ValidateEmail $email, 
		 ValidateUser $user, 
		 ValidatePassword $password, 
		 ValidateDate $birthDAte, 
		 ValidateSex $sex, 
		 $privilege, 
		 ImageConverter $converter):void {
		
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


	/**
	 * Load user from database and, if not existx,
	 * call static setUpAnonim method, then load user
	 * 
	 * @param ?string $email
	 * @param ?string $password
	 * @param ?aint $id
	 * @return object Object of user entity
	 */
	protected static function getUser(?string $email = null, ?string $password = null,  ?int $id = null): object
	{
		$sql = 'CALL getUser(:inUId, :inEmail, :inPass)';

		$params = [
			':inUId' => $id,
			':inEmail' => $email, 			
			':inPass' => $password
		];

		$user = DB::select($sql, $params); 
		if ($user == null) {
			$params[":inUId"] = 1;
			if (($user = DB::select($sql, $params)) == null) {
				self::setupAnonim();								
				return DB::select($sql, $params);
			}				
		}
		return $user;
	}

	/**
	 * Export user to database from getted datas
	 * @param string $name Name of user
	 * @param string $email Email of user
	 * @param string $pass Password of user
	 * @param string $birth Birthday of user
	 * @param string $sex
	 * @param int $privilege
	 * @param string $cmpBin
	 * @throws LogicException
	 * 
	 * 	----------------
	 *	Szükséges táblák
	 * 
	 *	users
	 *	images
	 *	nbackDatas
	 *	nbackSessions
	 *	userWrongSessions
	 */
	private static function exportUser($name, $email, $pass, $birth, $sex, int $privilege, $cmpBin): void
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

		$sql = 'CALL createUser(
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
	 * @throws LogicException
	 */
	private static function setupAnonim(): void
	{				
		self::exportUser(
			"Anonim",
			"default@user.com",
			NULL,
			NULL,
			"male",
			0,
			(new ImageConverter("/var/www/html".APPROOT."/App/images/userMale.png"))->cmpBin
		);
	}
}