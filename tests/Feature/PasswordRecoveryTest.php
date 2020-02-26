<?php

namespace Tests\Feature;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\User;

class PasswordRecoveryTest extends TestCase
{
    public function testRouteExists()
    {
        $response = $this->get('/password/reset');

        $response->assertStatus(200);
    }

    public function testRequiredEmail()
    {
        $this->from('/password/reset')
            ->post('/password/email')
            ->assertStatus(302)
            ->assertSessionHasErrors([
                'email'=>'The email field is required.']);
    }

    public function testRequiredEmailView()
    {
        $this->followingRedirects()
            ->from('/password/reset')
            ->post('/password/email')
            ->assertOk();
            //->assertSeeText('The email field is required.');
    }

    public function testInvalidEmail()
    {
        $response = $this->from('/password/reset')
            ->post('/password/email', ['email'=>'invalid_email'])
            ->assertStatus(302)
            ->assertSessionHasErrors(['email'=>'The email must be a valid email address.']);

        //$response->dumpSession();
    }

    public function testEmailNotInDb()
    {
        $response = $this->followingRedirects()
            ->from('/password/reset')
            ->post('/password/email', ['email'=>'test@test.lv'])
            ->assertOk()
            ->assertSeeText(e("We can't find a user with that e-mail address."));
        //$response->dumpSession();
    }

    public function testPasswordReset()
    {
        $response = $this->followingRedirects()
            ->from('/password/reset')
            ->post('/password/email', ['email'=>'john.doe@yahoo.com'])
            ->assertOk()
            ->assertSeeText(e("We have e-mailed your password reset link!"));
        //$response->dumpSession();
    }

    public function testForgotPassword()
    {
        Notification::fake();

        $user = factory(User::class)->create();

        $this->followingRedirects()
            ->from('/password/reset')
            ->post('/password/email', ['email'=>$user->email])
            ->assertOk()
            ->assertSeeText(e("We have e-mailed your password reset link!"));

        $this->assertDatabaseHas('password_resets', [
            'email' => $user->email
        ]);

        Notification::assertSentTo([$user], ResetPassword::class);
    }
}
