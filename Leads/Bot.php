<?php

namespace Leads;

use TelegramBot\Api\Client;

class Bot
{
    public $token;
    public $bot;

    public function __construct($token)
    {
        $this->token = $token;
        $this->bot = new Client($token);

        $this->init();
    }

    public function init()
    {
        // перегружаемый метод
    }

    public function newCommand($name, $answer): void
    {
        $this->bot->command($name, function ($message) use ($answer) {
            $this->bot->sendMessage($message->getChat()->getId(), $answer);
        });
    }
}