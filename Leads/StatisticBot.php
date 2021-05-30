<?php

namespace Leads;

use Exception;
use TelegramBot\Api\Types\Update;

class StatisticBot extends Bot
{
    public $grouping = 'hour';
    public $user_token;
    public $offset = 0;

    public const START = 'start';
    public const START_ANSWER = 'Добро пожаловать!';
    public const HELP = 'help';
    public const HELP_ANSWER = 'Команды:
    /help - вывод справки
    /getaccountstatistics - вывод информации из личного кабинета вебмастера за последние сутки';
    public const GET_STATISTICS = 'getaccountstatistics';
    public const STATISTICS_REQUEST = 'Введите ваш авторизационный токен';

    public const URL_STATISTICS = 'http://api.leads.su/webmaster/reports?';

    public const TOKEN_REGEXP = "#^[a-f0-9]{32}$#";


    public function init(): void
    {
        $webhook = $this->setWebHook();

        if ($webhook) {
            $this->newCommand(self::START, self::START_ANSWER, $this->addKeyboard());
            $this->newCommand(self::HELP, self::HELP_ANSWER);
            $this->newCommand(self::GET_STATISTICS, self::STATISTICS_REQUEST);
            $this->textHandler();
        } else {
            throw new Exception("Webhook не установлен");
        }
    }

    private function timeFrames(): array
    {
        $current_time = time();
        $start_date = date('Y-m-d h:i:s', $current_time);
        $end_date = date('Y-m-d h:i:s', $current_time - 24*60*60);

        return [
            'start_date' => $start_date,
            'end_date' => $end_date
        ];
    }

    public function getStatistics(): string
    {
        return file_get_contents(self::URL_STATISTICS . $this->requestParams());
    }

    public function requestParams(): string
    {
        $parameters = array_merge([], $this->timeFrames());
        $parameters['grouping'] = $this->grouping;
        $parameters['offset'] = $this->offset;
        $parameters['token'] = $this->user_token;

        return http_build_query($parameters);
    }

    public function textHandler()
    {
        $this->bot->on(function (Update $update) {
            $message = $update->getMessage();
            $id = $message->getChat()->getId();
            $message_text = $message->getText();
            if (!empty($this->getStatistics($message_text)['error_msg']) || !preg_match(self::TOKEN_REGEXP, $message_text)) {
                $this->bot->sendMessage($id, 'Неверный токен');
            } else {
                $this->user_token = $message_text;
                $stat = $this->getStatistics();
                $stat = json_decode($stat, true);
                if ($stat['status'] === 'success') {
                    $this->bot->sendMessage($id, "Информация за последние сутки: \n" . Utils::arrayHumanReadable($stat['data']));                    
                } else {
                    $this->bot->sendMessage($id, 'Не удалось получить информацию. Возможны проблемы с сервером');                    
                }
            }
        }, function () {
            return true;
        });
    }
}