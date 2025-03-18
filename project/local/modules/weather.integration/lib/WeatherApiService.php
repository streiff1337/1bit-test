<?php

namespace Weather\Integration;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\HttpClient;
use RuntimeException;

Loc::loadMessages(__FILE__);

class WeatherApiService
{
    private string $apiKey;
    private string $city;
    private HttpClient $httpClient;
    private const WEATHER_API_URL = "https://api.openweathermap.org/data/2.5/weather";

    /**
     * WeatherApiService constructor.
     *
     * @param string $apiKey API key for OpenWeatherMap.
     * @param string $city   City name to fetch weather data for.
     */
    public function __construct(string $apiKey, string $city)
    {
        $this->apiKey = $apiKey;
        $this->city = $city;
        $this->httpClient = new HttpClient();
    }

    /**
     * Fetches weather data from OpenWeatherMap API.
     *
     * @return array Weather data response.
     * @throws RuntimeException If request fails or JSON decoding fails.
     */
    public function fetchWeatherData(): array
    {
        $url = self::WEATHER_API_URL . "?q={$this->city}&appid={$this->apiKey}&units=metric";

        $response = $this->httpClient->get($url);

        if (!$response) {
            $status = $this->httpClient->getStatus();
            throw new RuntimeException(Loc::getMessage('WEATHER_API_ERROR_HTTP_REQUEST', ['#STATUS#' => $status]));
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(Loc::getMessage('WEATHER_API_ERROR_JSON_DECODE', ['#ERROR#' => json_last_error_msg()]));
        }

        return $data;
    }
}
