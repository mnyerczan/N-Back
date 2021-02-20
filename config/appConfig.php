<?php


 return [
     // must end with a slash =>  "folder1/folder2/"
     "applicationRoot" => "",
     
     "cwd" => $_SERVER["REQUEST_URI"],

     "dbConfigPath" => "config/dbconfig.php",

     "reloadIndicator" => "_".date("s"),
     
];