<?php

namespace Blog\App\Controller;


use Blog\App\Alerts\Error;
use Blog\App\Alerts\Success;

class HomeController
{
    public function indexAction()
    {
        $alert = $this->getAlert();

        require_once('../View/home.php');
    }
    private function getAlert()
    {
        if(isset($_GET['success']) || isset($_GET['error'])){
            if(isset($_GET['success'])){
                $success = new Success();
                $function = htmlspecialchars($_GET['success'], ENT_QUOTES);

                if(method_exists($success, $function) == true){
                    $successAlert = $success->$function();

                    return $successAlert;
                }
                else{
                    $error = new Error();
                    $function = "notAllowed";
                    $errorAlert = $error->$function();

                    return $errorAlert;
                }

            }
            else{
                $error = new Error();
                $function = htmlspecialchars($_GET['error'], ENT_QUOTES);

                if(method_exists($error, $function) == true){
                    $errorAlert = $error->$function();
                    return $errorAlert;
                }
                else{
                    $function = "notAllowed";
                    $errorAlert = $error->$function();

                    return $errorAlert;
                }

            }



        }
    }
}