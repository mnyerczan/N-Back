<?php
namespace DB;

use DB\DBInterface;
use PDOException;
use RuntimeException;
use PDO;


require_once APPLICATION.'Interfaces/DBInterface.php';
require_once APPLICATION.'DB/baseDbApi.php';

/**
 * MySql This is a singleton
 */
class MySql extends baseDbApi implements DBInterface
{
    public                      
                $id;

    private static 
                $INSTANCE,
                $host       = "localhost",
                $user       = "root",
                $pass       = "1024",
                $database   = "testDB";

    //-----------------------------------------------------------------------------
        
    private static function CheckDatabase()
    {
        $statement = self::$connect->prepare('SHOW TABLES');

        try 
        {
            $statement->Execute();

            $tables = $statement->fetchAll();
            
            if ( count( $tables ) < 6 )
            {
                throw new RuntimeException('Database breaked. Count of tables not enough ');
            }
            
        } 
        catch ( RuntimeException $e ) 
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

        return $this->Select( $sql, [ ':menuid' => $menuid, ':privilege' => $privilege ] ) ;        

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

        return $this->Select( $sql, $param );
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

        return $this->Select( $sql, [ 
            ':uid1' => $uid,
            ':uid2' => $uid, 
            ':uid3' => $uid, 
            ':timestamp' => date("Y-m-d H:i:s", mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 1, date('Y'))) ] 
        );

    }

    public function getMenus( $privilege )
    {        
        $sql = 'SELECT * FROM menus WHERE parentID = "none" AND privilege <= :privilege ORDER BY child ASC, name ASC';

        return $this->Select( $sql, [ ':privilege' => $privilege ] );
    }    

    //-----------------------------------------------------------------------------           

    /**
     * Home Module
     */
    public function getHomeContent(): string
    {
        $sql = 'SELECT content FROM documents WHERE title = "start_page" AND privilege = 3';
        

        return $this->Select( $sql )[0]->content ?? '';
    }

    //-----------------------------------------------------------------------------
           
    /**
     * User module
     */

    public function getUsersCount(): array
    {
        return $this->Select('SELECT COUNT(*) as num FROM users');
    }

    public function getUser( array $params ): array
    {
        if ( array_key_exists( ':userId', $params ) )
        {
            $sql=
		   "SELECT `users`.*, `nbackDatas`.*, current_timestamp AS refresh 
			FROM `users` JOIN `nbackDatas` 
				ON `users`.`id` = `nbackDatas`.`userID` 
			WHERE `users`.`id` = :userId";
        }
        else
        {
            $sql=
            "SELECT `users`.*, `nbackDatas`.*, current_timestamp AS refresh 
             FROM `users` JOIN `nbackDatas` 
                 ON `users`.`id` = `nbackDatas`.`userID` 
             WHERE `email` = :email 
             AND `password` = :password ";
        }        
            
        return $this->Select( $sql, $params );
    }

    public function userRegistry( array $params )
    {        

        $sql= "INSERT INTO `users` ( `email`, `userName`, `password`, `birth`, `privilege` ) VALUES (  :email, :userName, :password, :dateOfBirth, :privilege )";        

        return $this->Execute( $sql, $params );
    }


    //-----------------------------------------------------------------------------       

    /**
     * Seria module
     */
    public function getSeria(): array
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
        where userID= \"{$this->uid}\"
        and ip =\"{$_SERVER["REMOTE_ADDR"]}\"
        and gameMode = 0
        group by intDate
        having round(sum(sessionLength) / 1000 /60, 1) >= 20
        order by intDate DESC, session ASC
        LIMIT 30)";   

        return $this->Select( $script );
    }

    //-----------------------------------------------------------------------------
           
    /**
     * DBInterface abstract methods
     */
    public static function GetInstance(): object
    {
        if ( self::$INSTANCE == NULL )
        {
            self::$INSTANCE = new self(); 
            self::Connect();     
            self::CheckDatabase();                    
        }               
        return self::$INSTANCE;
    }


    private function Select( string $script, array $params = [] ): array
    {
        $statement =  self::$connect->prepare($script);     
        
        $keys = array_keys( $params );
        /**
         * A LIMIT és OFFSET esetében, az sql integer számot vár, egyébként olyan hibát dob vissza,
         * amely még a kivételobjektumban sem került definiálásra (WTF??). 
         * 
         * A PDO::PARAM_INT konstans értéke tulajdonképpen 1, amivel integer értékűre állítjuk a kapott 
         * adat feldolgozását.
         * https://phpdelusions.net/pdo#limit
         * 
         * Debug:  $statement->debugDumpParams();
         */          
        for ($i=0; $i < count( $keys ); $i++) 
        { 
            if ( $keys[$i] === ':limit' || $keys[$i] === ':offset' )
            {
                $statement->bindParam( $keys[$i], $params[$keys[$i]], PDO::PARAM_INT );
            }
            else
            {
                $statement->bindParam( $keys[$i], $params[$keys[$i]] );
            } 
        }
         
        try
        {
            if ( !$statement->execute() )
            {
                throw new RuntimeException( $statement->errorInfo()[2] );
            }
            
            return $statement->fetchAll( PDO::FETCH_CLASS );
            
        }
        catch( RuntimeException $e )
        {
            error_log( date('Y-m-d h:i:s').' - '.$e->getMessage()." with {$script} in ".__FILE__." at ".__LINE__.PHP_EOL, 3, APPLICATION.'Log/dberror.log' );        }

        return $params;
    }
    

    private function Execute( string $script, array $params = [] ): bool
    {    
    
        try
        {
            if ( !$statement =  self::$connect->prepare( $script ) )
            {
                throw new PDOException( $statement->errorInfo()[2] );
            }


            $keys = array_keys( $params );
            /**
             * A LIMIT és OFFSET esetében, az sql integer számot vár, egyébként olyan hibát dob vissza,
             * amely még a kivételobjektumban sem került definiálásra (WTF??). 
             * 
             * A PDO::PARAM_INT konstans értéke tulajdonképpen 1, amivel integer értékűre állítjuk a kapott 
             * adat feldolgozását.
             * https://phpdelusions.net/pdo#limit
             * 
             * Debug:  $statement->debugDumpParams();
             */          
            for ( $i = 0; $i < count( $keys ); $i++ )
            { 
                if ( $keys[$i] === ':limit' || $keys[$i] === ':offset' )
                {
                    $statement->bindParam( $keys[$i], $params[$keys[$i]], PDO::PARAM_INT );
                }
                else
                {
                    $statement->bindParam( $keys[$i], $params[$keys[$i]] );
                } 
            }


            if( !$statement->execute() )
            {             
                throw new RuntimeException( $statement->errorInfo()[2] );                
            }

            return TRUE;
        }
        catch( RuntimeException $e )
        {
            error_log( date('Y-m-d h:i:s').' - '.$e->getMessage()." with {$script} in ".__FILE__." at ".__LINE__.PHP_EOL, 3, APPLICATION.'Log/dberror.log' );
            
            return FALSE;
        }
    }
    
    //-----------------------------------------------------------------------------                  

    /**
     * Helper function to get PDO object
     */
    private static function Connect(): void
    {
        try 
        {
            /**
             * baseDbApi::$connect
             */
            self::$connect = new PDO( "mysql:host=".self::$host.";dbname=".self::$database.";charset=utf8", self::$user, self::$pass );            
        }
        catch( PDOException $e ) 
        {           
            error_log( date('Y-m-d h:i:s').' - '.$e->getMessage()." in ".__FILE__." at ".__LINE__.PHP_EOL, 3, APPLICATION.'Log/dberror.log' );
            die; 
        }
    }
}