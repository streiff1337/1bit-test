<?php
namespace Weather\Integration;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Context;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ArgumentOutOfRangeException;
use Exception;


class SettingsForm
{
    const MODULE_ID = 'weather.integration';
    private const API_KEY_PATTERN = '/^[a-f0-9]{32}$/i';
    private const CITY_NAME_PATTERN = '/^[\p{L}\s\-]{2,50}$/u';

    protected mixed $settings;
    protected array $errors = [];

    public function __construct()
    {
        $this->settings = unserialize(
            Option::get(self::MODULE_ID, 'settings') ?: 'a:0:{}'
        );
    }

    public function processRequest(): void
    {
        $request = Context::getCurrent()->getRequest();

        if ($request->isPost() && check_bitrix_sessid()) {
            try {
                $this->handlePost($request);
            } catch (Exception) {
                $this->errors[] = Loc::getMessage('WEATHER_INVALID_REQUEST');
            }
        }
    }

    /**
     * Setting api key and city
     * @param HttpRequest $request
     * @return void
     * @throws ArgumentOutOfRangeException
     */
    protected function handlePost(HttpRequest $request): void
    {
        $apiKey = trim($request->getPost('API_KEY'));
        $city = trim($request->getPost('CITY'));

        $this->validate($apiKey, $city);

        if (empty($this->errors)) {
            Option::set(
                self::MODULE_ID,
                'settings',
                serialize(['API_KEY' => $apiKey, 'CITY' => $city])
            );
            LocalRedirect($request->getRequestedPage());
        }
    }

    protected function validate(string $apiKey, string $city): bool
    {
        return $this->validateCityName($city) && $this->validateApiKey($apiKey);
    }

    protected function validateApiKey(string $apiKey): bool
    {
        return $this->validateField(
            $apiKey,
            self::API_KEY_PATTERN,
            32,
            32,
            'WEATHER_INVALID_API_KEY',
        );
    }

    protected function validateCityName(string $city): bool
    {
        return $this->validateField(
            $city,
            self::CITY_NAME_PATTERN,
            2,
            50,
            'WEATHER_INVALID_CITY',
        );
    }

    protected function validateField(
        string $value,
        string $pattern,
        int    $minLength,
        int    $maxLength,
        string $invalidMessageCode,
    ): bool
    {
        if (!$this->checkRequired($value)) {
            return false;
        }

        if (!$this->checkLength($value, $minLength, $maxLength, $invalidMessageCode)) {
            return false;
        }

        if (!$this->checkPattern($value, $pattern, $invalidMessageCode)) {
            return false;
        }

        return true;
    }

    protected function checkRequired(string $value): bool
    {
        if (trim($value) === '') {
            $this->addError('EMPTY_FIELD');
            return false;
        }
        return true;
    }

    protected function checkLength(string $value, int $min, int $max, string $errorCode): bool
    {
        $length = mb_strlen($value, 'UTF-8');
        if ($length < $min || $length > $max) {
            $this->addError($errorCode);
            return false;
        }
        return true;
    }

    protected function checkPattern(string $value, string $pattern, string $errorCode): bool
    {
        if (!preg_match($pattern, $value)) {
            $this->addError($errorCode);
            return false;
        }
        return true;
    }

    protected function addError(string $messageCode): void
    {
        $this->errors[] = Loc::getMessage($messageCode);
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}