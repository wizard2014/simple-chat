<?php

namespace App;

use App\Events\UserJoined;
use Ratchet\ConnectionInterface;

trait ChatEventsTrait
{
    protected function handleJoined(ConnectionInterface $connection, $payload)
    {
        $user = $payload->data->user;

        $this->users[$connection->resourceId] = $user;

        $this->broadcast(new UserJoined($user))->toAll();
    }
}
