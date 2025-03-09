<?php

namespace Bot\Keyboards;

trait Keyboard
{
    public function phone_number_key()
    {
        return resizeKeyboard([
            ['text' => "Send number", 'request_contact' => true]
        ]);
    }

    public function back_key()
    {
        return inlineKeyboard([
            ['text' => 'Back', 'callback_data' => 'back']
        ]);
    }

    public function categories_key()
    {
        $json = json_decode(file_get_contents(resource_path('menu.json')), true);

        $buttons = [];
        foreach($json as $key => $value){
            $buttons[] = ['text' => $value['name'], 'callback_data' => "category_{$key}"];
        }

        return inlineKeyboard($buttons);
    }

    public function subcategories_key($index)
    {
        $json = json_decode(file_get_contents(resource_path('menu.json')), true);
        $json = $json[$index]['subcategories'];

        $buttons = [];
        foreach($json as $key => $value){
            $buttons[] = ['text' => $value['name'], 'callback_data' => "subcategory_{$index}_{$key}"];
        }

        $buttons[] = ['text' => 'Back', 'callback_data' => 'back'];

        return inlineKeyboard($buttons);
    }

    public function products_key($category, $index)
    {
        $json = json_decode(file_get_contents(resource_path('menu.json')), true);
        $json = $json[$category]['subcategories'];
        $json = $json[$index]['products'];

        $buttons = [];
        foreach($json as $key => $value){
            $buttons[] = ['text' => $value['name'], 'callback_data' => "product_{$category}_{$index}_{$key}"];
        }

        $buttons[] = ['text' => 'Back', 'callback_data' => 'back'];

        return inlineKeyboard($buttons);
    }
}