<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class SessionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_login_and_get_tokens()
    {
        $user = User::factory()->create(['password' => bcrypt('123456')]);

        $response = $this->postJson('/api/session', [
            'email' => $user->email,
            'password' => '123456',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'access_token',
                         'refresh_token',
                     ],
                 ]);
    }

    /** @test */
    public function it_can_refresh_access_token()
    {
      $user = User::factory()->create(['password' => bcrypt('123456')]);

      $response = $this->postJson('/api/session', [
          'email' => $user->email,
          'password' => '123456',
      ]);

      $token = $response['data']['refresh_token'];


      $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->putJson('/api/session');

      
        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => ['access_token']]);
    }
}
