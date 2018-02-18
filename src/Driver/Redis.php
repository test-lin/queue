<?php

namespace Testlin\Queue\Driver;

use Testlin\Queue\Driver\QueueInterface;

class Redis implements QueueInterface
{
    protected $key_name;
    protected $redis;
    protected $pop_count = 0;
    protected $push_count = 0;

    public function __construct($config)
    {
        $this->redis = new \Redis();
        $this->redis->connect($config['host'], $config['port']);

        if (isset($config['password']) && $config['password']) {
            $this->redis->auth($config['password']);
        }

        if (isset($config['db']) && $config['db']) {
            $this->redis->select($config['db']);
        }

        $this->key_name = isset($config['key_name']) ? $config['key_name'] : 'queue_list';
    }

    public function pop($id = null)
    {
        $this->pop_count ++;
        if ($id) {
            $data = $this->redis->hGet($this->key_name.'hash', $id);
        } else {
            $data = $this->redis->lpop($this->key_name);
        }
        if ($data) {
            $data = json_decode($data, true);
            if ($id) {
                $this->redis->hDel($this->key_name.'hash', $id);
            }
            return $data;
        } else {
            return false;
        }
    }

    public function push($data, $id = null)
    {
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);

        try {
            $this->push_count ++;
            if ($id) {
                return $this->redis->hSet($this->key_name.'hash', $id, $data);
            } else {
                return $this->redis->rpush($this->key_name, $data);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function status()
    {
        $info = array(
            'pop_count' => $this->pop_count,
            'push_count'=> $this->push_count,
            'list_count'=> $this->redis->llen($this->key_name),
            'hash_count'=> $this->redis->hLen($this->key_name.'hash')
        );
        return json_encode($info);
    }

    public function clear()
    {
        $this->pop_count = 0;
        $this->push_count = 0;
        $this->redis->delete($this->key_name);
        $this->redis->delete($this->key_name.'hash');
    }
}