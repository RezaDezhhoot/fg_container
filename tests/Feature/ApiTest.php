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
    /**
     * A basic feature test example.
     *
     * @test
     */
    public function api_get_code()
    {
        $pID= 74984;
        $salt = '12$#dAe)O@c$5*2Cn#g/sV^55!wX';
        $md5 = md5($salt.$pID.$salt);
        $code = "$md5-$pID-1-25";
        $response = $this->post('/api/v1/data',[
            'phone' => '09336332901',
            'count' => 1,
            'code' => base64_encode($code),
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
        $pID= 74984;
        $salt = '12$#dAe)O@c$5*2Cn#g/sV^55!wX';
        $md5 = md5($salt.$pID.$salt);
        $code = "$md5-$pID-1-35";
        $response  = Http::accept('application/json')
            ->post('http://127.0.0.1:8000/api/v1/data',[
                'phone' => '09336332901',
                'count' => 1,
                'code' => base64_encode($code),
                'exit_price' => '1000',
                'product_title' => 'test'
            ]);

        $this->assertEquals(200,$response->status());
    }
}
