<?php

namespace Testlin\Queue;

use Testlin\Queue\Driver\QueueInterface;

class Queue
{
    protected $queue;

    public function __construct(string $driver, array $config)
    {
        $driver = strtolower($driver);
        if (in_array($driver, array('redis', 'mysql', 'php')) == false) {
            throw new \Exception("not driver");
        }

        $this->queue = $this->queueConnect($driver, $config);
    }

    protected function queueConnect($driver, $config)
    {
        if (!file_exists(__DIR__ . '/Driver/' . ucfirst($driver) . '.php')) {
            throw new \Exception("db driver [$driver] is not supported.");
        }
        $gateway = __NAMESPACE__ . '\\Driver\\' . ucfirst($driver);
        return new $gateway($config);
    }

    public function push($data, $id = null)
    {
        return $this->queue->push($data, $id);
    }

    public function pop($id = null)
    {
        return $this->queue->pop($id);
    }

    public function status()
    {
        return $this->queue->status();
    }

    public function clear()
    {
        return $this->queue->clear();
    }
}