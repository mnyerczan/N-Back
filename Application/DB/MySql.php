<?php
namespace DB;

use DB\DBInterface;
use PDOException;
use RuntimeException;
use PDO;


require_once APPROOT.'Interfaces/DBInterface.php';
require_once APPROOT.'DB/baseDbApi.php';

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
                $database   = "NBackDB";

    //-----------------------------------------------------------------------------
           
    /**
     * Navbar Module
     */
    public function getChildLinks( $menuid, $privilege )
    {
        $sql = 'SELECT * FROM menus where parent_id = :menuid AND privilege <= :privilege';

        return $this->Select( $sql, [ ':menuid' => $menuid, ':privilege' => $privilege ] ) ;        

    }

    public function getSessions( $uid )
    {
        $sql="
                select 
                    case when timestamp < current_date then concat('Yesterday ', substr(timestamp,12, 5)) else substr(timestamp, 12, 5) end as time,
                    result, 
                    wrong_hit,
                    correct_hit, 
                    level, 
                    manual, 
                    ip 
                from n_back_sessions 
                where user_id= :uid
                and timestamp > :timestamp  
                order by timestamp desc 
                limit 10";
        
        $param = [ ':uid' => $uid, ':timestamp' => date("Y-m-d H:i:s", mktime(date('H'), date('i'), date('s'), date('m'), date('d') - 1, date('Y'))) ];

        return $this->Select( $sql, $param );
    }


    public function getTimes( $uid )
    {
        $sql ='select
                SEC_TO_TIME(ceil(sum(`time_length`) / 1000)) as "last_day",
                (select
                    SEC_TO_TIME(ceil(sum(`time_length`) / 1000))
                from `n_back_sessions`
                where `user_id` = :uid1
                and `timestamp` > current_date) as "today",
                (select
                    SEC_TO_TIME(ceil(sum(`time_length`) / 1000))
                from `n_back_sessions`
                where `user_id` = :uid2
                and `timestamp` > current_date
                and `manual` = 0) as "today_position"
            from `n_back_sessions`
            where `user_id` = :uid3
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
        $sql = 'SELECT * FROM menus WHERE parent_id = "none" AND privilege <= :privilege ORDER BY child ASC, name ASC';

        return $this->Select( $sql, [ ':privilege' => $privilege ] );
    }    

    //-----------------------------------------------------------------------------           

    /**
     * Home Module
     */
    public function getHomeContent(): string
    {
        $sql = 'SELECT content FROM documents WHERE title = "start_page" AND privilege = 3';
        
        return $this->Select( $sql )[0]->content;
    }

    //-----------------------------------------------------------------------------
           
    /**
     * User module
     */
    public function getUser( array $params ): array
    {
        $sql=
		   "SELECT `users`.*, `n_back_datas`.*, current_timestamp AS refresh 
			FROM `users` JOIN `n_back_datas` 
				ON `users`.`id` = `n_back_datas`.`user_id` 
			WHERE `u_name` = :name 
            AND `password` = :pass ";
            
        return $this->Select( $sql, $params );
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
        round(sum(time_length) / 1000 /60, 1) as minutes
        from n_back_sessions
        where user_id= \"{$this->uid}\"
        and ip =\"{$_SERVER["REMOTE_ADDR"]}\"
        and manual = 0
        group by intDate
        having round(sum(time_length) / 1000 /60, 1) >= 20
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
            error_log( $e->getMessage()." with {$script} in ".__FILE__." at ".__LINE__ );
        }

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
            var_dump($params);
            for ($i=0; $i < count( $keys ); $i++) 
            {           
                $statement->bindParam( $keys[$i], $params[$keys[$i]] );                
            }


            if( !$statement->execute() )
            {
                throw new RuntimeException( $statement->errorInfo()[2] );
            }

            return TRUE;
        }
        catch( RuntimeException $e )
        {
            error_log( $e->getMessage()." with {$script} in ".__FILE__." at ".__LINE__ );

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
            error_log( $e->getMessage()." in ".__FILE__." at ".__LINE__, 3, 'log/dberror.log' );
            die; 
        }
    }
}