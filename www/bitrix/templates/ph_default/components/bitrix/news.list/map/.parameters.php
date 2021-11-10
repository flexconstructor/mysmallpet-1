<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (
    !Loader::includeModule('iblock')
    || !Loader::includeModule('redsign.devfunc')
    ) {
	return;
}

$defaultListValues = array('-' => getMessage('RS_SLINE.UNDEFINED'));

$listProp = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);

$arMapTypes = array(
    'yandex' => Loc::getMessage('RS_SLINE.MAP_TYPE.YANDEX'),
    'google' => Loc::getMessage('RS_SLINE.MAP_TYPE.GOOGLE'),
);

$arTemplateParameters = array(
	'DISPLAY_DATE' => Array(
		'NAME' => GetMessage('T_IBLOCK_DESC_NEWS_DATE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'DISPLAY_NAME' => Array(
		'NAME' => GetMessage('T_IBLOCK_DESC_NEWS_NAME'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'DISPLAY_PICTURE' => Array(
		'NAME' => GetMessage('T_IBLOCK_DESC_NEWS_PICTURE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'DISPLAY_PREVIEW_TEXT' => Array(
		'NAME' => GetMessage('T_IBLOCK_DESC_NEWS_TEXT'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'MAP_ZOOM' => array(
		'NAME' => GetMessage('RS_SLINE.MAP_ZOOM'),
		'TYPE' => 'STRING',
		'DEFAULT' => '14',
	),
	'MAP_TYPE' => array(
		'NAME' => GetMessage('RS_SLINE.MAP_TYPE'),
		'TYPE' => 'LIST',
        'VALUES' => $arMapTypes,
		'DEFAULT' => 'yandex',
	),
	'MAP_POINT_COORD_PROP' => array(
		'NAME' => Loc::getMessage('RS_SLINE.MAP_POINT_COORD_PROP'),
		'TYPE' => 'LIST',
		'VALUES' => array_merge($defaultListValues, $listProp['SNL']),
	),
	'MAP_POINT_TYPE_PROP' => array(
		'NAME' => Loc::getMessage('RS_SLINE.MAP_POINT_TYPE_PROP'),
		'TYPE' => 'LIST',
		'VALUES' => array_merge($defaultListValues, $listProp['L']),
	),
	'GOOGLE_API_KEY' => array(
		'NAME' => Loc::getMessage('RS_SLINE.GOOGLE_API_KEY'),
		'TYPE' => 'STRING',
		'DEFAULT' => '',
	),
	'SHOW_TITLE' => array(
		'NAME' => Loc::getMessage('RS_SLINE.SHOW_TITLE'),
		'TYPE' => 'CHECKBOX',
		'VALUE' => 'Y',
		'DEFAULT' => 'Y',
	),
);
