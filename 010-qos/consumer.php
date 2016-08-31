<?php

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable; // Need this back in

/**
 * @var \PhpAmqpLib\Channel\AMQPChannel $channel
 */
$channel = require __DIR__ . '/../channel.php';

// Define the exchange name and type
$exchangeName = 'test_qos_exchange';
$exchangeType = 'direct';

// Define explicit queue name
$queueName = 'test_qos_queue';

// Define binding key
$bindingKey = 'qos';

// Define worker (consumer) id
$workerId = 'worker' . getmypid();

$channel->exchange_declare(
    $exchangeName, // update name
    $exchangeType
);

$channel->queue_declare(
    $queueName,
    false,
    false,
    false,
    false, // do not auto delete queue (we want to see messages)
    false // Don't forget metadata
);

// Update exchange name here too
$channel->queue_bind($queueName, $exchangeName, $bindingKey);


$handler = function(AMQPMessage $message) use ($channel) {
    print $message->body . PHP_EOL;
    sleep(mt_rand(0,2));
    $channel->basic_ack($message->delivery_info['delivery_tag']);
};

// Prefetch 5 message at a time
//$channel->basic_qos(null, 5, null);

$channel->basic_consume(
    $queueName,
    $workerId,
    false,
    false,
    false,
    false,
    $handler
);

while (count($channel->callbacks)) {
    $channel->wait();
}
