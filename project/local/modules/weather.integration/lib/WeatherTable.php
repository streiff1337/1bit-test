<?php
namespace Weather\Integration;

use Bitrix\Main\Entity;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\DatetimeField;
class WeatherTable extends Entity\DataManager
{
    public static function getTableName(): string
    {
        return 'b_weather_data';
    }

    /**
     * @return array
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return [
            new IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
            new StringField('CITY', ['required' => true]),
            new FloatField('TEMPERATURE', ['required' => true]),
            new IntegerField('HUMIDITY', ['required' => true]),
            new DatetimeField('TIMESTAMP', ['default_value' => new DateTime()]),
        ];
    }
}