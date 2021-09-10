<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    // Mailjet configuration
    private $apiKey = '2c99dcd927a77a838adbe312b26f58af';
    private $apiKeySecret = '1913a813df43de9886ad6fe79f061a8f';
    private int $idDefaultTemplate = 3149457;

    /**
     * Send an email with mailjet
     * @param $toEmail
     * @param $toName
     * @param $subject
     * @param $content
     */
    public function send($toEmail, $toName, $subject, $content){

        $mj = new Client($this->apiKey, $this->apiKeySecret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "ushopp@gmx.fr",
                        'Name' => "Ushopp"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'TemplateID' => $this->idDefaultTemplate,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}