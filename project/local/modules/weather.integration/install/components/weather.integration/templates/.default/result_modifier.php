<?php
if (!empty($arResult['WEATHER_DATA'])) {
    $arResult['WEATHER_DATA']['TEMPERATURE'] = round($arResult['WEATHER_DATA']['TEMPERATURE'], 1);
}