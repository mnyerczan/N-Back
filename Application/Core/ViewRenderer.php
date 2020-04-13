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
        
        require_once APPLICATION.'Templates/_layout.php';

        return ob_get_clean();
    }
}