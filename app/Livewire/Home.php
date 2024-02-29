<?php

namespace App\Livewire;

use Livewire\Component;

class Home extends Component
{
    private string $pageTitle = '';

    public function render()
    {
        return view('livewire.home')->title($this->pageTitle);
    }

    public function mount()
    {
        $this->pageTitle = __('Home');
    }
}
