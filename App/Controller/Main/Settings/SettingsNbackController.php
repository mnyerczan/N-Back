<?php


namespace App\Controller\Main\Settings;

use App\DB\DB;
use App\Model\SettingsBar;
use App\Core\BaseController;
use App\Model\ViewParameters;
use App\Model\Seria;
use App\Model\Header;
use App\Model\Navbar;
use App\Model\Indicator;
use App\Model\Sessions;



class SettingsNbackController extends BaseController
{

    public function __construct()
    {
        parent::__construct();

        $this->db  = DB::GetInstance();        
        $this->getEntitys(); 
        
        // Átadásra kerül a frontend felé, hogy melyik almenű aktív.
        $this->datas['settingsBar'] = new SettingsBar('nbackItem', $this->user->id);
               
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
        setcookie('gameMode', $gameMode);
        setcookie('level', $level);
        setcookie('seconds', $seconds);
        setcookie('trials', $trials);
        setcookie('eventLength', $eventLength);
        setcookie('color', $color);

        $this->Response([], new ViewParameters("redirect:".APPROOT."/settings/nback?sm=Update successfully!"));

    }


    /**
     * Updat logged user
     */
    public function update()
    {        
        $errorMsg = null;

        extract($_POST);
      
        // A bind-olási paraméterek összecsoportosítása a Select függvénynek való
        // átadásra.
        $params = [
            ':inUserId'         => $this->datas['user']->id,
            ':newGameMode'      => $gameMode       ?? 'Position',
            ':newLewel'         => $level          ?? 1,
            ':newSeconds'       => $seconds        ?? 3,
            ':newTrials'        => $trials         ?? 30,
            ':newEventLength'   => $eventLength    ?? 3,
            ':newColor'         => $color          ?? 'blue',
        ];


        // update procedúra meghívása a Select metóduson keresztül. 
        // A validációt az adatbázis végzi.
        if ($this->db->Select('CALL `updateNBackOptions`(
                :inUserId,
                :newGameMode,
                :newLewel,
                :newSeconds,
                :newTrials,
                :newEventLength,
                :newColor
            )', $params)[0]->result == '1') 
        {            
            setcookie('gameMode', $gameMode);
            setcookie('level', $level);
            setcookie('secconds', $seconds);
            setcookie('trials', $trials);
            setcookie('eventLength', $eventLength);
            setcookie('color', $color);

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