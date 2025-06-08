<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;


return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        AMQPChannel::class => function () {
            $connection = new AMQPStreamConnection(
                $_ENV['RMQ_HOST'],
                $_ENV['RMQ_PORT'],
                $_ENV['RMQ_USERNAME'],
                $_ENV['RMQ_PASSWORD'],
                $_ENV['RMQ_VHOST']);

            return $connection->channel();
        },
        Mailer::class => function() {
            $host = $_ENV['MAILER_HOST'] ?? 'mailpit';
            $port = intval($_ENV['MAILER_PORT']) ?? 1025;
            $dsn = "smtp://$host:$port";
            $transport = Transport::fromDsn($dsn);
            return new Mailer($transport);
        },
        \Symfony\Component\Mailer\MailerInterface::class => function() {
            $host = $_ENV['MAILER_HOST'] ?? 'mailpit';
            $port = intval($_ENV['MAILER_PORT']) ?? 1025;
            $dsn = "smtp://$host:$port";
            $transport = Transport::fromDsn($dsn);
            return new Mailer($transport);
        },
        LoggerInterface::class => function () {
            $logger = new Logger('worker');
            $logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
            return $logger;
        }
    ]);

};
