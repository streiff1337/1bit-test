<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (!$USER->IsAdmin()) {
    return;
}

return [
    "parent_menu" => "global_menu_settings",
    "section" => "weather_integration",
    "sort" => 100,
    "text" => Loc::getMessage("WEATHER_MENU_TEXT"),
    "title" => Loc::getMessage("WEATHER_MENU_TITLE"),
    "url" => "weather_settings.php?lang=" . LANGUAGE_ID,
];