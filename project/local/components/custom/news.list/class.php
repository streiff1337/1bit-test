<?php

use Bitrix\Iblock\EO_Element_Result;
use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;

class NewsListComponent extends CBitrixComponent
{
    const DEFAULT_ELEMENTS_COUNT = 10;
    const DEFAULT_IBLOCK_ID = 1;

    public function onPrepareComponentParams($arParams): array
    {
        $arParams['ELEMENTS_PER_PAGE'] = intval($arParams['ELEMENTS_PER_PAGE']) ?: self::DEFAULT_ELEMENTS_COUNT;
        $arParams['IBLOCK_ID'] = intval($arParams['IBLOCK_ID']) ?: self::DEFAULT_IBLOCK_ID;
        return $arParams;
    }

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

    protected function checkModules(): void
    {
        if (!Loader::includeModule('iblock')) {
            throw new Main\LoaderException(Loc::getMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        }
    }

    protected function checkIBlockExists(): void
    {
        $infoBlock = IblockTable::getList([
            'select' => ['ID'],
            'filter' => ['=ID' => $this->arParams['IBLOCK_ID'], '=ACTIVE' => 'Y'],
        ])->fetch();

        if (!$infoBlock) {
            throw new \Exception(Loc::getMessage("IBLOCK_NOT_FOUND"));
        }
    }

    protected function getNewsList(): array
    {
        $pageNavigation = $this->prepareNavigation();
        $newsQueryResult = $this->fetchNewsData($pageNavigation->getOffset(), $pageNavigation->getLimit());

        $rawNewsItems = $newsQueryResult->fetchAll();
        $formattedNewsItems = $this->formatNewsItems($rawNewsItems);

        $pageNavigation->setRecordCount($newsQueryResult->getCount());

        return ['ITEMS' => $formattedNewsItems, 'NAV' => $pageNavigation];
    }

    protected function prepareNavigation(): PageNavigation
    {
        $pageNavigation = new PageNavigation('page');
        $pageNavigation->allowAllRecords(false)
            ->setPageSize($this->arParams["ELEMENTS_PER_PAGE"])
            ->initFromUri();
        return $pageNavigation;
    }

    protected function fetchNewsData(int $offset, int $limit): EO_Element_Result
    {
        return ElementTable::getList([
            'select' => ['ID', 'NAME', 'PREVIEW_TEXT', 'DATE_ACTIVE_FROM'],
            'filter' => [
                'IBLOCK_ID' => $this->arParams["IBLOCK_ID"],
                'ACTIVE' => 'Y',
            ],
            'order' => ['DATE_ACTIVE_FROM' => 'DESC'],
            'count_total' => true,
            'offset' => $offset,
            'limit' => $limit,
        ]);
    }

    protected function formatNewsItems(array $rawNewsItems): array
    {
        return array_map(static function ($newsItem) {
            return [
                'ID' => $newsItem['ID'],
                'TITLE' => $newsItem['NAME'],
                'DATE' => $newsItem['DATE_ACTIVE_FROM'],
                'PREVIEW' => $newsItem['PREVIEW_TEXT'],
            ];
        }, $rawNewsItems);
    }
}