<?php


namespace App\Controller;

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
        $this->datas['settingsBar'] = new SettingsBar('nbackItem');
               
    }


    /**
     * Apprear the nback settings form.
     */
    public function index()
    {              
        $this->Response( 
            $this->datas, new ViewParameters(
                'settings', 
                'text/html',
                'Main',
                'Settings',
                'N-back settings',
                "",
                'nback')                              
        );
    }


    public function update()
    {        

        $errorMsg = null;

      

        // A bind-olási paraméterek összecsoportosítása a Select függvénynek való
        // átadásra.
        $params = [
            ':inUserId'         => $this->datas['user']->id,
            ':newGameMode'      => $_POST['gameMode']       ?? 'Position',
            ':newLewel'         => $_POST['level']          ?? 1,
            ':newSeconds'       => $_POST['seconds']        ?? 3,
            ':newTrials'        => $_POST['trials']         ?? 30,
            ':newEventLength'   => $_POST['eventLength']    ?? 3,
            ':newColor'         => $_POST['color']          ?? 'blue',
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
            
            // Ha sikeres a bevitel, átirányítás.
            $this->Response([],new ViewParameters("redirect:".APPROOT."/settings/nback?sm=Update sucessfully!"));         
        }

        else
        {
            // Sikertelen művelet esetén hibaüzenet. Mivel a frontend teljesen be van biztosítva, 
            // ez csak akkor fordulhat elő, ha átírják az oldal DOM értékeit.
            $errorMsg = 'Update failed, bacause values are wrong';
            

             // Sikertelen módosítás esetén vissaadja saját magát hibaüzenettel.
            $this->Response( 
                $this->datas, new ViewParameters(
                    'settings', 
                    'text/html', 
                    'Main',
                    'Settings',
                    'N-back settings',
                    $errorMsg,
                    'nback')                
            );
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