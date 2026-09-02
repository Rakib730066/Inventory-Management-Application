<?php

namespace App\Controllers;

class HomeController
{
    public function home(array $vars = []): void
    {
        echo 'Welcome home!';
    }
}