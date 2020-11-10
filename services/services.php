<?php

use App\Model\ModelAndView;
use App\Model\ViewParameters;

return [
    "ModelAndView" => function(string $container, $params) {
        return new ModelAndView($container::get("ViewParameters", $params));
    },
    "ViewParameters" => function(string $container, $params) {
        return new ViewParameters(...$params);
    }
];