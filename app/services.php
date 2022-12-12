<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;


return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([

        Swift_Mailer::class => function() {
            $host = $_ENV['MAILER_HOST'] ?? 'smtp.mailtrap.io';
            $port = intval($_ENV['MAILER_PORT']) ?? 465;
            $username = $_ENV['MAILER_USERNAME'] ?? 'test';
            $password = $_ENV['MAILER_PASSWORD'] ?? 'test';

            $transport = (new Swift_SmtpTransport($host, $port))
                ->setUsername($username)
                ->setPassword($password)
            ;

            return new Swift_Mailer($transport);
        },
        AMQPChannel::class => function () {
            $connection = new AMQPStreamConnection(
                $_ENV['RMQ_HOST'],
                $_ENV['RMQ_PORT'],
                $_ENV['RMQ_USERNAME'],
                $_ENV['RMQ_PASSWORD'],
                $_ENV['RMQ_VHOST']);

            return $connection->channel();
        }
    ]);

};
