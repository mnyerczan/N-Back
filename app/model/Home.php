<?php

namespace App\Model;

use App\Controller\Main\MainController;
use App\Model\Home\HomeViewModel;
use App\Services\DB;


class Home extends MainController
{

    function getContent()
    {   
        $sql        = 'CALL `GetHomeContent`(:inPrivilege)';
        $params     = [':inPrivilege' => 3];     

        $content    = DB::selectAll($sql, $params);


        return new HomeViewModel($content[0] ?? '');
    }
}