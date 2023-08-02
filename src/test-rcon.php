<?php

use Discord\Toro\Rcon\Client as RconClient;

include 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('.');
$dotenv->load();
//
//$adapter = new RustAdapter([
//    'host' => $_ENV['RCON_HOST'],
//    'port' => $_ENV['RCON_PORT'],
//    'password' => $_ENV['RCON_PASS']
//]);

//$rcon = new GRcon($adapter);
//print_r($rcon->getPlayers());
//$rcon->execute('say "Hola todos"');
//$rcon->globalMessage("TESTTTTT");
//var_dump($rcon->execute('jet'));
//var_dump($rcon->execute('o.reload BetterChat'));
//var_dump( $rcon->execute('restart "Restart diario"'));


$rcon = new RconClient($_ENV['RCON_HOST'], $_ENV['RCON_PORT'], $_ENV['RCON_PASS']);

//$rcon->sendMessage("Recorda unirte a nuestro discrod https://discord.gg/nxNzYCHV");

print_r($rcon->getPlayers());



