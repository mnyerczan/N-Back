#!/usr/bin/php7.4
<?php

use App\Core\ServiceContainer;
use App\Model\ViewParameters;
use Test\Test;


require "../testEngine.php";


Test::test("App\Core\ServiceContainer", "get", ["ViewParameters", []], "App\Model\ViewParameters");
Test::test("App\Core\ServiceContainer", "get", ["ModelAndView"], "App\Model\ModelAndView");