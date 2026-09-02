<?php

namespace App\Models;

class Category
{
    public ?int $id;
    public string $name;

    public function __construct()
    {
        $this->id = null;
        $this->name = '';
    }
}