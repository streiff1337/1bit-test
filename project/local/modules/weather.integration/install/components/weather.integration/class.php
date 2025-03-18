<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Query\Result;
use Bitrix\Main\SystemException;
use Weather\Integration\WeatherTable;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ArgumentException;

Loc::loadMessages(__FILE__);


class WeatherIntegrationComponent extends CBitrixComponent
{
    /**
     * Executes the component logic.
     *
     * This method checks if the required module is installed, retrieves weather data from the database,
     * and passes the data to the template for rendering.
     *
     * @return void
     */
    public function executeComponent(): void
    {
        try {
            $this->checkModules();
            $weatherData = $this->getWeatherData();
            $this->arResult['WEATHER_DATA'] = $weatherData;
            $this->includeComponentTemplate();
        } catch (Exception $exception) {
            ShowError($exception->getMessage());
        }

    }

    /**
     * Checks if the iblock module is installed.
     *
     * @throws LoaderException If the module is not found.
     */
    protected function checkModules(): void
    {
        if (!Loader::includeModule('weather.integration')) {
            throw new LoaderException(Loc::getMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        }
    }

    /**
     * Retrieves the latest weather data from the database.
     * @return array The weather data or an empty array if no data is found.
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getWeatherData(): array
    {
        $result = [];

        if ($row = $this->getWeatherQuery()->fetch()) {
            $result = [
                'CITY' => $row['CITY'],
                'TEMPERATURE' => $row['TEMPERATURE'],
                'HUMIDITY' => $row['HUMIDITY'],
                'TIMESTAMP' => $row['TIMESTAMP']->toString(),
            ];
        }

        return $result;
    }

    /**
     * @return Result The weather query
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getWeatherQuery(): Result
    {
        return WeatherTable::getList([
            'select' => ['CITY', 'TEMPERATURE', 'HUMIDITY', 'TIMESTAMP'],
            'order' => ['TIMESTAMP' => 'DESC'],
            'limit' => 1,
        ]);
    }
}
