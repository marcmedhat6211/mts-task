<?php

namespace App\Core;

class App
{
    protected string|object $controller = "home";
    protected string $method = "index";
    protected array $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();
        if (file_exists(dirname(__DIR__) . '/Controller/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        }

        $this->controller = ucfirst($this->controller);
        $className = "App\\Controller\\" . $this->controller . "Controller";
        $this->controller = new $className();

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl(): array
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }

        return [];
    }
}