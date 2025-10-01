<?php
class Router {
    private $routes = [];

    public function add($route, $controllerAction) {
        $this->routes[$route] = $controllerAction;
    }

    public function dispatch($route) {
        if (array_key_exists($route, $this->routes)) {
            $controllerAction = $this->routes[$route];
            list($controller, $method) = explode('@', $controllerAction);
            
            // Include controller file
            $controllerFile = dirname(__DIR__) . '/admin/controllers/' . $controller . '.php';
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                
                // Instantiate controller and call method
                $controllerInstance = new $controller();
                if (method_exists($controllerInstance, $method)) {
                    $controllerInstance->$method();
                    return;
                }
            }
        }
        
        // Handle 404 if route not found
        header("HTTP/1.0 404 Not Found");
        echo '404 Page Not Found';
        exit;
    }
}
