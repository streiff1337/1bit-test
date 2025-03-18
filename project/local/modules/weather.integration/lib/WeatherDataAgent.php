<?php
namespace Weather\Integration;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class WeatherDataAgent
{
    private const AGENT_STRING = '\\Weather\\Integration\\WeatherDataAgent::execute();';

    /**
     * Bitrix agent method.
     * Fetches weather data and stores it in the database.
     *
     * @return string Agent execution string for repeated execution.
     */
    public static function execute(): string
    {
        $settings = static::getSettings();
        if (!$settings) {
            static::logError(Loc::getMessage('WEATHER_AGENT_ERROR_SETTINGS_NOT_FOUND'));
            return static::AGENT_STRING;
        }

        $apiKey = $settings['API_KEY'] ?? null;
        $city   = $settings['CITY'] ?? null;
        if (!$apiKey || !$city) {
            static::logError(Loc::getMessage('WEATHER_AGENT_ERROR_API_KEY_CITY_NOT_SET'));
            return static::AGENT_STRING;
        }

        try {
            $weatherService = new WeatherApiService($apiKey, $city);
            $data = $weatherService->fetchWeatherData();
            static::processWeatherData($data, $city);
        } catch (\Exception $e) {
            static::logError(Loc::getMessage('WEATHER_AGENT_ERROR_FETCH') . ': ' . $e->getMessage());
        }

        return static::AGENT_STRING;
    }

    /**
     * Retrieves module settings from Bitrix.
     *
     * @return array|null Returns an array of settings or null if not found.
     */
    private static function getSettings(): ?array
    {
        $settingsSerialized = Option::get('weather.integration', 'settings');
        if (!$settingsSerialized) {
            return null;
        }
        return unserialize($settingsSerialized);
    }

    /**
     * Processes the received weather data and stores it in the database.
     *
     * @param array $data Weather data received from the API.
     * @param string $city City name for which the data is fetched.
     * @throws \Exception
     */
    private static function processWeatherData(array $data, string $city): void
    {
        if (!isset($data['main'])) {
            static::logError(Loc::getMessage('WEATHER_AGENT_ERROR_NO_WEATHER_DATA'));
            return;
        }

        WeatherTable::add([
            'CITY'        => $city,
            'TEMPERATURE' => $data['main']['temp'],
            'HUMIDITY'    => $data['main']['humidity'],
        ]);
    }


    /**
     * Logs an error message.
     *
     * @param string $message Error message to log.
     */
    private static function logError(string $message): void
    {
        AddMessage2Log("WeatherDataAgent ERROR: {$message}", "weather.integration");
    }
}
