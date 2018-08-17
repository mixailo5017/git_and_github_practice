<?php

namespace GViP\Mail;

defined('BASEPATH') or exit('No direct script access allowed');

use SparkPost\SparkPost;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

class Mail
{
	private   $apiKey,
              $subject,
              $substitutionData;
	protected $emailRecipients = [],
              $sparky,
              $bodyHtml,
              $bodyText;

    public function __construct()
    {
    	$this->apiKey = env('SPARKPOST_API_KEY');
        $this->sparky = $this->generateSparky($this->apiKey);
    }

    private function generateSparky(string $apiKey)
    {
        $httpClient = new GuzzleAdapter(new Client());
        return new SparkPost($httpClient, ['key'=> $apiKey]);
    }

    public function addRecipients(array $emailRecipients)
    {
    	foreach ($emailRecipients as $emailRecipient) {
    		if (! $emailRecipient instanceof EmailRecipient) {
    			throw new \Exception('Need to provide instances of EmailRecipient!');
    			continue;
    		}
    		$this->emailRecipients[] = $emailRecipient;

            return $this;
    	}
    }

    public function withSubstitutionData(array $substitutionData)
    {
        $this->substitutionData = $substitutionData;

        return $this;
    }

    /**
     * Returns an array of email names/addresses formatted for SparkPost
     * @return array Format documented at https://github.com/SparkPost/php-sparkpost
     */
    private function getRecipientsForSparkpost()
    {
        $recipients = [];
        foreach ($this->emailRecipients as $emailRecipient) {
            $newRecipient = [
                'address' => [
                    'name' => $emailRecipient->getName(),
                    'email' => $emailRecipient->getEmail()
                ]
            ];

            if (! empty($emailRecipient->getSubstitutionData())) {
                $newRecipient['substitution_data'] = $emailRecipient->getSubstitutionData();
            }
            
            $recipients[] = $newRecipient;
        }

        return $recipients;
    }

    public function subject(string $subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function body(string $html, string $text = null)
    {
        $this->bodyHtml = $html;
        $this->bodyText = $text ?? \Html2Text\Html2Text::convert($html);

        return $this;
    }

    public function send()
    {
        $this->sparky->setOptions(['async' => false]);
        try {
            $response = $this->sparky->transmissions->post([
                'content' => [
                    'from' => [
                        'name' => ADMIN_EMAIL_NAME,
                        'email' => ADMIN_EMAIL,
                    ],
                    'subject' => $this->subject,
                    'html' => $this->bodyHtml,
                    'text' => $this->bodyText,
                ],
                'substitution_data' => $this->substitutionData,
                'recipients' => $this->getRecipientsForSparkpost(),
                // 'cc' => [
                // ],
                // 'bcc' => [
                // ],
            ]);
            
            echo $response->getStatusCode()."\n";
            print_r($response->getBody())."\n";
        }
        catch (\Exception $e) {
            echo $e->getCode()."\n";
            echo $e->getMessage()."\n";
        }
    }


}
