<?php

namespace Bot\Handlers;

use Bot\Keyboards\Keyboard;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;

class MainHandler
{
    use Keyboard;

    public function start(Nutgram $bot)
    {
        sendMessage($bot, "Menu:", 'html', $this->categories_key());
    }

    public function back(Nutgram $bot)
    {
        $bot->message()->delete();
        $bot->answerCallbackQuery();
        
        $this->start($bot);
    }

    public function category(Nutgram $bot, $index)
    {
        editMessageText($bot, "Subcategories:", 'html', $this->subcategories_key($index));
        $bot->answerCallbackQuery();
    }

    public function subcategory(Nutgram $bot, $category, $index)
    {
        editMessageText($bot, "Products", 'html', $this->products_key($category, $index));
        $bot->answerCallbackQuery();
    }

    public function product(Nutgram $bot, $category, $subcategory, $product)
    {
        $bot->message()->delete();

        $json = json_decode(file_get_contents(resource_path('menu.json')), true);
        $json = $json[$category]['subcategories'];
        $json = $json[$subcategory]['products'];
        $json = $json[$product];

        $image = InputFile::make(resource_path($json['img']));
        $price = number_format($json['price'], 0, ',', ' ');

        sendPhoto($bot, $image, "<b>{$json['name']}</b>\nPrice: {$price} UZS", 'html', $this->back_key());
        
        $bot->answerCallbackQuery();
    }
}