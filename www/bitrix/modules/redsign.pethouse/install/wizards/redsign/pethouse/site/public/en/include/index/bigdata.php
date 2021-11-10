<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.bigdata.products",
	"al",
	array(
		"COMPONENT_TEMPLATE" => "al",
		"RCM_TYPE" => "personal",
		"ID" => "",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "#CATALOG_IBLOCK_ID#",
		"SHOW_FROM_SECTION" => "N",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_ELEMENT_ID" => "",
		"SECTION_ELEMENT_CODE" => "",
		"DEPTH" => "",
		"HIDE_NOT_AVAILABLE" => "N",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_NAME" => "Y",
		"SHOW_IMAGE" => "Y",
		"MESS_BTN_BUY" => "Buy",
		"MESS_BTN_DETAIL" => "More",
		"MESS_BTN_SUBSCRIBE" => "Subscribe",
		"PAGE_ELEMENT_COUNT" => "30",
		"SECTION_TITLE" => "",
		"NOVELTY_TIME" => "720",
		"TEMPLATE_AJAXID" => "catalog_home",
		"DETAIL_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_GROUPS" => "Y",
		"SHOW_OLD_PRICE" => "N",
		"PRICE_CODE" => array(
			0 => "BASE",
			1 => "ONLINE",
		),
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "N",
		"CONVERT_CURRENCY" => "Y",
		"CURRENCY_ID" => "RUB",
		"BASKET_URL" => "#SITE_DIR#personal/cart/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "Y",
		"USE_PRODUCT_QUANTITY" => "Y",
		"SHOW_PRODUCTS_#CATALOG_IBLOCK_ID#" => "Y",
		"PROPERTY_CODE_#CATALOG_IBLOCK_ID#" => array(
			0 => "",
			1 => "",
		),
		"CART_PROPERTIES_#CATALOG_IBLOCK_ID#" => array(
			0 => "",
			1 => "",
		),
		"ADDITIONAL_PICT_PROP_#CATALOG_IBLOCK_ID#" => "MORE_PHOTO",
		"LABEL_PROP_#CATALOG_IBLOCK_ID#" => "-",
		"MAKER_PROP_#CATALOG_IBLOCK_ID#" => "BRANDS",
		"ICON_NOVELTY_PROP_#CATALOG_IBLOCK_ID#" => "NEW_ICON",
		"ICON_DEALS_PROP_#CATALOG_IBLOCK_ID#" => "ACTION_ICON",
		"ICON_DISCOUNT_PROP_#CATALOG_IBLOCK_ID#" => "DISCOUNT_ICON",
		"ICON_MEN_PROP_#CATALOG_IBLOCK_ID#" => "FOR_MEN",
		"ICON_WOMEN_PROP_#CATALOG_IBLOCK_ID#" => "FOR_WOMEN",
		"PROPERTY_CODE_#OFFERS_IBLOCK_ID#" => array(
			0 => "",
			1 => "",
		),
		"CART_PROPERTIES_#OFFERS_IBLOCK_ID#" => array(
			0 => "",
			1 => "",
		),
		"ADDITIONAL_PICT_PROP_#OFFERS_IBLOCK_ID#" => "SKU_MORE_PHOTO",
		"OFFER_TREE_PROPS_#OFFERS_IBLOCK_ID#" => array(
			0 => "SKU_COLOR_2",
			1 => "SKU_COLOR_1",
			2 => "SKU_COLOR",
			3 => "SKU_SIZE",
			4 => "SKU_TKAN",
			5 => "SKU_COLOR_ZERO",
		),
		"OFFER_TREE_BTN_PROPS_#OFFERS_IBLOCK_ID#" => array(
			0 => "SKU_SIZE",
		),
		"OFFER_TREE_COLOR_PROPS_#OFFERS_IBLOCK_ID#" => array(
			0 => "SKU_COLOR_2",
			1 => "SKU_COLOR_1",
			2 => "SKU_COLOR",
			3 => "SKU_COLOR_ZERO",
		)
	),
	false
);?>