<?php

namespace App;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Chat implements MessageComponentInterface
{
    protected $clients = [];

    protected $users = [];

    public function onOpen(ConnectionInterface $connection)
    {
        $this->clients[$connection->resourceId] = $connection;
    }

    public function onClose(ConnectionInterface $connection)
    {
        unset($this->clients[$connection->resourceId]);
    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        $connection->close();
    }

    public function onMessage(ConnectionInterface $connection, $message)
    {
        $payload = json_decode($message);

        $this->users[$connection->resourceId] = $payload->data->user;
    }
}
