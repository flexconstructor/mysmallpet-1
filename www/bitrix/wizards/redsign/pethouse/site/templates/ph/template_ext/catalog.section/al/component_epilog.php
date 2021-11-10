<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */

use \Bitrix\Main\Application;
use \Bitrix\Main\Page\Asset;

$Asset = Asset::getInstance();

// $Asset->addJs(SITE_TEMPLATE_PATH.'/assets/js/owl.carousel/owl.carousel.min.js');
// $Asset->addCss(SITE_TEMPLATE_PATH.'/assets/js/owl.carousel/owl.carousel.css');

/*
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/template_ext/catalog.section/catalog/script.js');
*/
if ($arParams['POPUP_DETAIL_VARIABLE'] == 'ON_IMAGE' || $arParams['POPUP_DETAIL_VARIABLE'] == 'ON_LUPA') {
	$Asset->addJs(SITE_TEMPLATE_PATH.'/components/bitrix/catalog.element/catalog/script.js');
}

$Asset->addJs(SITE_TEMPLATE_PATH.'/components/bitrix/catalog.product.subscribe/al/script.js');

$Asset->addJs(SITE_TEMPLATE_PATH.'/template_ext/catalog.section/al/script.js');
$Asset->addCss(SITE_TEMPLATE_PATH.'/template_ext/catalog.section/al/style.css');


$request = Application::getInstance()->getContext()->getRequest();

if (
	$request->get('rs_ajax') == 'Y' &&
	$request->get('ajax_id') == $arParams['TEMPLATE_AJAXID']
) {

	$content = ob_get_contents();
	ob_end_clean();

	list(, $sectionContainer) = explode('<!-- section-container -->', $content);
	list(, $itemsContainer) = explode('<!-- items-container -->', $content);

	if ($arParams['AJAX_MODE'] === 'Y')
	{
		// $component->prepareLinks($filterContainer);
	}

	$APPLICATION->restartBuffer();

	if ($request->get('ajax_type') == 'pages')
	{
		echo $itemsContainer;
	}
	elseif ($request->get('ajax_filter'))
	{
		$arJson = array(
			$arParams['TEMPLATE_AJAXID'] => $sectionContainer,
		);

		if (method_exists($component, 'sendJsonAnswer'))
		{
			$component::sendJsonAnswer($arJson);
		}
		else
		{
			echo \Bitrix\Main\Web\Json::encode($arJson);
		}
	}
	else
	{
		$arJson = array(
			$arParams['TEMPLATE_AJAXID'].'_items' => $itemsContainer,
			$arParams['TEMPLATE_AJAXID'].'_sorter' => $APPLICATION->GetViewContent($arParams['TEMPLATE_AJAXID'] . '_sorter'),
			$arParams['TEMPLATE_AJAXID'].'_pager-bottom' => $APPLICATION->GetViewContent('catalog_pager'),
			$arParams['TEMPLATE_AJAXID'].'_pager-top' => $APPLICATION->GetViewContent('catalog_pager'),
			$arParams['TEMPLATE_AJAXID'].'_filterin' => $APPLICATION->GetViewContent('catalog_filterin')
		);

		if (method_exists($component, 'sendJsonAnswer'))
		{
			$component::sendJsonAnswer($arJson);
		}
		else
		{
			echo \Bitrix\Main\Web\Json::encode($arJson);
		}
	}
	die();
}
