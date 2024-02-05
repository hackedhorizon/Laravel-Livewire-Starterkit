<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Register extends Component
{
    public $name;
    public $username;
    public $email;
    public $password;

    public function store()
    {

    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
