<?php 
use DB\DB;

class Seria
{
    private $db,   
            $result,
            $seria = 0;    

    public function __construct( int $uid )
    {
        $this->db = DB::GetInstance();                   

        $this->result = $this->GetResult( $uid );
        $this->seria  = $this->CalculateSeria();      
          
    }

    public function __get( $name )
    {
        switch( $name )
        {
            case 'result':  return $this->result; break;
            case 'seria':   return $this->seria; break;
        }
    }


    private function GetResult( int $uid ): array
    {   
        // A WHILE függvény pedíg addig meg, míg az aktuális napi és az
        // egyel korábbi ineger értéke megegyezik. minen loopban nö egyel a seria száma így jön ki a végeredmény.   
    
        $sql = 'CALL GetSeria(:inRemoteAddress, :inUserId)';                                
        $params = [':inRemoteAddress' => $_SERVER['REMOTE_ADDR'], ':inUserId' => $uid];
  

        return ( $this->db->Select($sql, $params));
    }

    private function CalculateSeria(): int
    {      
        /**
         *  If today or yesterday is complete day. 
         */ 
        $seria = 0;

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
                        $seria ++;
                    }                
                }                
            } 
                                
        }        
        return $seria;        
    }
}