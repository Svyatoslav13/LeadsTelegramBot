<?php

namespace Leads;

class Settings
{
    private const URL_NGROK = 'https://6e08201d5b78.ngrok.io';
    private const BOT_TOKEN = '1855693062:AAG408GTvqq0HPYiyCcJxi8um75gJjhEUNU';

    public static function getUrlNgrok()
    {
        return self::URL_NGROK;
    }

    public static function getBotToken()
    {
        return self::BOT_TOKEN;
    }
}