<?php 
use DB\EntityGateway;

class Seria
{
    private $dbObject,
            $uid,
            $result,
            $seria = 0;    

    public function __construct( int $uid )
    {
        $this->dbObject = EntityGateway::getDB(); 
        $this->uid = $uid;                  

        $this->GetResult();
        $this->CalculateSeria();         
    }

    public function __get( $name )
    {
        switch( $name )
        {
            case 'result':  return $this->result; break;
            case 'seria':   return $this->seria; break;
        }
    }


    protected function GetResult()
    {                                   
        $this->result = $this->dbObject->getSeria();
    
                
    }

    protected function CalculateSeria()
    {      
        /**
         *  If today or yesterday is complete day. 
         */ 
        
        if( count( $this->result ) > 1 && (int)$this->result[0]['intDate'] < (int)$this->result[1]['intDate'] + 2 )
        {                       

            for( $i = 1; (int)$this->result[$i]['intDate'] === (int)$this->result[$i + 1]['intDate'] + 1 && $i < count( $this->result ) -1; $i++ )
            {                       
                if( $this->result[$i]['session'] != -1 )
                {
                    /**
                     * Test for days of months
                     */
                    if ( (int)$this->result[$i]['intDate'] === (int)($this->result[$i + 1]['intDate'] ) + 1 ||

                        ((  date('m') == "01" || 
                            date('m') == "03" || 
                            date('m') == "05" || 
                            date('m') == "07" ||  
                            date('m') == "08" || 
                            date('m') == "10" || 
                            date('m') == "10") 
                            &&  (int)$this->result[$i]['intDate'] === (int)($this->result[$i + 1]['intDate']) + 7 ) ||

                        (   date('m') == "02" 
                            &&  (int)$this->result[$i]['intDate'] === (int)($this->result[$i + 1]['intDate']) + 7 ) ||

                        ((  date('m') == "04" || 
                            date('m') == "06" || 
                            date('m') == "09" || 
                            date('m') == "11") 
                            &&  (int)$this->result[$i]['intDate'] === (int)($this->result[$i + 1]['intDate']) + 7 )
                    ){                  
                        $this->seria ++;
                    }                
                }                
            } 
                                
        }                
    }
}