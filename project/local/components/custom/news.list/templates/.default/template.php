<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="news-list">
    <?php foreach ($arResult['ITEMS'] as $newsItem): ?>
        <div class="news-item">
            <h2><?= htmlspecialcharsbx($newsItem['TITLE']) ?></h2>
            <p><small><?= $newsItem['DATE'] ?></small></p>
            <p><?= htmlspecialcharsbx($newsItem['PREVIEW']) ?></p>
        </div>
    <?php endforeach; ?>

    <?php
    $APPLICATION->IncludeComponent(
        "bitrix:main.pagenavigation",
        "",
        [
            "NAV_OBJECT" => $arResult['NAV'],
            "SEF_MODE" => "N",
        ],
        false
    );
    ?>
</div>
