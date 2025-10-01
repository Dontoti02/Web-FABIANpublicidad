<?php
class Controller {
    protected $views;
    protected $model;

    public function __construct() {
        $this->views = new Views();
        $this->loadModel();
    }

    public function loadModel() {
        $model = get_class($this) . "Model";
        $ruta = __DIR__ . "/../admin/models/" . $model . ".php";
        if (file_exists($ruta)) {
            require_once $ruta;
            if (class_exists($model)) {
                $this->model = new $model();
            }
        }
    }
}
?>