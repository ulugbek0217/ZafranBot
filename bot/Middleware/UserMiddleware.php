<?php
namespace Bot\Middleware;

use App\Models\User;
use Bot\Chats\FullNameChat;
use SergiX44\Nutgram\Nutgram;

class UserMiddleware
{
    public function __invoke(Nutgram $bot, $next)
    {
        $user = User::firstOrCreate([
            'user_id' => $bot->chatId(),
        ]);

        if (! $user->phone_number) {
            return FullNameChat::begin($bot);
        }

        $next($bot);
    }
}
