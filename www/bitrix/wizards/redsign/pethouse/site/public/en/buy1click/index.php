<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Buy 1 click");
?>
<?$APPLICATION->IncludeComponent(
	"redsign:buy1click", 
	"al", 
	array(
		"COMPONENT_TEMPLATE" => "al",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"ALFA_EMAIL_TO" => "#SHOP_EMAIL#",
		"ALFA_MESSAGE_AGREE" => "Thank you for your application is accepted!",
		"DATA" => "",
		"SHOW_FIELDS" => array(
			0 => "4",
			1 => "11",
			2 => "92",
		),
		"REQUIRED_FIELDS" => array(
			0 => "92",
		),
		"ALFA_USE_CAPTCHA" => "Y",
		"EVENT_TYPE" => "RS_FORM_BUY1CLICK",
		"USER_ORDER" => "",
		"PHONE_ORDER" => "2",
		"EMAIL_ORDER" => "2",
		"NAME_ORDER" => "2",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "#USER_CONSENT_ID#",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>