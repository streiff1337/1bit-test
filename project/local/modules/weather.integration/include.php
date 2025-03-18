<?php
use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'weather.integration',
    [
        'Weather\\Integration\\SettingsForm' => 'lib/SettingsForm.php',
        'Weather\\Integration\\WeatherDataAgent' => 'lib/WeatherDataAgent.php',
        'Weather\\Integration\\WeatherApiService' => 'lib/WeatherApiService.php',
    ]
);
