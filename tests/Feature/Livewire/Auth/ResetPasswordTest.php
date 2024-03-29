<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\ResetPassword;
use Livewire\Livewire;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(ResetPassword::class)
            ->assertStatus(200);
    }
}
