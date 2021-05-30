<?php

use Leads\Settings;
use Leads\StatisticBot;

require_once '/Users/svyatoslav/Hosts/localhost/public_html/autoload.php';

    try {
        $bot = new StatisticBot(Settings::getBotToken());
        $bot->runBot();
    } catch (Throwable $ex) {
        $ex->getMessage();
    }
?>

