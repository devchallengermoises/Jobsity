<?php

namespace App\Domain\Queue\Service;

use App\Domain\Mail\Service\MailService;
use PhpAmqpLib\Channel\AMQPChannel;
use Psr\Log\LoggerInterface;

/**
 * Consumes messages from the email queue and sends emails using the MailService.
 *
 * This class is designed for CLI/worker usage and logs its progress using a PSR-3 logger.
 */
class Consumer
{
    private AMQPChannel $channel;
    private MailService $mailService;
    private LoggerInterface $logger;

    /**
     * Consumer constructor.
     *
     * @param AMQPChannel $channel
     * @param MailService $mailService
     * @param LoggerInterface $logger
     */
    public function __construct(
        AMQPChannel $channel,
        MailService $mailService,
        LoggerInterface $logger
    ) {
        $this->channel = $channel;
        $this->mailService = $mailService;
        $this->logger = $logger;
    }

    /**
     * Start listening to the queue and process messages.
     *
     * @return void
     */
    public function listen(): void
    {
        $this->logger->info("Started");

        $this->channel->queue_declare('email', false, true, false, false);
        $this->channel->basic_qos(null, 1, null);

        $this->channel->basic_consume(
            'email',
            '',
            false,
            false,
            false,
            false,
            function ($message) {
                $this->logger->info("Consuming message: {$message->body}");
                $decodedMessage = json_decode($message->body, true);
                $this->mailService->sendMessage($decodedMessage['email'], $decodedMessage['message']);
                $channel = $message->delivery_info['channel'];
                $channel->basic_ack($message->delivery_info['delivery_tag']);
            }
        );

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
        $this->logger->info("Done consuming messages!");
        $this->channel->close();
    }
}
