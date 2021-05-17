<?php


namespace App\Classes;


use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $apiKey = 'db37402bd329c66c664f80f37eca26cc';
    private $secretKey = 'ffa8f0cc11362ff92fafbacdbfc45604';

    public function send($toEmail, $toName, $subject, $content)
    {
        $mj = new Client($this->apiKey, $this->secretKey, true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "killian.minetto@gmail.com",
                        'Name' => "eCommerceUdemy"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName,
                        ]
                    ],
                    'TemplateID' => 2891481,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && dd($response->getData());
    }
}