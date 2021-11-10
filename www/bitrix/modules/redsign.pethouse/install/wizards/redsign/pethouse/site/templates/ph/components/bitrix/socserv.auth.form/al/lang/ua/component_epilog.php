<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Application;

$request = Application::getInstance()->getContext()->getRequest();

$bIsAjax = (
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
    $request->get('rs_ajax') == 'Y' ||
    $request->get('rs_ajax__page') == 'Y'
);

if ($bIsAjax) {
    ?><link href="<?=$templateFolder?>/style.css" type="text/css" rel="stylesheet" /><?
}