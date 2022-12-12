<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager;
use Psr\Container\ContainerInterface;
use Awurth\SlimValidation\Validator;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => true, // Should be set to false in production
            'logger' => [
                'name' => 'jobsity',
                'path' => '/var/www/logs/jobsity.log',
                'level' => Logger::DEBUG,
            ],
            'db' => [
                'driver'    => $_ENV['DB_DRIVER'],
                'host'      => $_ENV['DB_HOST'],
                'database'  => $_ENV['DB_DATABASE'],
                'username'  => $_ENV['DB_USERNAME'],
                'password'  => $_ENV['DB_PASSWORD'],
                'charset'   => $_ENV['DB_CHARSET'],
                'collation' => $_ENV['DB_COLLATION'],
                'prefix'    => $_ENV['DB_PREFIX'] ?? '',
                'port'      => $_ENV['DB_PORT']
            ]
        ],
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings')['logger'];

            $logger = new Logger($settings['name']);
            $handler = new StreamHandler($settings['path'], $settings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        "db" => function (ContainerInterface $c) {
            $settings = $c->get('settings')['db'];
            $capsule = new Manager();
            $capsule->addConnection($settings);
            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            return $capsule;
        },
        Validator::class => function () {
            return new Validator();
        },
    ]);
};
