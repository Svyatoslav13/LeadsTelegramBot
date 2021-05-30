<?php

namespace Leads;

use TelegramBot\Api\Client;
use TelegramBot\Api\BotApi;

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

    public function newCommand($name, $answer, $keyboard = null): void
    {
        $this->bot->command($name, function ($message) use ($answer, $keyboard) {
            if (is_null($keyboard)) {
                $this->bot->sendMessage($message->getChat()->getId(), $answer);
            } else {
                $this->bot->sendMessage($message->getChat()->getId(), $answer, null, false, null, $keyboard);
            }
        });
    }

    public function runBot()
    {
        $this->bot->run();
    }

    public function setWebHook(): string
    {
        $url = BotApi::URL_PREFIX . $this->token . "/setWebhook?url=" . urlencode(Settings::getUrlNgrok());
        $answer = file_get_contents($url);

        return json_decode($answer, true)["ok"];
    }

    public function addKeyboard()
    {
        return new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("/help", "/getaccountstatistics"))); // true for one-time keyboard
    }
}