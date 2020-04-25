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
                $user       = "www-data",
                $pass       = "0000",
                $database   = "NBackDB";
           
    /**
     * DBInterface abstract methods
     */
    public static function GetInstance()
    {
        if ( self::$INSTANCE == NULL )
        {
            self::$INSTANCE = new self(); 
            
            if (!self::Connect())
                return false;
        }               
        return self::$INSTANCE;
    }

   /**
    *  START TRANSACTION
    */
    public function StartTransaction()
    {
        return self::$connect->beginTransaction();
    }


    /**
     * ROLLBACK transaction
     */
    public function Rollback()
    {
        return self::$connect->rollBack();
    }


    /**
     * COMMIT transaction
     */
    public function Commit()
    {
        return self::$connect->commit();
    }



    /**
     * SELECT query
     */
    public function Select( string $script, array $params = [] ): array
    {
        var_dump($script);
        var_dump($params);
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

            $result = $statement->fetchAll( PDO::FETCH_CLASS );

            $statement = null;     

            return $result;
            
        }
        catch( RuntimeException $e )
        {
            error_log( date('Y-m-d H:i:s').' - '.$e->getMessage()." with: '".addslashes($script)."' in ".__FILE__." at ".__LINE__.PHP_EOL, 3, APPLICATION.'Log/dberror.log' );       
            $statement = null;
            return [];
        }        
    }
    


    /**
     * EXECUTE query
     */
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
            $statement = null;

            return true;
        }
        catch( PDOException $e )
        {
            error_log( date('Y-m-d H:i:s').' - '.$e->getMessage()." with: '{addslashes($script)}' in ".__FILE__." at ".__LINE__.PHP_EOL, 3, APPLICATION.'Log/dberror.log' );
                        
            $statement = null;
            return false;
        }
    }
    
    //-----------------------------------------------------------------------------                  


    
    /**
     * Helper function to get PDO object
     */
    private static function Connect(): bool
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

            return true;
        }
        catch( PDOException $e ) 
        {           
            error_log( date('Y-m-d h:i:s').' - '.$e->getMessage()." in ".__FILE__." at ".__LINE__.PHP_EOL, 3, APPLICATION.'Log/dberror.log' );
            return false;
        }
    }
}