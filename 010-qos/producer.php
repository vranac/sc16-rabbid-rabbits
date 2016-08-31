<?php

use PhpAmqpLib\Message\AMQPMessage;

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

// Create 30000 messages with differing priority
for ($i = 1; $i <= 30000; $i++) {

    $message = new AMQPMessage('Hello, Web Summer Camp 16 workshop!! [' . $i . ']');

    // Modify to "batch" publish - purely to demonstrate another feature while here
    $channel->batch_basic_publish(
        $message,
        $exchangeName, // Update exchange name
        $bindingKey
    );
}

// Call batch publish here
$channel->publish_batch();
