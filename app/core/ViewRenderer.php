<?php
/**
 * Az osztály, ami elvégzi a kimenetre írást. 
 * A kapott modelAndView objektum elemeit kibontja és a lokálos scopon belül elérhetővé
 * teszi a frontend felé.
 * 
 * 
 * 
 */
namespace App\Core;

use App\Classes\JsonRenderer;
use App\Model\ModelAndView;

final class ViewRenderer
{
    public function render(ModelAndView $modelAndView)
    {

        $models = $modelAndView->model;
        // $view = ViewParameters objektum
        $views  = $modelAndView->view;

        unset($modelAndView);

        // Adatszerkezetek kicsomagolása
        extract($models);
        extract((array)$views);
      
        ob_clean();
        ob_start();

        // Ha a kapott mime tartalmazza a json stringet, jsonban renderel.
        if (!strpos($views->mime, "json")) {            
            unset($models);
            // Fő view struktúrákat tartalmazó Layout mappában található fájlok meghatározása.
            require_once APPLICATION."templates/layouts/{$views->layout}Layout.php";
        }
        else            
            echo (new JsonRenderer())->Emit($models);

        return ob_get_clean();
    }
}