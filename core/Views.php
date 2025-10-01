<?php
class Views {
    public function getView($controller, $view, $data = "") {
        // Handle the data parameter if provided
        if (!empty($data) && is_array($data)) {
            extract($data);
        }

        // Extract the controller name from the path (e.g., 'admin/login' -> 'login')
        $controllerName = basename($controller);

        // Construct the view path
        $viewPath = __DIR__ . "/../admin/views/" . $controllerName . "/" . $view . ".php";

        // Check if the view file exists
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            // Fallback error message
            echo "Error: View file not found - " . $viewPath;
        }
    }
}
?>