<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/weather.integration/install/index.php';
$module = new weather_integration();
try {
    $module->DoUninstall();
} catch (Exception $e) {
    ShowError($e->getMessage());
}