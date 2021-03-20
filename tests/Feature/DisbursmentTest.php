<?php

namespace Tests\Feature;

use App\Models\Disbursment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DisbursmentTest extends TestCase
{
    use RefreshDatabase;

    private $disbursment;

    public function setUp(): void
    {
        parent::setUp();

        $this->disbursment = Disbursment::factory()->create();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->get(route('disbursments.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'data' => [
                    '*' => 
                    [
                        'id',
                        'transaction_id',
                        'amount',
                        'status'
                    ]
                ],
                'status',
                'message',
                'errors'
            ]
        );
    }

    public function test_create_new()
    {
        $data = [
            'amount' => 200000,
            'account_number' => '12345678',
            'bank_code' => 'BNA',
            'remark' => 'test'
        ];

        $response = $this->post(route('disbursments.store'), $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(
            [
                'data' => [
                    'id',
                    'transaction_id',
                    'amount',
                    'status'
                ],
                'status',
                'message',
                'errors'
            ]
        );
    }
}
