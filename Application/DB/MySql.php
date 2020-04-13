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
class MySql extends baseDbApi
{
    public                      
                $id;

    private static 
                $INSTANCE,
                $host       = "localhost",
                $user       = "root",
                $pass       = "1024",
                $database   = "NBackDB";
           
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
   
    public function StartTransaction()
    {
        return self::$connect->beginTransaction();
    }

    public function Rollback()
    {
        return self::$connect->rollBack();
    }

    public function Commit()
    {
        return self::$connect->commit();
    }


    public function Select( string $script, array $params = [] ): array
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
         
        $statement->execute();

        try
        {
            if( !$statement->execute() )
            {
                throw new RuntimeException( $statement->errorInfo()[2] );
            }
                     

            return $statement->fetchAll( PDO::FETCH_CLASS );        
            
        }
        catch( RuntimeException $e )
        {
            error_log( date('Y-m-d H:i:s').' - '.$e->getMessage()." with: '".addslashes($script)."' in ".__FILE__." at ".__LINE__.PHP_EOL, 3, APPLICATION.'Log/dberror.log' );       
            return [];
        }        
    }
    

    public function Execute( string $script, array $params = [] ): bool
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
                throw new PDOException( 'Errno: '.$statement->errorInfo()[1].' - '.$statement->errorInfo()[2] );                
            }
            

            return true;
        }
        catch( PDOException $e )
        {
            error_log( date('Y-m-d H:i:s').' - '.$e->getMessage()." with: '{addslashes($script)}' in ".__FILE__." at ".__LINE__.PHP_EOL, 3, APPLICATION.'Log/dberror.log' );
                        

            return $statement->errorInfo()[1];
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
             * 
             * ARRR_PERSISTENT -> állandó adatbázis kapcsolat fenntartása új szálak generálása helyett. Gyorsabb.
             */
            self::$connect = new PDO( 
                "mysql:host=".self::$host.";dbname=".self::$database.";charset=utf8", 
                self::$user, 
                self::$pass,
                [PDO::ATTR_PERSISTENT => true]
            );
        }
        catch( PDOException $e ) 
        {           
            error_log( date('Y-m-d h:i:s').' - '.$e->getMessage()." in ".__FILE__." at ".__LINE__.PHP_EOL, 3, APPLICATION.'Log/dberror.log' );
            die; 
        }
    }
}