<?php
namespace DB;

use DB\DBInterface;
use PDOException;
use RuntimeException;
use PDO;


if( @defined(LOGIN) )
{
    require_once '.././Interfaces/DBInterface.php';
}
else
{
    require_once 'Interfaces/DBInterface.php';
}


require_once 'baseDbApi.php';

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
            error_log( $e->getMessage(), 3, 'log/dberror.log' );
            die; 
        }
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
         
        try
        {
            if ( !$statement->execute() )
            {
                throw new RuntimeException( $statement->errorInfo()[2] );
            }
            
            return $statement->fetchAll();
            
        }
        catch( RuntimeException $e )
        {
            LogLn( 1, $e->getMessage()."in ".__FILE__." at ".__LINE__ );   
            error_log( $e->getMessage() );
        }

        return [];
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
            LogLn( 1, $e->getMessage()."in ".__FILE__." at ".__LINE__ );
            error_log( $e->getMessage() );

            return FALSE;
        }
    }
    
}