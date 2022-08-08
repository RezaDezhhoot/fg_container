<?php


namespace App\Http\Controllers\Sends;


use App\Models\Notification;
use GuzzleHttp\Client;

class SendMessages
{
    private $baseUri = 'https://ws.sms.ir/api/';
    private $apiKey = 'V-qyDXKy60ZVeni8h-HAl6Qtz2vXP4Keenc0EN6k3LQ=';
    private $username = '09931788937';
    private $password = 'faraz0671834685';
    private $lineNumber = '3000505';


    public function sendCode($code , $user)
    {
//        if (app()->environment('local')) {
//            return;
//        }

        $client = new Client();
        $query = ['apikey' => $this->apiKey,
            'pid' => 'xwxal1leox0uq1a',
            'fnum' => $this->lineNumber,
            'tnum' => $user->phone,
            'p1' => 'verification-code',
            'v1' => $code];

        $result = $client->get('http://ippanel.com:8080/',
            [
                'query' => $query,
            ]);
        return json_decode($result->getBody(), true);
    }
}
