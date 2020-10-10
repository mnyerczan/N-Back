<?php

final class ViewRenderer
{
    public function render(ModelAndView $modelAndView)
    {
        $models = $modelAndView->model;
        $views  = $modelAndView->viewName;

        extract($models);
        extract($views);
        
        ob_clean();
        ob_start();

        // Ha nincs View név megadva, Json-ban renderel az oldal.
        if ($modelAndView->viewName['view'] != '')
            require_once APPLICATION."Templates/{$modelAndView->viewName['layout']}/_layout.php";
        else
        {
            echo (new JsonRenderer())->Emit($modelAndView->model);
        }            

        return ob_get_clean();
    }
}