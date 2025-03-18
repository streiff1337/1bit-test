<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle("Главная");
?><?$APPLICATION->IncludeComponent(
	"custom:news.list",
	".default",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"ELEMENTS_PER_PAGE" => "2",
		"IBLOCK_ID" => "1"
	)
);?>
<?$APPLICATION->IncludeComponent(
	"weather.integration",
	"",
Array()
);?>