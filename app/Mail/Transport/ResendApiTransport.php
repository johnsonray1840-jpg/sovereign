<?php

namespace App\Mail\Transport;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\MessageConverter;

class ResendApiTransport extends AbstractTransport
{
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        parent::__construct();
        $this->apiKey = $apiKey;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        $envelope = $message->getEnvelope();

        $fromAddress = $envelope->getSender()->getAddress();
        $fromName = $envelope->getSender()->getName();
        $from = $fromName ? "{$fromName} <{$fromAddress}>" : $fromAddress;

        $recipients = [];
        foreach ($envelope->getRecipients() as $recipient) {
            $recipients[] = $recipient->getAddress();
        }

        $payload = [
            'from' => $from,
            'to' => $recipients,
            'subject' => $email->getSubject(),
            'html' => $email->getHtmlBody() ?: nl2br((string) $email->getTextBody()),
        ];

        if ($text = $email->getTextBody()) {
            $payload['text'] = $text;
        }

        if ($replyTo = $email->getReplyTo()) {
            $replyToList = [];
            foreach ($replyTo as $r) {
                $replyToList[] = $r->getAddress();
            }
            if (!empty($replyToList)) {
                $payload['reply_to'] = $replyToList;
            }
        }

        // Send via Resend HTTPS REST API (standard port 443, never blocked by cloud providers)
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'User-Agent' => 'Sovereign-Mailer/1.0',
        ])->timeout(15)->post('https://api.resend.com/emails', $payload);

        if ($response->failed()) {
            $errorBody = $response->body();
            Log::error('Resend API Dispatch Error: ' . $errorBody);
            throw new Exception('Resend API Error: ' . $errorBody);
        }

        Log::info('Resend API email delivered successfully: ' . $response->body());
    }

    public function __toString(): string
    {
        return 'resend-api';
    }
}
