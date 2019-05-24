<?php

namespace Blog\App\Controller;


class HomeController
{
    public function indexAction()
    {
        $alert = $this->getAlert();

        require_once('../View/home.php');
    }
}