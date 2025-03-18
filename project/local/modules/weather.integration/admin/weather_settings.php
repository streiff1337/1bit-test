<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;


Loc::loadMessages(__FILE__);

if (!Loader::includeModule('weather.integration')) {
    return;
}

use Weather\Integration\SettingsForm;

$form = new SettingsForm();
$form->processRequest();
$APPLICATION->SetTitle(Loc::getMessage('WEATHER_SETTINGS_TITLE'));

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');


require __DIR__ . '/templates/settings_form.php';

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
