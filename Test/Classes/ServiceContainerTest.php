#!/usr/bin/php7.4
<?php

use App\Core\ServiceContainer;

require "../testEngine.php";

$s = new ServiceContainer;

$s->add("User", "App\Services\User");
test($s, "__call", ["User"], "App\Services\User");

$s->add("DB", "App\Services\DB");
test($s, "__call", ["DB"], "App\Services\DB");