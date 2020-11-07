<?php


return [
    /**
     * Ebben a fájlban kell elhelyezni az egyes http route-okat, a következő formában:
     * 
     * "url_regex_pattern" => [
     *      "controller" => "controller_class_full_path",                
     *      "action" => "methond_of_controller_class",
     *      "logged" => "is_logged_the_user"
     * ],
     * 
     * A GET, POST, ... tömbök a Http metódushoz tartozó hívásokat jelöli. Ha egy útvonal 
     * a GET metódushoz tartozik, akkor az algoritmus csak a GET Http kérés esetén fog rátalálni,
     * mert csak a metódus neve alatt lévő bejegyjéseket vizsgálja át. Ha nem találja meg a keresett
     * útvonalat, a rendszer 404 error dob.
     */
    "GET" => [        
        "/" => [
            "controller" => "App\Controller\Main\HomeController",                
            "action" => "",
            "logged" => false
        ],
        // SignUpController: index
        "/signUp/form" => [
            "controller" => "App\Controller\Main\SignUpController",                
            "action" => "",
            "logged" => false
        ],
        // SignInController: index
        "/signIn" => [
            "controller" => "App\Controller\Main\SignInController",                
            "action" => "",
            "logged" => false
        ],
        // LogUotController: index
        "/logUot" => [
            "controller" => "App\Controller\Main\LogUotController",                
            "action" => "",
            "logged" => false
        ],
        // Account: index
        "/account" => [
            "controller" => "App\Controller\Main\AccountController",                
            "action" => "",
            "logged" => true
        ],
        // PersonalSettings: index
        "/settings" => [
            "controller" => "App\Controller\Main\Settings\SettingsAccountController",                
            "action" => "",
            "logged" => true
        ],
        // NbackSettings: index
        "/settings/nback" => [
            "controller" => "App\Controller\Main\Settings\SettingsNbackController",                
            "action" => "",
            "logged" => false
        ],
        // API
        "/api/authenticate" => [
            "controller" => "App\Controller\AuthenticateController",                
            "action" => "",
            "logged" => false
        ],
        // NBACK: index
        "/nback" => [
            "controller" => "App\Controller\Nback\NBackSessionController",                
            "action" => "",
            "logged" => false
        ],
        // NBACK: feedback
        "/nback/feedback" => [
            "controller" => "App\Controller\Nback\NBackSaveController",                
            "action" => "feedback",
            "logged" => false
        ],
        // Documents
        "/documents" => [
            "controller" => "App\Controller\DocumentsController",                
            "action" => "",
            "logged" => false
        ],
        "databaseError" => [
            "controller" => "App\Controller\Errors\DatabaseErrorController",
            "action" => "",
            "logged" => false
        ]
    ],
    "POST" => [
        "/nback" => [
            "controller" => "App\Controller\Nback\NBackSaveController",                
            "action" => "",
            "logged" => false
        ],
        // SignUpController: submit
        "/signUp/submit" => [
            "controller" => "App\Controller\Main\SignUpController",                
            "action" => "submit",
            "logged" => false
        ],
        // SignInController: submit
        "/signIn/submit" => [
            "controller" => "App\Controller\Main\SignInController",                
            "action" => "submit",
            "logged" => false
        ],
        // PersonalSettings: personalUpdate
        "/settings/personalUpdate" => [
            "controller" => "App\Controller\Main\Settings\SettingsAccountController",                
            "action" => "personalUpdate",
            "logged" => true
        ],
        // PersonalSettings: passwordUpdate
        "/settings/passwordUpdate" => [
            "controller" => "App\Controller\Main\Settings\SettingsAccountController",                
            "action" => "passwordUpdate",
            "logged" => true
        ],
        // PersonalSettings: imageUpdate
        "/settings/imageUpdate" => [
            "controller" => "App\Controller\Main\Settings\SettingsAccountController",                
            "action" => "imageUpdate",
            "logged" => true
        ],
        // NbackSettings: submit
        "/settings/nbackAnonim" => [
            "controller" => "App\Controller\Main\Settings\SettingsNbackController",                
            "action" => "updateAnonim",
            "logged" => false
        ],
        // NbackSettings: submit
        "/settings/nback" => [
            "controller" => "App\Controller\Main\Settings\SettingsNbackController",                
            "action" => "updateUser",
            "logged" => true
        ],        
        // Nback: submit
        "/nback/submit" => [
            "controller" => "App\Controller\NBackController",                
            "action" => "update",
            "logged" => false
        ],
        "databaseError" => [
            "controller" => "App\Controller\Errors\DatabaseError",                
            "action" => "",
            "logged" => false
        ]       
    ]         
];