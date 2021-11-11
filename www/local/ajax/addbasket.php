<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
if (!CModule::IncludeModule('sale') && !CModule::IncludeModule('catalog')) {
    return false;
}

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if ($request->isAjaxRequest() && $request->isPost()) {
    $post_data = $request->getPost('offers_data');
    if (!empty($post_data)) {
        $arFields = array(
            'LID' => \Bitrix\Main\Context::getCurrent()->getSite(),
            'DELAY' => 'N',
            'CAN_BUY' => 'Y',
            'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
            //'NAME' => $request->getPost('NAME'),
        );
        foreach ($post_data as $field) {
            $arFields['PRODUCT_ID'] = $field['ID'];
            $arFields['PRICE'] = $field['PRICE'];
            $arFields['QUANTITY'] = $field['QUANTITY'];

            $r = Bitrix\Catalog\Product\Basket::addProduct($arFields);
            if (!$r->isSuccess()) {
                var_dump($r->getErrorMessages());
            } else{
                echo 1;
            }
        }
    } else {
        echo 'empty';
    }
}
