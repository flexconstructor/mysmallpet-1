<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация");
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.form", 
	"fancybox", 
	array(
		"COMPONENT_TEMPLATE" => "fancybox",
		"REGISTER_URL" => "/auth/",
		"FORGOT_PASSWORD_URL" => "/auth/",
		"PROFILE_URL" => "/personal/profile/",
		"SHOW_ERRORS" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>