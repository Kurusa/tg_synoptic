<?php

use App\Utils\Api;

require_once(__DIR__ . '/bootstrap.php');
$bot = new Api(env('TELEGRAM_BOT_TOKEN'));

$user_list = \App\Models\User::where('is_blocked', 0)->where('chat_id', 375036391)->get();
foreach ($user_list as $key => $user) {
    try {
        $bot->getUserProfilePhotos($user->chat_id);
    } catch (\TelegramBot\Api\Exception $e) {
        echo 'sdf';

        if ($e->getMessage() == 'Forbidden: bot was blocked by the user') {
            \App\Models\User::where('chat_id', $user->chat_id)->update([
                'is_blocked' => 1
            ]);
        }
    }
}