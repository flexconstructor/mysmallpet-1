<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Обратная связь");
?>
<?$APPLICATION->IncludeComponent(
	"redsign:forms", 
	"al", 
	array(
		"COMPONENT_TEMPLATE" => "al",
		"IBLOCK_TYPE" => "forms",
		"IBLOCK_ID" => "9",
		"SUCCESS_MESSAGE" => "Спасибо, ваша заявка принята!",
		"EVENT_TYPE" => "RS_FORM_FEEDBACK",
		"EMAIL_TO" => "sale@mysmallpet.itdelta-test.ru",
		"USE_CAPTCHA" => "Y",
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "1",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>