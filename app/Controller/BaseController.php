<?php

namespace Controller;

Class BaseController
{
    public function render($template, $params = [])
    {
        return \App::$twig->render($template.'.html.twig', $params);
    }
}
