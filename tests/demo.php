<?php

require __DIR__ . '/../vendor/autoload.php';
// require __DIR__ . '/../init.php';

$config = array(
    'host' => '127.0.0.1',
    'port' => 6379,
    'password' => '123456',
    'db' => 1,
    'key_name' => 'test_queue'
);
$queue = new Testlin\Queue\Queue('redis', $config);

// $data = 'abc';
$data = [1=>'one','two','4'=>'4'];
$queue->push($data); // list set
print_r($queue->push($data, 3)); // hash set

// print_r($queue->status());