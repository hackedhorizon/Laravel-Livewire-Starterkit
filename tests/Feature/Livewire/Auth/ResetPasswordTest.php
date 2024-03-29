<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
