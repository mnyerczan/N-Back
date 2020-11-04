<?php


namespace App\Controller\Main\Settings;

use App\Controller\Main\MainController;
use App\DB\DB;
use App\Model\SettingsBar;
use App\Model\ViewParameters;
use App\Model\Seria;
use App\Model\Header;
use App\Model\Navbar;
use App\Model\Indicator;
use App\Model\Sessions;
use App\Model\User;



class SettingsNbackController extends MainController
{

    public function __construct()
    {
        parent::__construct();
        $this->setDatas();  
        // Átadásra kerül a frontend felé, hogy melyik almenű aktív.
        $this->datas['settingsBar'] = new SettingsBar('nbackItem', $id = User::$id);
               
    }


    /**
     * Apprear the nback settings form.
     */
    public function index(string $errorMsg = "")
    {              
        $this->Response( 
            $this->datas, new ViewParameters(
                'settings', 
                'text/html',
                '',
                'Settings',
                'N-back settings',
                $errorMsg,
                'nback')                              
        );
    }

    /**
     * Update anonim user client cookie on browsers
     */
    public function updateAnonim()
    {
        extract($_POST);

        // gameMode
        // level
        // secconds
        // trials
        // eventLength
        // color        
        // lifetime
        $lifeTime = time() + 365 * 24 * 3600;
        // Útvonal: A site gyökérmappájára kell megadni, hogy minden 
        // url-nél elérhető legyen.
        $path = "/NBack";

        setcookie('gameMode', $gameMode, $lifeTime, $path);
        setcookie('level', $level, $lifeTime, $path);
        setcookie('seconds', $seconds, $lifeTime, $path);
        setcookie('trials', $trials, $lifeTime, $path);
        setcookie('eventLength', $eventLength, $lifeTime, $path);
        setcookie('color', $color, $lifeTime, $path);

        $this->Response([], new ViewParameters("redirect:".APPROOT."/settings/nback?sm=Update successfully!"));

    }


    /**
     * Updat logged user
     */
    public function updateUser()
    {        
        $errorMsg = null;

        extract($_POST);
      
        // A bind-olási paraméterek összecsoportosítása a Select függvénynek való
        // átadásra.
        $params = [
            ':inUserId'         => User::$id,
            ':newGameMode'      => $gameMode       ?? 'Position',
            ':newLewel'         => $level          ?? 1,
            ':newSeconds'       => $seconds        ?? 3,
            ':newTrials'        => $trials         ?? 30,
            ':newEventLength'   => $eventLength    ?? 3,
            ':newColor'         => $color          ?? 'blue',
        ];


        // update procedúra meghívása a Select metóduson keresztül. 
        // A validációt az adatbázis végzi.
        if (DB::select('CALL `updateNBackOptions`(
                :inUserId,
                :newGameMode,
                :newLewel,
                :newSeconds,
                :newTrials,
                :newEventLength,
                :newColor
            )', $params)[0]->result == '1') 
        {            
            // Ha sikeres a bevitel, átirányítás.
            $this->Response([],new ViewParameters("redirect:".APPROOT."/settings/nback?sm=Update sucessfully!"));         
        }
        else{
            // Sikertelen művelet esetén hibaüzenet. Mivel a frontend teljesen be van biztosítva, 
            // ez csak akkor fordulhat elő, ha átírják az oldal DOM értékeit.
            $errorMsg = 'Update failed, bacause values are wrong';            

             // Sikertelen módosítás esetén vissaadja saját magát hibaüzenettel.
            $this->index($errorMsg);
        }               
    }

 


    private function getEntitys()
    {
        $this->datas = [ 
            'seria' => (new Seria( $this->user->id ))->seria, 
            'user'  => $this->user,            
            'navbar'=> ( new Navbar( $this->user ) )->getDatas(),
            'indicator' => (
                Indicator::getInstance(
                    new Sessions( $this->user->id, 1 ),
                    $this->user->gameMode 
                )
            )->getDatas(),
            'header' => (new Header( $this->user ))->getDatas()
        ];       
    }
}