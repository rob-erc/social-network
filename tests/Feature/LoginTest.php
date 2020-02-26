<?php

namespace Tests\Feature;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    //use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRouteExists()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function testInvalidCredentialsLogin()
    {
        $this->followingRedirects()
            ->from('/login')
            ->post('/login', [
                'email'=> 'test@test.com',
                'password'=>'123456'
            ])
            ->assertOk();

//        $response->assertSeeText('The email field is required.');
//        $response->assertSeeText('The password field is required.');
    }

    public function testInvalidLogin()
    {
        $this->followingRedirects()
            ->from('/login')
            ->post('/login')
            ->assertOk();
//        $response->assertSeeText('The email field is required.');
//        $response->assertSeeText('The password field is required.');
    }

    public function testLogin()
    {
        $password = 'codelex123';
        $user = factory(User::class)->create([
            'password' => bcrypt($password)
        ]);

        $this->followingRedirects()
            ->from('/login')
            ->post('/login', [
                'email'=> $user->email,
                'password'=>$password
            ])
            ->assertOk();

        $this->assertTrue(auth()->check());
    }

    public function testRedirectIfAuthenticated()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->get('/login');

        $response->assertStatus(302);
    }

    
}
