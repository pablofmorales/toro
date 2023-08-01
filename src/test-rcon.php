<?php

use Knik\GRcon\GRcon;
use Knik\GRcon\Protocols\RustAdapter;

include 'vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable('.');
$dotenv->load();

$adapter = new RustAdapter([
    'host' => $_ENV['RCON_HOST'],
    'port' => $_ENV['RCON_PORT'],
    'password' => $_ENV['RCON_PASS']
]);

$rcon = new GRcon($adapter);

$rcon->globalMessage("TESTTTTT");
var_dump($rcon->execute('jet'));
var_dump($rcon->execute('o.reload BetterChat'));
//var_dump( $rcon->execute('restart "Restart diario"'));
