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
        
        if ($modelAndView->viewName['view'] != '')
            require_once APPLICATION.'Templates/_layout.php';
        else
        {
            (new JsonRenderer())->Emit($modelAndView->model);
        }            

        return ob_get_clean();
    }
}