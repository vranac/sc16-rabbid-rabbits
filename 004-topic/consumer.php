<?php

use PhpAmqpLib\Message\AMQPMessage;

/**
 * @var \PhpAmqpLib\Channel\AMQPChannel $channel
 */
$channel = require __DIR__ . '/../channel.php';

$handler = function(AMQPMessage $message) use ($channel) {
    print $message->body . PHP_EOL;;
};

$q = $channel->queue_declare(
    ''
);
$queue_name = $q[0];

// declare the exchange (topic this time)
$channel->exchange_declare(
    'test_topic_exchange',
    'topic'
);

$binding_key = $argv[1];
print "Binding queue $queue_name to $binding_key" . PHP_EOL;
$channel->queue_bind($queue_name, 'test_topic_exchange', $binding_key);

$channel->basic_consume(
    $queue_name,
    'worker' . getmypid(),
    false,
    true,
    false,
    false,
    $handler
);

while (count($channel->callbacks)) {
    $channel->wait();
}
