<?php

if (is_dir($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/weather.integration/")) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/weather.integration/admin/weather_settings.php");
}
if (is_dir($_SERVER["DOCUMENT_ROOT"] . "/local/modules/weather.integration/")) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/local/modules/weather.integration/admin/weather_settings.php");
}