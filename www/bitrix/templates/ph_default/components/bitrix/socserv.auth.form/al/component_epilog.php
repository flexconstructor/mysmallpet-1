<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Application;

$arExt = array();
if($arParams["POPUP"])
	$arExt[] = "window";
CUtil::InitJSCore($arExt);
$GLOBALS["APPLICATION"]->SetAdditionalCSS("/bitrix/js/socialservices/css/ss.css");
$GLOBALS["APPLICATION"]->AddHeadScript("/bitrix/js/socialservices/ss.js");

$request = Application::getInstance()->getContext()->getRequest();

$bIsAjax = (
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
    $request->get('rs_ajax') == 'Y' ||
    $request->get('rs_ajax__page') == 'Y'
);

if ($bIsAjax) {
    ?><link rel="stylesheet" type="text/css" href="/bitrix/js/socialservices/css/ss.css" /><? // TODO _loadCSS
    ?><script>
    if (window.jQuery) {
        $.ajax({url:'/bitrix/js/socialservices/ss.js',type:'GET',dataType:'script',cache:true});
    }
    </script><?
}