<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*
use \Bitrix\Main\Application;

$request = Application::getInstance()->getContext()->getRequest();

$bIsAjax = (
    $request->isAjaxRequest() ||
    $request->get('rs_ajax') == 'Y'
);

if ($bIsAjax) {
	$APPLICATION->RestartBuffer();
	echo $templateData['TEMPLATE_HTML'];
	die();
}
*/