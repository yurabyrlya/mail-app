<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubscriberTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Test successful subscriber creation.
     *
     * @return void
     */
    public function testSubscriberCreationSuccess()
    {
        $subscriberData = [
            'email' => 'test@example.com',
            'name' => 'John Doe',
            'country' => 'United States'
        ];

        $response = $this->postJson('api/subscribers', $subscriberData);

        $response->assertStatus(200);
    }

    /**
     * Test subscriber creation with invalid data.
     *
     * @return void
     */
    public function testSubscriberCreationValidationFailure()
    {
        $subscriberData = [
            'email' => 'invalid-email',
            'name' => '',
            'country' => ''
        ];

        $response = $this->postJson('api/subscribers', $subscriberData);

        $response->assertStatus(422);
    }
}
