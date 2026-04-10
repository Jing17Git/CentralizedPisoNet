<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Machine;
use App\Models\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected $apiToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiToken = config('app.api_token');
    }

    /**
     * Test API requires authentication
     */
    public function test_api_requires_authentication(): void
    {
        $response = $this->getJson('/api/machines');
        $response->assertStatus(401);
    }

    /**
     * Test can get all machines with valid token
     */
    public function test_can_get_all_machines_with_valid_token(): void
    {
        Machine::factory()->count(3)->create();

        $response = $this->withHeader('X-API-Token', $this->apiToken)
            ->getJson('/api/machines');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'count'
            ]);
    }

    /**
     * Test can start session via API
     */
    public function test_can_start_session_via_api(): void
    {
        $machine = Machine::factory()->create(['machine_code' => 'PC-01']);

        $response = $this->withHeader('X-API-Token', $this->apiToken)
            ->postJson('/api/sessions/start', [
                'machine_code' => 'PC-01',
                'user_name' => 'Test User',
                'minutes' => 30
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'session_id',
                    'pc_unit_number',
                    'user_session_name',
                    'start_time',
                    'remaining_time',
                    'status'
                ]
            ]);

        $this->assertDatabaseHas('pc_sessions', [
            'pc_unit_number' => 1,
            'user_session_name' => 'Test User',
            'remaining_time' => 30,
            'status' => 'Active'
        ]);
    }

    /**
     * Test can record coin insert via API
     */
    public function test_can_record_coin_insert_via_api(): void
    {
        $machine = Machine::factory()->create(['machine_code' => 'PC-01']);

        $response = $this->withHeader('X-API-Token', $this->apiToken)
            ->postJson('/api/coins/insert', [
                'machine_code' => 'PC-01',
                'coins_inserted' => 2,
                'minutes_purchased' => 10,
                'amount' => 10.00
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data'
            ]);

        $this->assertDatabaseHas('coin_transactions', [
            'machine_id' => $machine->id,
            'coins_inserted' => 2,
            'minutes_purchased' => 10,
            'amount' => 10.00
        ]);
    }

    /**
     * Test machine heartbeat updates status
     */
    public function test_machine_heartbeat_updates_status(): void
    {
        $machine = Machine::factory()->create([
            'machine_code' => 'PC-01',
            'status' => 'offline'
        ]);

        $response = $this->withHeader('X-API-Token', $this->apiToken)
            ->postJson('/api/machines/PC-01/heartbeat');

        $response->assertStatus(200);

        $machine->refresh();
        $this->assertEquals('online', $machine->status);
    }

    /**
     * Test system status endpoint
     */
    public function test_system_status_endpoint(): void
    {
        $response = $this->withHeader('X-API-Token', $this->apiToken)
            ->getJson('/api/system/status');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 'online'
            ]);
    }
}
