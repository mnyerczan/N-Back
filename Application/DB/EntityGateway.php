<?php
namespace DB;

use DB\MySql;
use Exception;
use RuntimeException;

require_once 'MySql.php';


class EntityGateway
{
    public static $INSTANCE;

    private 
            $dbname = 'DB\MySql',
            $serial,
            $object;

    /**
     * Database abstraction level
     * 
     * @return object of a specified Database class
     */
    public static function getInstance()    
    {        
        if (self::$INSTANCE)
            return self::$INSTANCE;
        else
        {            
            self::$INSTANCE = new self;            
            return self::$INSTANCE;
        }        
    }

    private function __construct()
    {
        $this->serial = rand(0, 1000000);        
        $this->object = $this->dbname::GetInstance();
        
        if (!$this->object)
            throw new RuntimeException("Cant connect to database");

        $this->CheckDatabase();
        
    }

    public function StartTransaction()
    {
        return $this->object->StartTransaction();
    }

    public function Rollback()
    {
        return $this->object->Rollback();
    }

    public function Commit()
    {
        return $this->object->Commit();
    }

    private function CheckDatabase()
    {        
        try 
        {                        
            
            if ( count( $this->object->select('SHOW TABLES') ) < 6 )
            {
                throw new Exception('Database breaked. Count of tables not enough ');
            }
            
        } 
        catch ( Exception $e ) 
        {
            error_log( $e->getMessage()." in ".__FILE__." at ".__LINE__.PHP_EOL, 3, APPLICATION.'Log/dberror.log' );

            die('Sorry! Something is wrong while load page!');
        }
    }


    //-----------------------------------------------------------------------------

    /**
     * Navbar Module
     */
    public function getChildMenus( $menuid, $privilege )
    {
        $sql = 'SELECT * FROM menus where parentID = :menuid AND privilege <= :privilege';

        return $this->object->Select( $sql, [ ':menuid' => $menuid, ':privilege' => $privilege ] ) ;        

    }

    public function getSessions( $uid, string $anytime = NULL )
    {
        $sql="
                select 
                    case when timestamp < current_date then concat('Yesterday ', substr(timestamp,12, 5)) else substr(timestamp, 12, 5) end as time,
                    result, 
                    wrongHit,
                    correctHit, 
                    level, 
                    gameMode, 
                    ip,
                    timestamp,
                    sessionLength
                from nbackSessions 
                where userID= :uid
                and timestamp > :timestamp  
                order by timestamp desc 
                limit 10";        
        
        $timestamp = $anytime ?? date("Y-m-d H:i:s", mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 1, date('Y')));
        
        $param = [ ':uid' => $uid, ':timestamp' => $timestamp ];

        return $this->object->Select( $sql, $param );
    }


    public function getTimes( $uid )
    {
        $sql ='select
                SEC_TO_TIME(ceil(sum(`sessionLength`) / 1000)) as "last_day",
                (select
                    SEC_TO_TIME(ceil(sum(`sessionLength`) / 1000))
                from `nbackSessions`
                where `userID` = :uid1
                and `timestamp` > current_date) as "today",
                (select
                    SEC_TO_TIME(ceil(sum(`sessionLength`) / 1000))
                from `nbackSessions`
                where `userID` = :uid2
                and `timestamp` > current_date
                and `gameMode` = 0) as "today_position"
            from `nbackSessions`
            where `userID` = :uid3
            and `timestamp` > :timestamp';

        return $this->object->Select( $sql, [ 
            ':uid1' => $uid,
            ':uid2' => $uid, 
            ':uid3' => $uid, 
            ':timestamp' => date("Y-m-d H:i:s", mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 1, date('Y'))) ] 
        );

    }

    public function getMenus( $privilege )
    {        
        $sql = 'SELECT * FROM `menus` WHERE `parentID` = "none" AND `privilege` <= :privilege ORDER BY `child` ASC, `name` ASC';

        return $this->object->Select( $sql, [ ':privilege' => $privilege ] );
    }    

    //-----------------------------------------------------------------------------           

    /**
     * Home Module
     */
    public function getHomeContent(): string
    {
        $sql = 'SELECT content FROM documents WHERE title = "start_page" AND privilege = 3';
        

        return $this->object->Select( $sql )[0]->content ?? '';
    }

    //-----------------------------------------------------------------------------
           
    /**
     * User module
     */

    public function getUsersCount(): array
    {
        return $this->object->Select('SELECT COUNT(*) as num FROM users');
    }



    public function getUser( array $params ): array
    {        
        if ( array_key_exists( ':userId', $params ) )
        {
            $userSql=
		   "SELECT `users`.*, `nbackDatas`.*, current_timestamp AS refresh 
			FROM `users` JOIN `nbackDatas` 
				ON `users`.`id` = `nbackDatas`.`userID`         
            WHERE `users`.`id` = :userId";
            

            $image  = $this->object->Select( 
                "SELECT `imgBin` FROM `images` WHERE `userID` = :userId", 
                [ ':userId' => $params[':userId']] 
            )[0] ?? null;       
        }
        else
        {
            $userSql =
            "SELECT `users`.*, `nbackDatas`.*, current_timestamp AS refresh 
             FROM `users` JOIN `nbackDatas` 
                 ON `users`.`id` = `nbackDatas`.`userID`
             WHERE `email` = :email 
             AND `password` = :password ";


            $image  = $this->object->Select( 
                "SELECT `imgBin` FROM `images` WHERE `userID` = (SELECT `id` FROM `users` WHERE `email` = :email)", 
                [ ':email' => $params[':email']] 
            );
        }               
      
        
        $user   = $this->object->Select( $userSql, $params )[0];
            

        return [
            "user" => $user,
            "image"=> $image ?? null
        ];
    }



    public function userRegistry( array $params )
    {        

        $sql= "INSERT INTO `users` ( `email`, `userName`, `password`, `birth`, `privilege` ) VALUES (  :email, :userName, :password, :dateOfBirth, :privilege )";        

        return $this->object->Execute( $sql, $params );
    }


    public function InsertImageFromSignUp($imageBin)
    {
        $getUserIdSql = 'SELECT MAX(`id`) userId FROM `users`';
        $insertImageSql = 'INSERT INTO `images`(`userID`,`imgBin`) VALUES (:userId, :binary)';
        

        if (!$result = $this->object->Select($getUserIdSql)[0])
            return false;
        
        return $this->object->Execute( $insertImageSql, [ ':userId' => $result->userId, ':binary' => $imageBin] );
    }



    //-----------------------------------------------------------------------------       

    /**
     * Seria module
     */
    public function getSeria( $uid ): array
    {
        #Ez a script lekéri a timestamp mezők date tagjának intécastolt értékét GROUP BY-olva, hozzá az összevonás számát
        #pusztán szemléltetésnek, és az aznapi összes időt. A WHILE függvény pedíg addig meg, míg az aktuális napi és az
        # egyel korábbi ineger értéke megegyezik. minen loopban nö egyel a seria száma így jön ki a végeredmény.

        $script = "
        select
        cast(current_date as unsigned) as intDate,
        -1 as session,
        -1 as minutes
        union all
        (select
        substr(cast(str_to_date(substr(timestamp, 1 ,10), \"%Y-%m-%d %h:%i%p\" ) as unsigned), 1, 8) as intDate,
        count(*) as session,
        round(sum(sessionLength) / 1000 /60, 1) as minutes
        from nbackSessions
        where userID= :uid
        and ip = :REMOTE_ADDRESS
        and gameMode = 0
        group by intDate
        having round(sum(sessionLength) / 1000 /60, 1) >= 20
        order by intDate DESC, session ASC
        LIMIT 30)";   

        return $this->object->Select( $script, [ ':uid' => $uid , ':REMOTE_ADDRESS' => $_SERVER["REMOTE_ADDR"] ]);
    }

}
