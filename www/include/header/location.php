<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>

<?$APPLICATION->IncludeComponent(
	"redsign:autodetect.location",
	"head",
	array(
		"COMPONENT_TEMPLATE" => "head",
		"RSLOC_INCLUDE_JQUERY" => "N",
		"RSLOC_LOAD_LOCATIONS" => "N"
	),
	false
);?>