<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLogout()
    {
        $this->actingAs(factory(User::class)->create());

        $this->followingRedirects()
            ->from('/home')
            ->post('/logout')
            ->assertOk();

        $this->assertFalse(auth()->check());
    }


}
