<?php

namespace App\Domain\Mail\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    private MailerInterface $mailer;
    private string $from;

    public function __construct(MailerInterface $mailer, string $from = 'john@doe.com')
    {
        $this->mailer = $mailer;
        $this->from = $from;
    }

    public function sendMessage($email, $message)
    {
        if (is_string($message)) {
            $message = json_decode($message, true);
        }
        $date = new \DateTime;
        $body = <<<HTML
        <b>Here is the result of your search:</b><br>
        Symbol: {$message['symbol']}<br>
        Name: {$message['name']}<br>
        Open: {$message['open']}<br>
        High: {$message['high']}<br>
        Low: {$message['low']}<br>
        Date of the request: {$date->format('Y-m-d H:i')}<br>
        HTML;

        $emailMessage = (new Email())
            ->from($this->from)
            ->to($email)
            ->subject('Stock result')
            ->html($body);

        $this->mailer->send($emailMessage);
    }
}
