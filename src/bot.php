<?php

include 'vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;

$dotenv = Dotenv\Dotenv::createImmutable('.');
$dotenv->load();

$discord = new Discord([
    'token' => $_ENV['DISCORD_TOKEN'],
    'intents' => Intents::getDefaultIntents()
//      | Intents::MESSAGE_CONTENT, // Note: MESSAGE_CONTENT is privileged, see https://dis.gd/mcfaq
]);

$discord->on('ready', function (Discord $discord) {
    echo "Bot is ready!", PHP_EOL;

    // Listen for messages.
    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {

        if (strtolower($message->content) === '!ip') {
            $message->reply('45.235.98.248:27215');
        }

        if (strtolower($message->content) === '!ricos' ) {
            $steamWebApi = new \SteamWebApi\SteamWebApi('YP4ACBJB0CS4KS56');

            $economics = json_decode(file_get_contents('data/Economics.json'), true);

            foreach ($economics['Balances'] as $id => $value) {
                preg_match('/\d{17}/', $id, $matches, PREG_OFFSET_CAPTURE);
                if (isset($matches[0][0])) {
                    $steamId = $matches[0][0];
                    $profile = $steamWebApi->getProfile('', $steamId);
                
                    echo $profile['personaname'];
                    echo "Balance: $" . $value. PHP_EOL;
                }
            }
        }

        if (strtolower($message->content) === '!top' ) {
            
            $top = json_decode(file_get_contents('https://toro.pablomorales.me/api/players/top'), true);

            $counter = 1;
            $result = 'Top 15 jugadores ' . PHP_EOL;
            foreach ($top as $score) {
                $result .= $counter . "- " . $score['name'] . " Score: " . $score['score'] . PHP_EOL;

                $counter ++;
            }
            $message->reply($result);
        }

        
        // Note: MESSAGE_CONTENT intent must be enabled to get the content if the bot is not mentioned/DMed.
    });
});

$discord->run();
