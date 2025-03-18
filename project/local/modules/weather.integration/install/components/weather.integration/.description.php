<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = [
    "NAME" => "Последняя погода",
    "DESCRIPTION" => "Последняя погода",
    "SORT" => 10,
    "CACHE_PATH" => "Y",
    "PATH" => [
        "ID" => "weather.integration",
        "NAME" => "Интеграция с OpenWeatherMap",
    ],
];