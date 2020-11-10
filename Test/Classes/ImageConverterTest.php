#!/usr/bin/php7.4
<?php

use App\Classes\ImageConverter;

chdir("/var/www/html/NBack");

require "Test/testEngine.php";


$i = new Imagick("App/Images/userMale.png");

var_dump(strlen($i));

$i->setSize(100,100);
var_dump(strlen($i));
echo $i->getCompression();
$i->setCompression();
var_dump(strlen($i));
/*
$img = new ImageConverter("App/Images/userMale.png");


// var_dump((2**16 / strlen($img->bin)) * strlen($img->bin) -10000);
// var_dump((2**16 +1000) / strlen($img->bin));
// echo 2**16 .PHP_EOL;

var_dump($img->getSize());
$img->resize(100,200);
var_dump($img->getSize());

/*
$pdo = new PDO(
    "mysql:host=localhost;dbname=NBackDB",
    "ms",
    "1024"
);

$smt = $pdo->prepare("UPDATE images set bin = :bin, `update` = CURRENT_TIMESTAMP WHERE userId = 1");

$smt->bindValue(":bin", $img->cmpBin);

if(!$smt->execute())
    echo $smt->errorInfo()[2] . PHP_EOL;
*/