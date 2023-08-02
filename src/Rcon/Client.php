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
        $this->client->send(json_encode([
            'Identifier' => $identifier,
            'Message' => $command,
            'Name' => $name,
        ]));

        return $this->response = array_map(function ($json) {
            return json_decode($json, true);
        }, json_decode($this->client->receive(), true));
    }

    public function sendMessage(string $message) : void
    {
        $this->command("say \"{$message}\"");
    }

    /**
     * @return array
     */
    public function getPlayers(): array
    {
        $status = $this->command('status');

        print_r($status);
exit;
        if (strlen($status) <= 0) {
            return [];
        }

        $count = preg_match_all('/^'
            . '\s*(?<id>\d*)\s*'
            . '\"(?<name>.*?)\"\s*'
            . '(?<ping>\d*)\s*'
            . '(?<connected>\d*s)\s*'
            . '(?<addr>[0-9\.]*)$'
            . '/mi',
            $status,
            $matches
        );

        if ($count <= 0) {
            return [];
        }

        $players = [];

        for ($i = 0; $i < $count; $i++) {
            if ($matches['addr'][$i] != 0) {
                $ip = explode(':', $matches['addr'][$i])[0];
            } else {
                $ip = '127.0.0.1';
            }

            $players[] = [
                // Common
                'id'        => $matches['id'][$i],
                'name'      => $matches['name'][$i],
                'ping'      => $matches['ping'][$i],
                'ip'        => $ip,

                // Rust
                'time'      => $matches['connected'][$i],
                'steamid'   => $matches['id'][$i],
            ];
        }

        return $players;
    }

    /**
     * @return array
     */
    public function receive(): array
    {
        return $this->response["Message"];
    }
}