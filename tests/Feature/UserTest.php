<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test register and auth.
     *
     * @return void
     */
    public function test_login_after_register()
    {
        $user = $this->getUser();

        $response = $this->actingAs($user)
          ->followingRedirects()->get('/');

        $response->assertSee('Tài khoản của bạn chưa xác thực danh tính');
    }

    public function test_login_after_register_and_verified()
    {
        $user = $this->getUser();

        $user->markPhoneAsVerified();

        $response = $this->call('POST', 'login', [
            'phone' => $user->phone,
            'password' => 'password'
        ]);

        $response->assertRedirect('home');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_view_online_post()
    {
        $user = $this->getUser();

        $user->givePermissionTo('*');
        $user->markPhoneAsVerified();

        $this->actingAs($user)->get('online')->assertOk();
    }

    public function test_user_cannot_view_a_login_form_when_authenticated()
    {
        $user = $this->getUser();

        $user->markPhoneAsVerified();

        $this->actingAs($user)->get('/login')->assertRedirect('home');
    }

    /**
     * Create user
     *
     * @return \App\Models\User
     */
    protected function getUser()
    {
        return factory(User::class)->create();
    }
}
