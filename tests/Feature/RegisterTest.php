<?php

namespace Tests\Feature;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\User;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRouteExists()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function testRedirectIfAuthenticated()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->get('/register');

        $response->assertStatus(302);
    }

    public function testRequiredFields()
    {
        $this->from('/register')
            ->post('/register')
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'name'=>'The name field is required.',
                'email'=>'The email field is required.',
                'password'=>'The password field is required.']);
//            ->assertSeeText('The name field is required.')
//            ->assertSeeText('The email field is required.')
//            ->assertSeeText('The password field is required.')
    }

    public function testInvalidEmail()
    {
        $this->from('/register')
            ->post('/register', ['email'=>'invalid_email'])
            ->assertStatus(302)
            ->assertSessionHasErrors(['email'=>'The email must be a valid email address.']);
    }

    public function testPasswordConfirms()
    {
        $this->from('/register')
            ->post('/register', ['email'=>'john@john.lv', 'name'=>'John', 'password'=>'123456', 'password_confirmation'=>'654321'])
            ->assertStatus(302)
            ->assertSessionHasErrors(['password'=>'The password confirmation does not match.']);
    }

    public function testEmailExists()
    {
        $user = factory(User::class)->create();

        $this->from('/register')
            ->post('/register', [
                'email'=>$user->email,
                'name'=>'John',
                'password'=>'123456',
                'password_confirmation'=>'123456'
            ])
            ->assertStatus(302)
            ->assertSessionHasErrors(['email'=>'The email has already been taken.']);

    }

    public function testRegister()
    {
        //Notification::fake();
        $user = factory(User::class)->create();

        $response = $this->followingRedirects()
            ->from('/register')
            ->post('/register', [
                'email'=>'john@john.lv',
                'name'=>'John',
                'surname'=>'Doe',
                'password'=>'123456',
                'password_confirmation'=>'123456'
            ])
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'email'=>'john@john.lv',
            'name'=> 'John'
        ]);

        $this->assertTrue(auth()->check());

        //Notification::assertSentTo([$user], VerifyEmail::class);
    }
}
