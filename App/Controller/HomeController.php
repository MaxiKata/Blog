<?php

namespace Blog\App\Controller;

use Blog\App\Alerts\Error;
use Blog\App\Alerts\Success;
use Model\PostManager;

class HomeController
{
    public function indexAction()
    {
        $alert = $this->getAlert();

        $postManager = new PostManager();
        $categories = $postManager->getCategories();
        $articles = $postManager->getLastArticles();

        $table = array($alert, $categories, $articles);

        $this->useUnused($table);

        require_once '../View/home.php';
    }
    public function getAlert()
    {
        $getSuccess = filter_input(INPUT_GET, 'success', FILTER_SANITIZE_STRING);
        $getError = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);
        if(isset($getSuccess) || isset($getError)){
            if(isset($getSuccess)){
                $success = new Success();

                if(method_exists($success, $getSuccess) == true){
                    $successAlert = $success->$getSuccess();

                    return $successAlert;
                }
                else{
                    $error = new Error();
                    $getSuccess = "notAllowed";
                    $errorAlert = $error->$getSuccess();
                    return $errorAlert;
                }
            }
            else{
                $error = new Error();

                if(method_exists($error, $getError) == true){
                    $errorAlert = $error->$getError();
                    return $errorAlert;
                }
                else{
                    $getError = "notAllowed";
                    $errorAlert = $error->$getError();

                    return $errorAlert;
                }
            }
        }
    }
    public function useUnused($table)
    {
        return $table;
    }
}