<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Application;

$request = Application::getInstance()->getContext()->getRequest();

// $bIsAjax = (
    // $request->isAjaxRequest() ||
    // $request->get('rs_ajax') == 'Y' ||
    // $request->get('rs_ajax__page') == 'Y'
// );

// if ($bIsAjax) {
    // if (
        // $USER->getId() > 0 &&
        // (strlen($request->get('backurl')) > 0 || $arParams["SUCCESS_PAGE"] <> '')
    // ) {
    // }
//	$APPLICATION->RestartBuffer();
//	die();
// }