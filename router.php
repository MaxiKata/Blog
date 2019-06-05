<?php

namespace Blog;

class Router
{
    // Default constants
    const DEFAULT_PATH        = 'Blog\App\Controller\\';
    const DEFAULT_CONTROLLER  = 'HomeController';
    const DEFAULT_ACTION      = 'indexAction';

    // Default properties
    protected $page           = null;
    protected $controller     = self::DEFAULT_CONTROLLER;
    protected $action         = self::DEFAULT_ACTION;


    public function __construct()
    {
        // Parses the request query
        $this->parseUrl();

        // Sets the controller
        $this->setController();

        // Sets the action method
        $this->setAction();
    }

    public function parseUrl()
    {

        if(!isset($_GET['access'])){
            $_GET['access'] = 'home';
        }

        $this->page = $_GET['access'];

        // Cuts this page value with the exclamation point
        $access = explode('!', $this->page);

        // Attributes the first access string to this controller
        $this->controller = $access[0];

        // If set, attributes the second access string to the current action method
        // if not set, attributes the string index
        $this->action = count($access) == 1 ? 'index' : $access[1];

    }

    public function setController()
    {
        // Constructs the current controller
        $this->controller = ucfirst(strtolower($this->controller)) . 'Controller';

        // Constructs the current controller with the default path
        $this->controller = self::DEFAULT_PATH . $this->controller;

        // Checks if the current controller is an existing class
        if (!class_exists($this->controller)) {

            // Attributes the default path & controller to the current controller
            $this->controller = self::DEFAULT_PATH . self::DEFAULT_CONTROLLER;
        }
    }

    public function setAction()
    {
        // Constructs the current action method
        $this->action = strtolower($this->action) . 'Action';

        // Checks if the current action method exists in the current controller
        if (!method_exists($this->controller, $this->action)) {

            // Attributes the default action method to the current action method
            $this->action = self::DEFAULT_ACTION;
        }
    }

    public function run()
    {
        // Creates the current controller instance
        $this->controller = new $this->controller();
        // Calls the action method on the controller
        $response = call_user_func([$this->controller, $this->action]);
        // Shows the response
        return $response;
    }
}



