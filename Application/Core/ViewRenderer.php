<?php
/**
 * Az osztály, ami elvégzi a kimenetre írást. 
 * A kapott modelAndView objektum elemeit kibontja és a lokálos scopon belül elérhetővé
 * teszi a frontend felé.
 * 
 * 
 * 
 */


final class ViewRenderer
{


    public function render(ModelAndView $modelAndView)
    {

        
        $models = $modelAndView->model;

        // $view = ViewParameters objektum
        $views  = $modelAndView->view;
        

        // Adatszerkezetek kicsomagolása
        extract($models);
        extract((array)$views);

      
        ob_clean();
        ob_start();

        // Ha nincs View név megadva, Json-ban renderel az oldal.
        if (!strpos($modelAndView->view->mime, "json"))
        {

            require_once APPLICATION."Templates/{$views->layout}/_layout.php";
        }
            
        else
        {
            
            echo (new JsonRenderer())->Emit($modelAndView->model);
        }            

        return ob_get_clean();
    }
}