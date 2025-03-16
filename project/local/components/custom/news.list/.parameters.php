<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Iblock\IblockTable;

Loc::loadMessages(__FILE__);

if (!Loader::includeModule('iblock')) {
    return;
}

$infoBlockList = [];
try {
    $infoBlocks = IblockTable::getList([
        'select' => ['ID', 'NAME'],
        'filter' => ['=ACTIVE' => 'Y'],
        'order' => ['SORT' => 'ASC'],
    ])->fetchAll();

    foreach ($infoBlocks as $infoBlock) {
        $infoBlockList[$infoBlock['ID']] = "{$infoBlock['NAME']} [{$infoBlock['ID']}]";
    }
} catch (Exception $exception) {
    return;
}


$arComponentParameters = [
    "GROUPS" => [],
    "PARAMETERS" => [
        "IBLOCK_ID" => [
            "PARENT"  => "BASE",
            "NAME"    => Loc::getMessage("IBLOCK_ID"),
            "TYPE"    => "LIST",
            "VALUES"  => $infoBlockList,
            "DEFAULT" => !empty($infoBlockList) ? key($infoBlockList) : '',
            "REFRESH" => "Y",
        ],
        "ELEMENTS_PER_PAGE" => [
            "PARENT"  => "BASE",
            "NAME"    => Loc::getMessage("ELEMENTS_PER_PAGE"),
            "TYPE"    => "STRING",
            "DEFAULT" => "5",
        ],
    ],
];