<?php
namespace App\DB;

use Exception;
use InvalidArgumentException;
use PDOException;
use PDOStatement;
use LogicException;
use PDO;


class DB
{
    private static PDO $connect;                

    
    private static string $host;
    private static string $user;
    private static string $pass;
    private static string $database;
    private static string $DBMS;
             
    /**
     * Initialisation
     */
    public static function setup()
    {  
        self::config();    
        self::connect();                     
        return self::checkDatabase();
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
    public static function select( string $script, array $params = [], string $type = null ): array
    {               

        $pdoStatement =  self::$connect->prepare($script);     
     
        self::binds($pdoStatement, $params);           
                

        try {
            if( !$pdoStatement->execute() )            
                throw new PDOException($pdoStatement->errorInfo()[2]);

            if ($type)
                $result = $pdoStatement->fetchAll(PDO::FETCH_CLASS, $type);
            else 
                $result = $pdoStatement->fetchAll(PDO::FETCH_CLASS);

            $pdoStatement = null;     

            return $result;
            
        } catch( PDOException $e ) {                 
            error_log( date('Y-m-d H:i:s').' - '.$e->getMessage()." with: '".$script."' in ".__FILE__." at ".__LINE__.PHP_EOL, 3, APPLICATION.'Log/dberror.log' );       
            $statement = null;
            return [];
        }        
    }
    
    /**
     * EXECUTE query
     * @throws LogicException   Logikai hiba eseténe
     */
    public static function execute( string $script, array $params = [] )
    {                    
        //self::$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Prepare PDOStatement object
            $smt =  self::$connect->prepare($script);
            
            self::binds($smt, $params); 

            // Ha nem sikerül végrehajtani a kódot, az csakis az érvénytelen paraméterezés
            // miatt fordulhat elő.
            if (!$smt->execute())
                throw new LogicException(
                    "Message: ".$smt->errorInfo()[2].
                    " Errorcode: ".$smt->errorInfo()[1]);
                        
            // Hibakód ellenőrzés. Ha nincs mit lekérdezni, PODException-t dob.
            $smt = self::$connect->prepare("SELECT @full_error AS error");
            $smt->execute();

            // Adatbézis szintű logikai hibáról értesítést kap a hívó.
            if(($error = $smt->fetch(PDO::FETCH_OBJ)->error) !== "") 
                throw new LogicException($error);

        } catch(PDOException $exception) {
            $smt = null;

            error_log( 
                date('Y-m-d H:i:s').
                '- Code: '.self::$connect->errorCode()[1].
                '- Msg:  '.self::$connect->errorInfo()[2].', '.$exception->getMessage().
                " with: '{$script}' in ".__FILE__." at ".__LINE__.PHP_EOL, 3, APPLICATION.'Log/dberror.log' );       
                    
            throw new LogicException($exception->getMessage());
        } catch(LogicException $exception) {
            error_log( 
                date('Y-m-d H:i:s').
                '- Code: '.self::$connect->errorCode()[1].
                '- Msg:  '.self::$connect->errorInfo()[2].', '.$exception->getMessage().
                " with: '{$script}' in ".__FILE__." at ".__LINE__.PHP_EOL, 3, APPLICATION.'Log/dberror.log' );       
            
            throw new LogicException($exception->getMessage());
        }

    }
    
    /**
     * Binding parameters
     */
    private static function binds(PDOStatement &$pdoStatement, array $params)
    {
        $keys = array_keys($params);
        /**
         * A LIMIT és OFFSET esetében, az sql integer számot vár, egyébként olyan hibát dob vissza,
         * amely még a kivételobjektumban sem került definiálásra (WTF??). 
         * 
         * A PDO::PARAM_INT konstans értéke tulajdonképpen 1, amivel integer értékűre állítjuk a kapott 
         * adat feldolgozását.
         * https://phpdelusions.net/pdo#limit
         * 
         * Debug:  $pdoStatement->debugDumpParams();
         */          
        for ($i=0; $i < count( $keys ); $i++) { 
            if ( $keys[$i] === ':limit' || $keys[$i] === ':offset' )
                $pdoStatement->bindParam( $keys[$i], $params[$keys[$i]], PDO::PARAM_INT );
            else
                $pdoStatement->bindParam( $keys[$i], $params[$keys[$i]] );
        } 
    }
    
    /**
     * Get PDO Connection
     */
    private static function connect()
    {            
        try {     
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
        } catch( PDOException $e ) {           
            error_log( date('Y-m-d h:i:s').' - '.$e->getMessage()." in ".__FILE__." at ".
                __LINE__.PHP_EOL, 3, APPLICATION.'Log/dberror.log' );
        }
    }

    /**
     * Check tables
     */
    private static function checkDatabase() 
    {                                                  
        if ( count( self::select('SHOW TABLES') ) < 6 ) {
            throw new \Exception('Database breaked. Count of tables not enough!');
        }          
        
        self::execute("Set @full_error = ''");
    }

    /**
     * Load configuration to connect database
     */
    private static function config()
    {
        $params = json_decode(file_get_contents(CONF_PATH), true);

        if (!$params)
            throw new InvalidArgumentException('Can\'t access /config.json file!!');

        extract($params);

        self::$host     = $hostName;
        self::$user     = $userName;
        self::$pass     = $password;
        self::$database = $database;
        self::$DBMS     = $DBMS;
    }
}