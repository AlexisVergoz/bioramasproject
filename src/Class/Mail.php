<?php

namespace App\Class;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = '80e5f6669a25f95877684df446b91bed';
    private $api_key_secret ='c8447ea297a7d013b256bf3843056fbe';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
        'Messages' => [
            [
                'From' => [
                    'Email' => "vgzalexis@gmail.com",
                    'Name' => "Bioramas.fr"
                ],
                'To' => [
                    [
                        'Email' => $to_email,
                        'Name' => $to_name
                    ]
                ],
                'TemplateID' => 3379861,
                'TemplateLanguage' => true,
                'Subject' => $subject,
                "Variables"=> [
                    "content"=> $content,
                ]
            ]
        ]
    ];
    $response = $mj->post(Resources::$Email, ['body' => $body]);
    $response->success();
    }
}