<?php
namespace Bot\Chats;

use App\Models\User;
use Bot\Keyboards\Keyboard;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class FullNameChat extends Conversation
{
    use Keyboard;

    public $phone_number;

    protected function getSerializableAttributes(): array
    {
        return [
            'phone_number' => $this->phone_number,
        ];
    }

    public function start(Nutgram $bot)
    {
        sendMessage($bot, "Please send your phone number please:", 'html', $this->phone_number_key());

        $this->next('phone_number');
    }

    public function phone_number(Nutgram $bot)
    {
        if ($bot->message()->contact) {
            if ($bot->message()->contact->user_id == $bot->chatId()) {
                sendMessage($bot, ".", 'html', removeKeyboard())->delete();
                sendMessage($bot, "Please send your full name:");

                $this->phone_number = str_replace('+', '', $bot->message()->contact->phone_number);

                $this->next('full_name');
            } else {
                sendMessage($bot, "Please send your own number:", 'html', $this->phone_number_key());
            }
        }
    }

    public function full_name(Nutgram $bot)
    {
        if ($bot->message()->text) {
            $user = User::where('user_id', $bot->chatId())->first();

            $user->update([
                'phone_number' => $this->phone_number,
                'full_name'    => $bot->message()->text,
            ]);

            sendMessage($bot, "Menu:", 'html', $this->categories_key());

            $this->end();
        }
    }
}
