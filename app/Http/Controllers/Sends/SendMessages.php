<?php


namespace App\Http\Controllers\Sends;


use App\Models\Notification;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class SendMessages
{
    private $baseUri = 'https://ws.sms.ir/api/';
    private $apiKey = 'V-qyDXKy60ZVeni8h-HAl6Qtz2vXP4Keenc0EN6k3LQ=';
    private $username = '09931788937';
    private $password = 'a1rp1l5o#{rB';
    private $lineNumber = '3000505';


    public function sendCode($code , $phone)
    {
        $client = new Client();

        $query = Arr::query([
            'username' => $this->username,
            'password' => $this->password,
            'from' => $this->lineNumber,
            'to' => $phone,
            'pattern_code' => "xwxal1leox0uq1a",
            'input_data' => json_encode([
                "verification-code" => $code
            ]),
        ]);

        $result = $client->post("https://ippanel.com/patterns/pattern"."?$query");
        return json_decode($result->getBody(), true);
    }
}
