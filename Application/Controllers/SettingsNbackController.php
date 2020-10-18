<?php

use DB\DB;
use \Model\Sessions;




class SettingsNbackController extends BaseController
{

    public function __construct()
    {
        parent::__construct();

        $this->db  = DB::GetInstance();        
        $this->getEntitys();  
               
    }


    /**
     * Apprear the nback settings form.
     */
    public function index()
    {
        //var_dump($this->datas['user']);die;

        $this->Response( 
            $this->datas, [
                'view'      => 'settings', 
                'nback'     => 'active',
                'item'      => 'nback',                
                'module'    => 'Settings',
                'mime'      => 'text/html',
                'layout'    => 'Main',
                "title"     => 'N-back settings',
            ]  
        );
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