<?php

use Login\UserEntity;
use DB\EntityGateway;

class Navbar
{
    private $user,
            $database;

    private $datas = [];            

    

    function __construct( UserEntity $user )    
    {
        $this->datas['childLinks'] = [];

        $this->user = $user;
        $this->database = EntityGateway::getDB(); 

        $this->getMenus();
        $this->getTimes();
        $this->getSessions();
        $this->getChildLinks();            
    }



    function getDatas()
    { 
        return $this->datas;
    }


    function getChildLinks()
    {
        $childlink = [];

        for( $i = 0; $i < count( $this->datas['menus'] ); $i++ )
        {
            $childlink = $this->database->getChildLinks( $this->datas['menus'][$i]->id, $this->user->privilege );

            if( count( $childlink ) > 0 )
            {
                $this->datas['childLinks'][ (string)$this->datas['menus'][$i]->id ] = $childlink;
            }                
        }
    
        
        
    }

    private function getSessions()
    {
        $this->datas['sessions'] = $this->database->getSessions( $this->user->id );
    }


    private function getMenus()
    {        
        $this->datas['menus'] = $this->database->getMenus( $this->user->privilege );

        for ( $i=0; $i < count( $this->datas['menus'] ); $i++ ) 
        { 
            $this->datas['menus'][$i]->name = strlen(Include_special_characters($this->datas['menus'][$i]->name)) > 8 ? 
                substr(Include_special_characters( $this->datas['menus'][$i]->name ), 0, 8).'..' : Include_special_characters( $this->datas['menus'][$i]->name );
        }
    }


    private function getTimes()
    {        

        $times = $this->database->getTimes( $this->user->id )[0];

        $times->last_day = $times->last_day == NULL ? 0 : $times->last_day;
        $times->today = $times->today == NULL ? 0 : $times->today;
        $times->today_position = $times->today_position == NULL ? 0 : $times->today_position;

        $this->datas['times'] = $times;
    }
}