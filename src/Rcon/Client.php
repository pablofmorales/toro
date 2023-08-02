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

    public function sendMessage(string $message) : void
    {
        $this->command("say \"{$message}\"");
    }

    /**
     * @return array
     */
    public function getPlayers(): array
    {
        $status = $this->command('status')['Message'];
        $status = explode("\n", $status);

        $tmps = array_filter(array_splice($status, 6, count($status)-3));

        $players = [];
        foreach($tmps as $player) {
            $tmp = array_values(array_filter(explode(" ", $player)));

            $player = [
                'steam_id'  => $tmp[0],
                'name'      => $tmp[1],
                'ping'      => $tmp[2],
                'connected' => $tmp[3],
                'address'   => $tmp[4],
           //     'violations' => $tmp[16],
            //    'kicks'   => $tmp[28],
            ];

            if (count($tmp) === 8) {
                $player['owner'] = true;
            }

            $players[] = $player;

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