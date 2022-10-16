<?php

namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = [])
    {
        require_once dirname(__DIR__, 2) . "/views/{$view}.php";
    }
}