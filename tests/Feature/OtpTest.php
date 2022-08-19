<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OtpTest extends TestCase
{
    /**
     * @test 
     */
    public function otp()
    {
        $response = $this->post('/api/v1/otp',[
            'phone' => '09336332901'
        ]);
        $response->assertStatus(200);
    }
}
