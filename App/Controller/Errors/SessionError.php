<?php 

/**
 * This controller called, if the /var/lib/php/sessions/sess_... file 
 * not accessable, and program couldn't start session.
 * 
 * 
 */

namespace App\Controller\Errors;


use App\Core\BaseController;
use App\Model\ViewParameters;


class SessionError extends BaseController
{
    public function index()
    {
        $this->Response(
            ["message" => "Session file access denied.... :("], 
            new ViewParameters("", "text/html", "_generalError", "Errors", "Session error")
        );
    }
}