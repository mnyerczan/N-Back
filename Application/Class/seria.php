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
        #Ez a script lekéri a timestamp mezők date tagjának intécastolt értékét GROUP BY-olva, hozzá az összevonás számát
        #pusztán szemléltetésnek, és az aznapi összes időt. A WHILE függvény pedíg addig meg, míg az aktuális napi és az
        # egyel korábbi ineger értéke megegyezik. minen loopban nö egyel a seria száma így jön ki a végeredmény.

        $query = "
			select
			cast(current_date as unsigned) as intDate,
			-1 as session,
			-1 as minutes
			union all
			(select
			substr(cast(str_to_date(substr(timestamp, 1 ,10), \"%Y-%m-%d %h:%i%p\" ) as unsigned), 1, 8) as intDate,
			count(*) as session,
			round(sum(time_length) / 1000 /60, 1) as minutes
			from n_back_sessions
			where user_id= \"{$this->uid}\"
			and ip =\"{$_SERVER["REMOTE_ADDR"]}\"
			and manual = 0
			group by intDate
			having round(sum(time_length) / 1000 /60, 1) >= 20
			order by intDate DESC, session ASC
            LIMIT 30)";                
                    
        $this->result = $this->dbObject->Select( $query );
                
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