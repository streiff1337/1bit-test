<?php

IncludeModuleLangFile(__FILE__);
global $DOCUMENT_ROOT, $APPLICATION;

if (class_exists("weather_integration"))
    return;

class weather_integration extends CModule
{
    public $MODULE_ID = "weather.integration";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;
    public array $errors = [];
    private const AGENT_STRING = '\\Weather\\Integration\\WeatherDataAgent::execute();';

    public function __construct()
    {
        $this->setModuleVersion();
        $this->setModuleDescription();
    }

    public function InstallDB(): bool
    {
        global $DB;
        if ($DB->TableExists('b_weather_data')) {
            return true;
        }
        $sqlFilePath = __DIR__ . '/db/mysql/install.sql';
        if (!file_exists($sqlFilePath)) {
            $this->errors[] = "SQL-файл не найден: {$sqlFilePath}";
            return false;
        }
        $errors = $DB->RunSQLBatch($sqlFilePath);
        if ($errors !== false) {
            $this->errors = array_merge($this->errors, $errors);
            return false;
        }
        return true;
    }

    public function UnInstallDB(): bool
    {
        global $DB;
        $DB->Query("DROP TABLE IF EXISTS b_weather_data");
        return true;
    }

    public function InstallFiles(): bool
    {
        $this->copyFiles(
            __DIR__ . "/admin",
            "/bitrix/admin"
        );

        $this->copyFiles(
            __DIR__ . "/components",
            "/bitrix/components"
        );

        return true;
    }

    public function UnInstallFiles(): bool
    {
        $this->deleteFiles(
            __DIR__ . "/admin",
            "/bitrix/admin"
        );
        if (is_dir($_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/" . $this->MODULE_ID)) {
            $this->deleteFilesEx("/bitrix/components/" . $this->MODULE_ID);
        }
        return true;
    }

    public function InstallEvents(): bool
    {
        $this->installAgents();
        return true;
    }

    public function UnInstallEvents(): bool
    {
        $this->unInstallAgents();
        COption::RemoveOption($this->MODULE_ID);
        return true;
    }

    public function DoInstall(): void
    {
        global $APPLICATION;
        if ($this->InstallDB()) {
            $this->InstallEvents();
            $this->InstallFiles();
            RegisterModule($this->MODULE_ID);
        } else {
            $APPLICATION->ThrowException(implode("<br>", $this->errors));
        }
    }

    public function DoUninstall(): void
    {
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        $this->UnInstallDB();
        UnRegisterModule($this->MODULE_ID);
    }

    private function setModuleVersion(): void
    {
        $arModuleVersion = [];
        include(__DIR__ . "/version.php");
        if (is_array($arModuleVersion) && isset($arModuleVersion["VERSION"])) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
    }

    private function setModuleDescription(): void
    {
        $this->MODULE_NAME = GetMessage("NAME");
        $this->MODULE_DESCRIPTION = GetMessage("WEATHER_MODULE_DESCRIPTION");
        $this->PARTNER_NAME = GetMessage("PARTNER_NAME");
        $this->PARTNER_URI = GetMessage("PARTNER_URI");
    }

    private function installAgents(): void
    {
        CAgent::AddAgent(
            self::AGENT_STRING,
            $this->MODULE_ID,
            "N",
            7200
        );
    }

    private function unInstallAgents(): void
    {
        CAgent::RemoveAgent(self::AGENT_STRING, $this->MODULE_ID);
    }

    private function copyFiles(string $sourcePath, string $targetPath): void
    {
        CopyDirFiles(
            $sourcePath,
            $_SERVER["DOCUMENT_ROOT"] . $targetPath,
            true,
            true
        );
    }

    private function deleteFiles(string $sourcePath, string $targetPath): void
    {
        DeleteDirFiles(
            $sourcePath,
            $_SERVER["DOCUMENT_ROOT"] . $targetPath
        );
    }

    private function deleteFilesEx(string $path): void
    {
        DeleteDirFilesEx($path);
    }
}
