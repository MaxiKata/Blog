<?php

namespace Blog\App\Controller;


class HomeController
{
    public function indexAction()
    {
        require_once('../View/home.php');
    }
}