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
        "/?(?<controller>)/?" => [
            "controller" => "App\Controller\HomeController",                
            "action" => "",
            "logged" => false
        ],
        // SignUpController: index
        "/?(?<controller>signUp)/(?<action>form)/?" => [
            "controller" => "App\Controller\SignUpController",                
            "action" => "",
            "logged" => false
        ],
        // SignInController: index
        "/?(?<controller>signIn)/?" => [
            "controller" => "App\Controller\SignInController",                
            "action" => "",
            "logged" => false
        ],
        // LogUotController: index
        "/?(?<controller>logUot)" => [
            "controller" => "App\Controller\LogUotController",                
            "action" => "",
            "logged" => false
        ],
        // Account: index
        "/?(?<controller>account)/?" => [
            "controller" => "App\Controller\AccountController",                
            "action" => "",
            "logged" => true
        ],
        // PersonalSettings: index
        "/?(?<controller>settings)/?" => [
            "controller" => "App\Controller\SettingsAccountController",                
            "action" => "",
            "logged" => true
        ],
        // NbackSettings: index
        "/?(?<controller>settings)/(?<action>nback)/?" => [
            "controller" => "App\Controller\SettingsNbackController",                
            "action" => "",
            "logged" => true
        ],
        // API
        "/?api/(?<controller>authenticate)/?" => [
            "controller" => "App\Controller\AuthenticateController",                
            "action" => "",
            "logged" => false
        ],
        // NBACK
        "/?(?<controller>nBack)/?" => [
            "controller" => "App\Controller\NBackController",                
            "action" => "",
            "logged" => false
        ],
        // Documents
        "/?(?<controller>documents)/?" => [
            "controller" => "App\Controller\DocumentsController",                
            "action" => "",
            "logged" => false
        ],
    ],
    "POST" => [
        // SignUpController: submit
        "/?(?<controller>signUp)/(?<action>submit)/?" => [
            "controller" => "App\Controller\SignUpController",                
            "action" => "submit",
            "logged" => false
        ],
        // SignInController: submit
        "/?(?<controller>signIn)/(?<action>submit)" => [
            "controller" => "App\Controller\SignInController",                
            "action" => "submit",
            "logged" => false
        ],
        // PersonalSettings: personalUpdate
        "/?(?<controller>settings)/(?<action>personalUpdate)/?" => [
            "controller" => "App\Controller\SettingsAccountController",                
            "action" => "personalUpdate",
            "logged" => true
        ],
        // PersonalSettings: passwordUpdate
        "/?(?<controller>settings)/(?<action>passwordUpdate)/?" => [
            "controller" => "App\Controller\SettingsAccountController",                
            "action" => "passwordUpdate",
            "logged" => true
        ],
        // PersonalSettings: imageUpdate
        "/?(?<controller>settings)/(?<action>imageUpdate)/?" => [
            "controller" => "App\Controller\SettingsAccountController",                
            "action" => "imageUpdate",
            "logged" => true
        ],
        "/?(?<controller>settings)/(?<action>nback)/?" => [
            "controller" => "App\Controller\SettingsNbackController",                
            "action" => "update",
            "logged" => true
        ]            
    ]         
];