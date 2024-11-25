<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Reminder;

class ReminderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_list_reminders()
    {
        // Create reminders for the user
        Reminder::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'api')
                         ->getJson('/api/reminders');


        $response->assertStatus(200)
                 ->assertJsonStructure([
                   'data' => [
                    'reminders' => [
                        '*' => [
                            'id',
                            'user_id',
                            'title',
                            'description',
                            'remind_at',
                            'event_at',
                            'type',
                            'status',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ],
                 ]);
    }


    /** @test */
    public function it_can_create_a_reminder()
    {
        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'remind_at' => date("Y-m-d H:i:00",strtotime(now()->addDay())),
            'event_at' => date("Y-m-d H:i:00",strtotime(now()->addDay()->addHour())),
        ];

        $response = $this->actingAs($this->user, 'api')
                         ->postJson('/api/reminders', $data);


        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => $data['title']]);

        $this->assertDatabaseHas('reminders', $data);
    }


    /** @test */

    public function it_can_show_a_reminder()
    {
        $reminder = Reminder::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'api')
                         ->getJson('/api/reminders/' . $reminder->id);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => $reminder->title]);
    }

    /** @test */

    public function it_can_update_a_reminder()
    {
        $reminder = Reminder::factory()->create(['user_id' => $this->user->id]);

        $data = ['title' => 'Updated Title'];

        $response = $this->actingAs($this->user, 'api')
                         ->putJson('/api/reminders/' . $reminder->id, $data);



        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Updated Title']);

        $this->assertDatabaseHas('reminders', $data);
    }

    /** @test */

    public function it_can_delete_a_reminder()
    {
        $reminder = Reminder::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'api')
                         ->deleteJson('/api/reminders/' . $reminder->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('reminders', ['id' => $reminder->id]);
    }

}
