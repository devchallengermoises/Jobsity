<?php

declare(strict_types=1);

namespace App\Console;

use App\Domain\Queue\Service\Consumer;
use DI\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;

class ConsumeQueueCommand
{
    /**
     * Run the queue consumer.
     *
     * @return void
     */
    public function run(): void
    {
        // Load environment variables
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../.env');

        // Build DI container
        $containerBuilder = new ContainerBuilder();
        $services = require __DIR__ . '/../../app/services.php';
        $services($containerBuilder);
        $container = $containerBuilder->build();

        // Get dependencies
        $logger = $container->get(LoggerInterface::class);
        $amqpChannel = $container->get('PhpAmqpLib\Channel\AMQPChannel');
        $mailer = $container->get(MailerInterface::class);
        $mailServiceInstance = new \App\Domain\Mail\Service\MailService($mailer, 'john@doe.com');
        $consumer = new Consumer($amqpChannel, $mailServiceInstance, $logger);

        $logger->info('Starting queue consumer...');
        $consumer->listen();
    }
} 