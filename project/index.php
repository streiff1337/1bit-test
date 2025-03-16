<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle("Главная");
?>

<?php
$APPLICATION->IncludeComponent(
	"custom:news.list", 
	".default", 
	array(
		"ELEMENTS_PER_PAGE" => "2",
		"IBLOCK_ID" => "1",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?>

<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
