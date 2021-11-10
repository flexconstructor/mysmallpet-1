<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Application;

$request = Application::getInstance()->getContext()->getRequest();

$bIsAjax = (
    $request->isAjaxRequest() ||
    $request->get('rs_ajax') == 'Y' ||
    $request->get('rs_ajax__page') == 'Y'
);
?>

<?php //if ($bIsAjax): ?>
	<?php //$APPLICATION->RestartBuffer(); ?>
	<?//=$templateData['TEMPLATE_HTML']; ?>
<?php //endif; ?>

<?php if ($bIsAjax || $request->get('RSLOC_CITY_ID') !== null && intval($request->get('RSLOC_CITY_ID')) > 0): ?>
    <?php if ($request->get('RSLOC_CITY_ID') !== null && intval($request->get('RSLOC_CITY_ID')) > 0): ?>
    <script>
	window.appSLine.closePopup({
	<?php if (strlen($arResult['BACKURL']) > 0): ?>
		backurl: <?=CUtil::PhpToJSObject($arResult['BACKURL'])?>,
	<?php endif; ?>
	});
    </script>
    <?php endif; ?>

<?php endif; ?>



<?php //if ($bIsAjax): ?>
    <?php //die(); ?>
<?php //endif; ?>