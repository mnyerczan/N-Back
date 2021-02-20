#!/usr/bin/php7.4
<?php 

use App\Controller\Main\Settings\SettingsAccountController;
use Test\Test;

require "test/testEngine.php";

// require APPLICATION.'controller/main/settings/SettingsAccountController.php';


/*
 | ----------------------------------------------------------
 | A SettingsAccountController ostrély validatePass metódusa
 | belső tagváltozókba ír, ezért megtartja az állapotát.
 | Minden tezstnél új objektumot kell inicializálni!!
 | ----------------------------------------------------------
 */
class T extends SettingsAccountController
{
    public function __construct(){}
}



// Password change test.
// Bad old password
Test::test(new T(), "validatePass", ['xyzv..', 'almafa','almafa'], true);

// Different new password;
Test::test(new T(), "validatePass", ["almafák","csokibekak","csokibeka"],  false);

// Too short password
Test::test(new T(), "validatePass", ["almafa","cso","cso"], false);

// Good passwords
Test::test(new T(), "validatePass", ["almafak","csokibekakak","csokibekakak"], true);

// számokkal
Test::test(new T(), "validatePass", ["almafa", "csokibeka1","csokibeka1"],  true);

// különleges karakterekkel
Test::test(new T(), "validatePass", ["almafa_", "csokibeka?","csokibeka?"], true);

// különleges karakterekkel
Test::test(new T(), "validatePass", ["almafa./?", "cs@kibekák1?","cs@kibekák1?"], true);