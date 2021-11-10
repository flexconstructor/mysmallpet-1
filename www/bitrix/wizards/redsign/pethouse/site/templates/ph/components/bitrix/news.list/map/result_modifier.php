<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

use \Bitrix\Main\Loader;

if (!Loader::includeModule('iblock')) {
    return;
}

if (!isset($arParams['MAP_TYPE']) || strlen($arParams['MAP_TYPE']) <= 0) {
    $arParams['MAP_TYPE'] = 'yandex'; // = false;
}
if (!isset($arParams['MAP_POINT_COORD_PROP']) || strlen($arParams['MAP_POINT_COORD_PROP']) <= 0) {
    $arParams['MAP_POINT_COORD_PROP'] = 'SHOP_MAP_COORDS'; // = false;
}

if (!isset($arParams['MAP_POINT_TYPE_PROP']) || strlen($arParams['MAP_POINT_TYPE_PROP']) <= 0) {
    $arParams['MAP_POINT_TYPE_PROP'] = 'SHOP_TYPE'; // = false;
}

$arResult['MAP_TYPE'] = $arParams['MAP_TYPE'];

$arResult['FILTER_TYPES'] = array();
// $arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'CODE' => $arParams['MAP_POINT_TYPE_PROP']);
// $propertyEnums = CIBlockPropertyEnum::GetList(array(), $arFilter);
// while ($arFields = $propertyEnums->GetNext()) {
    // $arResult['FILTER_TYPES'][] = array(
		// 'ID' => $arFields['ID'],
		// 'VALUE' => $arFields['VALUE'],
		// 'XML_ID' => $arFields['XML_ID'],
	// );
// }

foreach ($arResult['ITEMS'] as &$arItem) {
    
    if (
        !empty($arItem['PROPERTIES'][$arParams['MAP_POINT_TYPE_PROP']])
        && !isset($arResult['FILTER_TYPES'][$arItem['PROPERTIES'][$arParams['MAP_POINT_TYPE_PROP']]['VALUE_ENUM_ID']])
    ) {
        $arResult['FILTER_TYPES'][$arItem['PROPERTIES'][$arParams['MAP_POINT_TYPE_PROP']]['VALUE_ENUM_ID']] = array(
            'ID' => $arItem['PROPERTIES'][$arParams['MAP_POINT_TYPE_PROP']]['VALUE_ENUM_ID'],
            'VALUE' => $arItem['PROPERTIES'][$arParams['MAP_POINT_TYPE_PROP']]['VALUE'],
            'XML_ID' => $arItem['PROPERTIES'][$arParams['MAP_POINT_TYPE_PROP']]['VALUE_XML_ID'],
        );
    }

    $arItem['COORDINATES'] = $arItem['PROPERTIES'][$arParams['MAP_POINT_COORD_PROP']]['VALUE'];
    $arItem['TYPE'] = $arItem['PROPERTIES'][$arParams['MAP_POINT_TYPE_PROP']]['VALUE_XML_ID'];
}
unset($arItem);

$this->__component->arResult['MAP_TYPE'] = $arResult['MAP_TYPE'];

$this->__component->SetResultCacheKeys(array('MAP_TYPE'));