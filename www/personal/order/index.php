<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Мои заказы");
$APPLICATION->SetTitle("Мои заказы");
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.order",
	"al",
	array(
		"COMPONENT_TEMPLATE" => "al",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SEF_MODE" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_GROUPS" => "Y",
		"ORDERS_PER_PAGE" => "20",
		"PATH_TO_PAYMENT" => "/personal/order/payment/",
		"PATH_TO_BASKET" => "/personal/cart/",
		"SET_TITLE" => "Y",
		"SAVE_IN_SESSION" => "N",
		"NAV_TEMPLATE" => "al",
		"CUSTOM_SELECT_PROPS" => array(
		),
		"HISTORIC_STATUSES" => array(
			0 => "F",
		),
		"ARTICLE_PROP_11" => "CML2_ARTICKLE",
		"ADDITIONAL_PICT_PROP_11" => "MORE_PHOTO",
		"ARTICLE_PROP_12" => "CML2_ARTICKLE",
		"ADDITIONAL_PICT_PROP_12" => "SKU_MORE_PHOTO",
		"OFFER_TREE_PROPS_12" => array(
			0 => "SKU_COLOR_2",
			1 => "SKU_COLOR_1",
			2 => "SKU_COLOR",
			3 => "SKU_SIZE",
			4 => "CML2_ARTICKLE",
			5 => "SKU_TKAN",
		),
		"SEF_FOLDER" => "/personal/order/",
		"SEF_URL_TEMPLATES" => array(
			"list" => "",
			"detail" => "detail/#ID#/",
			"cancel" => "cancel/#ID#/",
		)
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>