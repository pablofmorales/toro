<?php

namespace Discord\Toro;

use WebSocket\BadOpcodeException;
use WebSocket\ConnectionException;

class Rust
{

    private Rcon\Client $client;
    public function __construct(Rcon\Client $client)
    {
        $this->client = $client;
    }

    public function sendMessage(string $message) : void
    {
        try {
            $this->client->command("say \"{$message}\"");
        } catch (ConnectionException $e) {
            echo "\n\n Connection Error when trying to send the message. Please try again \n\n";
        }
    }

    /**
     * @return array
     * @throws BadOpcodeException
     * Get the list of players actually connected to the server
     */
    public function getPlayers(): array
    {
        $status = $this->client->command('status')['Message'];
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

}