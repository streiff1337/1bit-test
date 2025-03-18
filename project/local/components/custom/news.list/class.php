<?php

use Bitrix\Main\ORM\Query\Result;
use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\LoaderException;

class NewsListComponent extends CBitrixComponent
{
    const DEFAULT_ELEMENTS_COUNT = 10;
    const DEFAULT_IBLOCK_ID = 1;

    /**
     * Prepares component parameters.
     *
     * @param array $arParams Component input parameters.
     * @return array Processed parameters.
     */
    public function onPrepareComponentParams($arParams): array
    {
        $arParams['ELEMENTS_PER_PAGE'] = intval($arParams['ELEMENTS_PER_PAGE']) ?: self::DEFAULT_ELEMENTS_COUNT;
        $arParams['IBLOCK_ID'] = intval($arParams['IBLOCK_ID']) ?: self::DEFAULT_IBLOCK_ID;
        return $arParams;
    }

    /**
     * Main component execution method.
     * Validates modules, checks infoblock existence, and loads the news list.
     */
    public function executeComponent(): void
    {
        try {
            $this->checkModules();
            $this->checkIBlockExists();
            $this->arResult = $this->getNewsList();
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
        if (!Loader::includeModule('iblock')) {
            throw new LoaderException(Loc::getMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        }
    }

    /**
     * Checks if the specified infoblock exists.
     *
     * @throws Exception If the infoblock is not found.
     */
    protected function checkIBlockExists(): void
    {
        $infoBlock = IblockTable::getList([
            'select' => ['ID'],
            'filter' => ['=ID' => $this->arParams['IBLOCK_ID'], '=ACTIVE' => 'Y'],
        ])->fetch();

        if (!$infoBlock) {
            throw new Exception(Loc::getMessage("IBLOCK_NOT_FOUND"));
        }
    }

    /**
     * Retrieves a list of news with pagination.
     *
     * @return array ['ITEMS' => news array, 'NAV' => navigation object]
     * @throws Main\ArgumentException
     * @throws Main\ObjectPropertyException
     * @throws Main\SystemException
     */
    protected function getNewsList(): array
    {
        $pageNavigation = $this->prepareNavigation();
        $newsQueryResult = $this->fetchNewsData($pageNavigation->getOffset(), $pageNavigation->getLimit());

        $rawNewsItems = $newsQueryResult->fetchAll();
        $formattedNewsItems = $this->formatNewsItems($rawNewsItems);

        $pageNavigation->setRecordCount($newsQueryResult->getCount());

        return ['ITEMS' => $formattedNewsItems, 'NAV' => $pageNavigation];
    }

    /**
     * Initializes the pagination object.
     *
     * @return PageNavigation Pagination object.
     */
    protected function prepareNavigation(): PageNavigation
    {
        $pageNavigation = new PageNavigation('page');
        $pageNavigation->allowAllRecords(false)
            ->setPageSize($this->arParams["ELEMENTS_PER_PAGE"])
            ->initFromUri();
        return $pageNavigation;
    }

    /**
     * Fetches news data from the infoblock.
     *
     * @param int $offset Offset for pagination.
     * @param int $limit Number of records per page.
     * @return Result Query result containing news data.
     * @throws Main\ArgumentException
     * @throws Main\ObjectPropertyException
     * @throws Main\SystemException
     */
    protected function fetchNewsData(int $offset, int $limit): Result
    {
        return ElementTable::getList([
            'select' => ['ID', 'NAME', 'PREVIEW_TEXT', 'ACTIVE_FROM'],
            'filter' => [
                'IBLOCK_ID' => $this->arParams["IBLOCK_ID"],
                'ACTIVE' => 'Y',
            ],
            'order' => ['ACTIVE_FROM' => 'DESC'],
            'count_total' => true,
            'offset' => $offset,
            'limit' => $limit,
        ]);
    }

    /**
     * Formats news data before passing it to the template.
     *
     * @param array $rawNewsItems Raw data fetched from the database.
     * @return array Formatted news array.
     */
    protected function formatNewsItems(array $rawNewsItems): array
    {
        return array_map(function ($newsItem) {
            return [
                'ID' => $newsItem['ID'],
                'TITLE' => $newsItem['NAME'],
                'DATE' => $newsItem['ACTIVE_FROM'],
                'PREVIEW' => $newsItem['PREVIEW_TEXT'],
            ];
        }, $rawNewsItems);
    }
}