<?php

namespace Tests\Feature;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ApiTest extends TestCase
{
    protected $code;
    public function setUp(): void
    {
        parent::setUp();
        $pID = 3969;
        $salt = '12$#dAe)O@c$5*2Cn#g/sV^55!wX';
        $md5 = md5($salt.$pID.$salt);
        $this->code = "$md5-$pID-1-25";
    }
    
    /**
     * A basic feature test example.
     *
     * @test
     */
    public function api_get_code()
    {
        $response = $this->post('/api/v1/data',[
            'phone' => '09336332901',
            'count' => 1,
            'code' => base64_encode($this->code),
            'exit_price' => '1000',
            'product_title' => 'test'
        ]);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @test
     */
    public function guzzle_http()
    {
        $response  = Http::accept('application/json')
            ->post('http://127.0.0.1:8000/api/v1/data',[
                'phone' => '09336332901',
                'count' => 1,
                'code' => base64_encode($this->code),
                'exit_price' => '1000',
                'product_title' => 'test',
                'base_id' => 1
            ]);

        $this->assertEquals(200,$response->status());
    }

    /**
     * @test
     */
    public function api_get_code_by_username_and_password()
    {
        $code = readline('code: ');
        $phone = (string)readline('phone: ');
        $response = $this->post('/api/v1/custom_data',[
            'phone' => '09336332901',
            'count' => 1,
            'code' => base64_encode($this->code),
            'exit_price' => '1000',
            'product_title' => 'test',
            'base_id' => 1,
            'admin_phone' => $phone,
            'admin_code' => $code
        ]);
        $response->assertStatus(200);
    }
}
