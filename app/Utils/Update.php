<?php

namespace App\Utils;

class Update extends \TelegramBot\Api\Types\Update
{

    public function getDecodedCallbackQueryData(): array
    {
        return json_decode($this->getCallbackQuery()->getData(), true);
    }

    public function getCallbackQueryByKey(string $key)
    {
        return $this->getDecodedCallbackQueryData() ? $this->getDecodedCallbackQueryData()[$key] : null;
    }

}
