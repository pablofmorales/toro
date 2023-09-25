<?php

namespace Discord\Toro\Rcon;

use WebSocket\BadOpcodeException;
use WebSocket\Client as WebSocketClient;

class Client
{
    /**
     * @var WebSocketClient
     */
    private WebSocketClient $client;

    /**
     * @var array
     */
    private array $response = [];

    public const LAST_INDEX = 1001;

    /**
     * @param string $ip
     * @param int $port
     * @param string $password
     */
    public function __construct(string $ip, int $port, string $password)
    {
        $this->client = new WebSocketClient("ws://{$ip}:{$port}/{$password}");
    }

    /**
     * @param string $command
     * @param int $identifier
     * @param string $name
     * @return array
     * @throws BadOpcodeException
     */
    public function command(
        string $command,
        int $identifier = self::LAST_INDEX,
        string $name = "WebRcon"): array
    {

        $this->client->send(
            json_encode([
            'Identifier' => $identifier,
            'Message' => $command,
            'Name' => $name,
            ])
        );

        return json_decode($this->client->receive(), true);

    }

}