<?php
namespace DB;

use DB\MySql;
use Exception;
use RuntimeException;

require_once 'DB.php';


class EntityGateway
{
    public static $INSTANCE;

    private 
            $dbname = 'DB\DB',
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
        {
            throw new Exception("Cant connect to database");
        }            

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




    public function userRegistry( array $params )
    {                                                      

        $sql = 'CALL CreateNewUserprocedure(
            :userName,
            :email, 
            :password, 
            :dateOfBirth,
            :sex,
            :privilege, 
            :passwordLength,
            :cmpBin)';

        return $this->object->Execute($sql, $params);
    }

    
}
