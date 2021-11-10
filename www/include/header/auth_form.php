<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>

<?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.form",
	"toppanel",
	Array(
		"COMPONENT_TEMPLATE" => "toppanel",
		"REGISTER_URL" => "/auth/",
		"PROFILE_URL" => "/personal/profile/"
	)
);?>