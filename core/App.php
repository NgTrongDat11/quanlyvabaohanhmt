<?php
/**
 * Core App - Xử lý routing và khởi tạo controller
 */

class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // Kiểm tra controller
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerFile = ROOT_PATH . '/app/controllers/' . $controllerName . '.php';
            
            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        // Require controller
        require_once ROOT_PATH . '/app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Kiểm tra method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Lấy params
        $this->params = $url ? array_values($url) : [];

        // Gọi controller và method với params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
