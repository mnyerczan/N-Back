<?php

use App\Model\ModelAndView;
use App\Model\ViewParameters;

return [
    "ModelAndView" => fn (string $container, $params) => new ModelAndView($container::get("ViewParameters", $params)),
    "ViewParameters" => fn (string $container, $params) => new ViewParameters(...$params)    
];