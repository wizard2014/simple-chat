<?php

namespace App;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Chat implements MessageComponentInterface
{
    use ChatEventsTrait;

    protected $clients = [];

    protected $users = [];

    public function onOpen(ConnectionInterface $connection)
    {
        $this->clients[$connection->resourceId] = $connection;
    }

    public function onClose(ConnectionInterface $connection)
    {
        foreach ($this->clients as $client) {
            $client->send(json_encode([
                'event' => 'left',
                'data'  => [
                    'user' => $this->users[$connection->resourceId]
                ]
            ]));
        }

        unset($this->clients[$connection->resourceId]);
    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        $connection->close();
    }

    public function onMessage(ConnectionInterface $connection, $message)
    {
        $payload = json_decode($message);

        if (method_exists($this, $method = 'handle' . ucfirst($payload->event))) {
            $this->{$method}($connection, $payload);
        }
    }
}
