<?php
namespace DB;

use DB\DBInterface;
use InvalidArgumentException;
use PDOException;
use RuntimeException;
use PDO;


require_once APPLICATION.'Interfaces/DBInterface.php';


/**
 * MySql This is a singleton
 */
class DB
{
    public int $id;

    private static
                $INSTANCE,
                $connect;                

    private static string              
                $host,
                $user,
                $pass,
                $database,
                $DBMS;
           
    /**
     * DBInterface abstract methods
     */
    public static function GetInstance()
    {
        if ( self::$INSTANCE == NULL )
        {
            self::$INSTANCE = new self();                        
        }               
        return self::$INSTANCE;
    }
 

    private function __construct()
    {  
        if (!$params = getConfig())
            throw new InvalidArgumentException('Can\'t access /config.json file!!');

        extract($params);

        self::$host     = $hostName;
        self::$user     = $userName;
        self::$pass     = $password;
        self::$database = $database;
        self::$DBMS     = $DBMS;
     
        if (!self::Connect()) return false;

        return $this->CheckDatabase();
    }

    public function __destruct()
    {
        self::$connect = NULL;
    }

    

    /**
     * SELECT query
     * 
     * Mindig tömbbel tér vissza, hogy loopolható maradjon.
     * 
     * @param string $cript SQL script
     * @param array $params Pramas to bind metodhs
     * 
     * @return array Entity set
     */
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
            if( !$statement->execute() )
            {
                throw new PDOException( $statement->errorInfo()[2] );
            }

            $result = $statement->fetchAll( PDO::FETCH_CLASS );

            $statement = null;     

            return $result;
            
        }
        catch( PDOException $e )
        {                 
            error_log( date('Y-m-d H:i:s').' - '.$e->getMessage()." with: '".$script."' in ".__FILE__." at ".__LINE__.PHP_EOL, 3, APPLICATION.'Log/dberror.log' );       
            $statement = null;
            return [];
        }        
    }
    


    /**
     * EXECUTE query
     */
    public function Execute( string $script, array $params = [] ): bool
    {                    
        //self::$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
 
        try
        {
            // Prepare PDOStatement object
            $statement =  self::$connect->prepare( $script );
            
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

            // Ha nem sikerül végrehajtani a kódot, az csakis az érvénytelen paraméterezés
            // miatt fordulhat elő.
            if (!$statement->execute())
                throw new PDOException(
                    "Message: ".$statement->errorInfo()[2].
                    " Errorcode: ".$statement->errorInfo()[1]);
            

            $statement = null;

            return true;
        }
        catch( PDOException $e )
        {
            $statement = null;

            error_log( 
                date('Y-m-d H:i:s').
                '- Code: '.self::$connect->errorCode()[1].
                '- Msg:  '.self::$connect->errorInfo()[2].', '.$e->getMessage().
                " with: '{$script}' in ".__FILE__." at ".__LINE__.PHP_EOL, 3, APPLICATION.'Log/dberror.log' );       
                    
            return false;
        }
    }
    
    //-----------------------------------------------------------------------------                  


    
    /**
     * Get PDO Connection
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
                self::$DBMS.":host=".self::$host.";dbname=".self::$database.";charset=utf8", 
                self::$user, 
                self::$pass,
                [PDO::ATTR_PERSISTENT => true]
            );            

            self::$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return true;
        }
        catch( PDOException $e ) 
        {           
            error_log( date('Y-m-d h:i:s').' - '.$e->getMessage()." in ".__FILE__." at ".
                __LINE__.PHP_EOL, 3, APPLICATION.'Log/dberror.log' );

            return false;
        }
    }

    private function CheckDatabase()
    {                                                
        if ( count( self::Select('SHOW TABLES') ) < 6 )
        {
            throw new \Exception('Database breaked. Count of tables not enough!');
        }                        
    }
}