<?php

namespace Tests\Feature;

//use http\Client\Curl\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Support\Facades\Password;
use App\User;

class ResetPasswordTest extends TestCase
{
    public function testRoute()
    {
        $user = factory(User::class)->create();

        $token = Password::createToken($user);

        $response = $this->get('/password/reset/' . $token);

        $response->assertStatus(200);
    }

    public function testRequiredFields()
    {
        $user = factory(User::class)->create();

        $token = Password::createToken($user);

        $this->from('/password/reset/' . $token)
            ->post('/password/reset')
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'email' => 'The email field is required.',
                'password' => 'The password field is required.'
            ]);
    }

    public function testPasswordsDontMatch()
    {
        $user = factory(User::class)->create();

        $token = Password::createToken($user);

        $this->from('/password/reset/' . $token)
            ->post('/password/reset', [
                'email' => $user->email,
                'password' => '123456',
                'password_confirmation' => '654321'
            ])
            ->assertStatus(302)
            ->assertSessionHasErrors([

                'password' => 'The password confirmation does not match.'
            ]);
    }

    public function testSuccessfulPasswordReset()
    {
        $user = factory(User::class)->create();

        $token = Password::createToken($user);

        $this->followingRedirects()
            ->from('/password/reset/' . $token)
            ->post('/password/reset', [
                'email' => $user->email,
                'password' => '123456',
                'password_confirmation' => '123456'
            ])
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
            'password' => Hash::check('123456', $user->password)
        ]);
    }
}
