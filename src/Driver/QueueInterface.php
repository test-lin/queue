<?php

namespace Testlin\Queue\Driver;

interface QueueInterface
{
    public function pop($id = null);

    public function push($data, $id = null);

    public function getHash($id);

    public function status();

    public function clear();
}
