<?php

use Bitrix\Main\Application;



if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$context = Application::getInstance()->getContext();
$request = $context->getRequest();

$this->IncludeLangFile('template.php');

$cartId = $arParams['cartId'];

require(realpath(dirname(__FILE__)).'/top_template.php');

$arItemIDs = array();
foreach ($arResult['CATEGORIES'] as $category => $items) {
    if (empty($items)) {
        continue;
    }
    foreach ($items as $v) {
        $arItemIDs[$v['PRODUCT_ID']] = 'Y';
    }
}

?><script>appSLine.cartList = <?=\Bitrix\Main\Web\Json::encode($arItemIDs)?>;</script><?