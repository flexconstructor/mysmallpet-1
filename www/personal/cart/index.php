<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Корзина");
$APPLICATION->SetTitle("Корзина");
global $CountBasket;
?>
<?php
if(isset($_GET['basketAction']) && $_GET['basketAction'] == 'delete'):
	header("Location: /personal/cart/");
endif; 
?>
<?
$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket", 
	"basket", 
	array(
		"COMPONENT_TEMPLATE" => "basket",
		"COLUMNS_LIST" => array(
			0 => "NAME",
			1 => "DISCOUNT",
			2 => "WEIGHT",
			3 => "PROPS",
			4 => "DELETE",
			5 => "DELAY",
			6 => "TYPE",
			7 => "PRICE",
			8 => "QUANTITY",
			9 => "SUM",
			10 => "PROPERTY_CML2_ARTICLE",
			11 => "PROPERTY_BRANDS",
			12 => "PROPERTY_YEARS_LIMIT",
			13 => "PROPERTY_YEAR",
			14 => "PROPERTY_RAMA",
			15 => "PROPERTY_VILKA",
			16 => "PROPERTY_OBODA",
			17 => "PROPERTY_TORMOZA",
			18 => "PROPERTY_POSHIV",
			19 => "PROPERTY_VOZRASTNAYA_GRUPPA",
			20 => "PROPERTY_DOSTAVKA_INFO",
			21 => "PROPERTY__",
		),
		"ADDITIONAL_PICT_PROP_11" => "MORE_PHOTO",
		"ARTICLE_PROP_11" => "PROPERTY_CML2_ARTICLE",
		"PATH_TO_ORDER" => "/personal/order/make/",
		"HIDE_COUPON" => "N",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"USE_PREPAYMENT" => "N",
		"QUANTITY_FLOAT" => "N",
		"AUTO_CALCULATION" => "Y",
		"SET_TITLE" => "Y",
		"ACTION_VARIABLE" => "basketAction",
		"USE_BUY1CLICK" => "Y",
		"ADDITIONAL_PICT_PROP_12" => "SKU_MORE_PHOTO",
		"OFFERS_PROPS" => array(
		),
		"OFFER_TREE_COLOR_PROPS" => array(
		),
		"OFFER_TREE_BTN_PROPS" => array(
		),
		"USE_GIFTS" => "Y",
		"GIFTS_PLACE" => "BOTTOM",
		"GIFTS_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",
		"GIFTS_SHOW_OLD_PRICE" => "N",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MESS_BTN_DETAIL" => "Подробнее",
		"GIFTS_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_CONVERT_CURRENCY" => "N",
		"GIFTS_HIDE_NOT_AVAILABLE" => "Y",
		"ARTICLE_PROP_12" => "PROPERTY_CML2_ARTICLE",
		"COLUMNS_LIST_EXT" => array(
			0 => "PREVIEW_PICTURE",
			1 => "DISCOUNT",
			2 => "WEIGHT",
			3 => "DELETE",
			4 => "DELAY",
			5 => "TYPE",
			6 => "PROPERTY_VES",
			7 => "PROPERTY_VES__1",
			8 => "PROPERTY_CML2_ARTICLE",
			9 => "PROPERTY_ARTICLE_1",
		),
		"ADDITIONAL_PICT_PROP_13" => "-",
		"ARTICLE_PROP_13" => "-",
		"CORRECT_RATIO" => "Y",
		"COMPATIBLE_MODE" => "Y",
		"ADDITIONAL_PICT_PROP_14" => "-",
		"ARTICLE_PROP_14" => "-",
		"BASKET_IMAGES_SCALING" => "adaptive"
	),
	false
);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>