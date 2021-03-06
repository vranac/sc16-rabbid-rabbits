<?php

use PhpAmqpLib\Message\AMQPMessage;

/**
 * @var \PhpAmqpLib\Channel\AMQPChannel $channel
 */
$channel = require __DIR__ . '/../channel.php';

// declare the exchange (type fanout)
$channel->exchange_declare(
    'test_fanout_exchange',
    'fanout'
);

$message = new AMQPMessage('Hello, Web Summer Camp 16 workshop!!');

$channel->basic_publish(
    $message,
    'test_fanout_exchange' // exchange name - name of the exchange we just made
    //'test_queue' // notice there is no queue name now!
);
