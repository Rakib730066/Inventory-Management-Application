<?php

namespace App\Models;

class User
{
    public ?int $id;
    public string $name;
    public string $email;
    public string $password;
    public string $role;
    public ?string $resetToken;
    public ?string $resetExpires;
    public ?string $createdAt;

    public function __construct()
    {
        $this->id = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'employee';
        $this->resetToken = null;
        $this->resetExpires = null;
        $this->createdAt = null;
    }
}