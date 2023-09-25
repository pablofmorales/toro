<?php

use Discord\Toro\Rcon\Client as RconClient;
use Discord\Toro\Rust;

include 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('.');
$dotenv->load();

$rconClient = new RconClient(
    $_ENV['RCON_HOST'],
    $_ENV['RCON_PORT'],
    $_ENV['RCON_PASS']
);

$rust = new Rust($rconClient);
//$rust->sendMessage("Recorda unirte a nuestro discord https://discord.gg/nxNzYCHV");

$rust->sendMessage("Server recien Wipeado entra a Discord para enterarte de los cambios https://discord.gg/nxNzYCHV");

print_r($rust->getPlayers());
