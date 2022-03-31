<?php

namespace App\Commands;

use App\Services\Status\UserStatus;

class Feedback extends BaseCommand
{

    function processCommand($param = false)
    {
        if ($this->user->status === UserStatus::FEEDBACK) {
            $this->user->feedbacks()->create([
                'text' => $this->update->getMessage()->getText(),
            ]);

            $this->getBot()->sendMessage($this->user->chat_id, $this->text['message_sent']);
            $this->triggerCommand(MainMenu::class);
        } else {
            $this->user->update([
                'status' => UserStatus::FEEDBACK,
            ]);

            $this->sendMessageWithBackButton($this->text['pre_send_feedback']);
        }
    }

}
