<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Contact us");
?>
<?$APPLICATION->IncludeComponent(
	"redsign:forms", 
	"al", 
	array(
		"COMPONENT_TEMPLATE" => "al",
		"IBLOCK_TYPE" => "forms",
		"IBLOCK_ID" => "#FORMS_RECALL_IBLOCK_ID#",
		"SUCCESS_MESSAGE" => "Thank you for your application is accepted!",
		"EVENT_TYPE" => "RS_FORM_RECALL",
		"EMAIL_TO" => "#SHOP_EMAIL#",
		"USE_CAPTCHA" => "Y",
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
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