<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>

<?$APPLICATION->IncludeComponent(
	"bitrix:menu",
	"modern",
	array(
		"COMPONENT_TEMPLATE" => "modern",
		"ROOT_MENU_TYPE" => "catalog",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "3",
		"CHILD_MENU_TYPE" => "",
		"USE_EXT" => "Y",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"PROPCODE_MORE_PHOTO" => "MORE_PHOTO",
		"PROPCODE_SKU_MORE_PHOTO" => "SKU_MORE_PHOTO",
		"PROPERTY_CODE_ELEMENT_IN_MENU" => "0",
		"NUM_GO_TO_EXCESS" => "5",
		"IBLOCK_ID" => "13",
		"PRICE_CODE" => "BASE",
		"SKU_PRICE_SORT_ID" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"OFFERS_FIELD_CODE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "SKU_COLOR",
			1 => "SKU_SIZE",
			2 => "SKU_TKAN",
			3 => "",
		),
		"CONVERT_CURRENCY" => "Y",
		"COUNT_ELEMENTS" => "N",
		"USE_PRODUCT_QUANTITY" => "Y",
		"ADDITIONAL_PICT_PROP" => "MORE_PHOTO",
		"OFFER_ADDITIONAL_PICT_PROP" => "SKU_MORE_PHOTO",
		"IBLOCK_TYPE" => "1c_catalog",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CACHE_SELECTED_ITEMS" => "N",
		"CURRENCY_ID" => "RUB"
	),
	false
);?>