<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Console\ConsumeQueueCommand;

$command = new ConsumeQueueCommand();
$command->run();
