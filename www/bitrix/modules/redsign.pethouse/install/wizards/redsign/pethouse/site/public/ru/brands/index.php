<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Все производители");
?><?$APPLICATION->IncludeComponent(
	"bitrix:news", 
	"brands", 
	array(
		"IBLOCK_TYPE" => "system",
		"IBLOCK_ID" => "#BRANDS_IBLOCK_ID#",
		"NEWS_COUNT" => "200",
		"USE_SEARCH" => "N",
		"USE_RSS" => "N",
		"USE_RATING" => "N",
		"USE_CATEGORIES" => "N",
		"USE_REVIEW" => "N",
		"USE_FILTER" => "Y",
		"SORT_BY1" => "NAME",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "TIMESTAMP_X",
		"SORT_ORDER2" => "DESC",
		"CHECK_DATES" => "Y",
		"SEF_MODE" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "Y",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "Y",
		"USE_PERMISSIONS" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"USE_SHARE" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "",
		"LIST_ACTIVE_DATE_FORMAT" => "",
		"LIST_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"DISPLAY_NAME" => "Y",
		"META_KEYWORDS" => "-",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"DETAIL_ACTIVE_DATE_FORMAT" => "",
		"DETAIL_FIELD_CODE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "DETAIL_PICTURE",
			2 => "",
		),
		"DETAIL_PROPERTY_CODE" => array(
			0 => "BRAND",
			1 => "",
		),
		"DETAIL_DISPLAY_TOP_PAGER" => "N",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
		"DETAIL_PAGER_TITLE" => "",
		"DETAIL_PAGER_TEMPLATE" => "",
		"DETAIL_PAGER_SHOW_ALL" => "Y",
		"PAGER_TEMPLATE" => "",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "Y",
		"TAGS_CLOUD_ELEMENTS" => "150",
		"PERIOD_NEW_TAGS" => "",
		"DISPLAY_AS_RATING" => "rating",
		"FONT_MAX" => "50",
		"FONT_MIN" => "10",
		"COLOR_NEW" => "3E74E6",
		"COLOR_OLD" => "C0C0C0",
		"TAGS_CLOUD_WIDTH" => "100%",
		"SEF_FOLDER" => "#SITE_DIR#brands/",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPONENT_TEMPLATE" => "brands",
		"SET_LAST_MODIFIED" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DETAIL_SET_CANONICAL_URL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"CATALOG_IBLOCK_TYPE" => "catalog",
		"CATALOG_IBLOCK_ID" => "#CATALOG_IBLOCK_ID#",
		"CATALOG_FILTER_NAME" => "",
		"HIDE_NOT_AVAILABLE" => "N",
		"CATALOG_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"ICON_MEN_PROP" => "FOR_MEN",
		"ICON_WOMEN_PROP" => "FOR_WOMEN",
		"ICON_NOVELTY_PROP" => "NEW_ICON",
		"ICON_DISCOUNT_PROP" => "DISCOUNT_ICON",
		"ICON_DEALS_PROP" => "ACTION_ICON",
		"ADDITIONAL_PICT_PROP" => "MORE_PHOTO",
		"PRODUCT_SUBSCRIPTION" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_COMPARE" => "Сравнение",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"NOVELTY_TIME" => "720",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"BASKET_URL" => "#SITE_DIR#personal/cart/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"USE_PRODUCT_QUANTITY" => "Y",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"USE_COMPARE" => "Y",
		"SECTION_COUNT_ELEMENTS" => "Y",
		"SECTION_TOP_DEPTH" => "5",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_LIKES" => "Y",
		"CONVERT_CURRENCY" => "N",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_OLD_PRICE" => "Y",
		"OFFER_ADDITIONAL_PICT_PROP" => "SKU_MORE_PHOTO",
		"OFFER_TREE_PROPS" => array(
			0 => "SKU_COLOR",
			1 => "SKU_SIZE",
			2 => "SKU_TKAN",
			3 => "SKU_COLOR_ZERO",
		),
		"OFFER_TREE_COLOR_PROPS" => array(
			0 => "SKU_COLOR",
			1 => "SKU_COLOR_ZERO",
		),
		"OFFER_TREE_BTN_PROPS" => array(
			0 => "SKU_SIZE",
		),
		"SECTION_BACKGROUND_IMAGE" => "-",
		"POPUP_DETAIL_VARIABLE" => "ON_IMAGE",
		"ERROR_EMPTY_ITEMS" => "N",
		"CATALOG_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"CATALOG_OFFERS_PROPERTY_CODE" => array(
			0 => "SKU_COLOR_2",
			1 => "SKU_COLOR_1",
			2 => "SKU_COLOR",
			3 => "SKU_SIZE",
			4 => "SKU_TKAN",
			5 => "SKU_COLOR_ZERO",
			6 => "",
		),
		"CATALOG_OFFERS_LIMIT" => "0",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SHOW_DEACTIVATED" => "N",
		"CATALOG_TEMPLATE_AJAXID" => "ajaxpages_catalog_identifier",
		"CATALOG_USE_AJAXPAGES" => "Y",
		"CATALOG_BRAND_PROP" => "BRANDS",
		"SOCIAL_COUNTER" => "N",
		"SOCIAL_COPY" => "first",
		"SOCIAL_LIMIT" => "",
		"SOCIAL_SIZE" => "m",
		"LIST_SOCIAL_SERVICES" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_SOCIAL_SERVICES" => array(
			0 => "",
			1 => "",
		),
		"BRAND_PROP" => "BRAND",
		"LIKES_COUNT_PROP" => "LIKES_COUNT",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"FILTER_NAME" => "",
		"FILTER_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_SHOW_BASIS_PRICE" => "Y",
		"SEF_URL_TEMPLATES" => array(
			"news" => "",
			"section" => "",
			"detail" => "#ELEMENT_CODE#/",
		)
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>