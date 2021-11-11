<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);
?>
<h1 class="catalog__title"><?$APPLICATION->ShowTitle(false, false)?></h1>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.compare.result",
	"al",
	array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => (!empty($arParams["ACTION_VARIABLE"]) ? $arParams["ACTION_VARIABLE"] : "action"),
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"FIELD_CODE" => $arParams["COMPARE_FIELD_CODE"],
		"PROPERTY_CODE" => $arParams["COMPARE_PROPERTY_CODE"],
		"NAME" => $arParams["COMPARE_NAME"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"PRICE_CODE" => $arParams["~PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
		"DISPLAY_ELEMENT_SELECT_BOX" => $arParams["DISPLAY_ELEMENT_SELECT_BOX"],
		"ELEMENT_SORT_FIELD_BOX" => $arParams["ELEMENT_SORT_FIELD_BOX"],
		"ELEMENT_SORT_ORDER_BOX" => $arParams["ELEMENT_SORT_ORDER_BOX"],
		"ELEMENT_SORT_FIELD_BOX2" => $arParams["ELEMENT_SORT_FIELD_BOX2"],
		"ELEMENT_SORT_ORDER_BOX2" => $arParams["ELEMENT_SORT_ORDER_BOX2"],
		"ELEMENT_SORT_FIELD" => $arParams["COMPARE_ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => $arParams["COMPARE_ELEMENT_SORT_ORDER"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"OFFERS_FIELD_CODE" => $arParams["COMPARE_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["COMPARE_OFFERS_PROPERTY_CODE"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],
		'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
		
		"ICON_MEN_PROP" => $arParams["ICON_MEN_PROP"],
		"ICON_WOMEN_PROP" => $arParams["ICON_WOMEN_PROP"],
		"ICON_NOVELTY_PROP" => $arParams["ICON_NOVELTY_PROP"],
		'NOVELTY_TIME' => $arParams['NOVELTY_TIME'],
		"ICON_DISCOUNT_PROP" => $arParams["ICON_DISCOUNT_PROP"],
		"ICON_DEALS_PROP" => $arParams["ICON_DEALS_PROP"],
        'USE_LIKES' => $arParams['USE_LIKES'],
        'USE_FAVORITE' => $arParams['USE_FAVORITE'],
        'USE_SHARE' => $arParams['USE_SHARE'],
        'SOCIAL_SERVICES' => $arParams['DETAIL_SOCIAL_SERVICES'],
        'LIST_SOCIAL_SERVICES' => $arParams['LIST_SOCIAL_SERVICES'],
        'SOCIAL_COUNTER' => $arParams['SOCIAL_COUNTER'],
        'SOCIAL_COPY' => $arParams['SOCIAL_COPY'],
        'SOCIAL_LIMIT' => $arParams['SOCIAL_LIMIT'],
        'SOCIAL_SIZE' => $arParams['SOCIAL_SIZE'],
		"ADDITIONAL_PICT_PROP" => $arParams["ADDITIONAL_PICT_PROP"],
		"OFFER_ADDITIONAL_PICT_PROP" => $arParams["OFFER_ADDITIONAL_PICT_PROP"],
		"ARTICLE_PROP" => $arParams["ARTICLE_PROP"],
        "OFFER_ARTICLE_PROP" => $arParams["OFFER_ARTICLE_PROP"],
		"BRAND_PROP" => $arParams["BRAND_PROP"],
		"BRAND_IBLOCK_ID" => $arParams["BRAND_IBLOCK_ID"],
		"BRAND_IBLOCK_BRAND_PROP" => $arParams["BRAND_IBLOCK_BRAND_PROP"],
		"BRAND_LOGO_PROP" => $arParams["BRAND_LOGO_PROP"],
		'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
		'OFFER_TREE_COLOR_PROPS' => $arParams['OFFER_TREE_COLOR_PROPS'],
		'OFFER_TREE_BTN_PROPS' => $arParams['OFFER_TREE_BTN_PROPS'],
		"SHOW_OLD_PRICE" => $arParams['SHOW_OLD_PRICE'],
		"SHOW_DISCOUNT_PERCENT" => $arParams['SHOW_DISCOUNT_PERCENT'],
		"SHOW_SECTION_URL" => $arParams['SHOW_SECTION_URL'],
		"TEMPLATE_AJAXID" => (!empty($arParams['TEMPLATE_AJAXID']) ? $arParams['TEMPLATE_AJAXID'] : "catalog")."_compare",
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?>
<?php
$APPLICATION->AddChainItem(GetMessage("COMPARE_PAGE_TITLE"), $APPLICATION->GetCurPage());
$APPLICATION->SetTitle(GetMessage("COMPARE_PAGE_TITLE"));