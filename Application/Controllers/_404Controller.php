<?php

use Login\UserEntity;

class _404Controller extends Controller
{
    private $user;

    function Action()
    {
        $this->View('_404');
    }

    function __construct( UserEntity $user )
    {
        $this->user = $user;
        $this->Action();
    }
}