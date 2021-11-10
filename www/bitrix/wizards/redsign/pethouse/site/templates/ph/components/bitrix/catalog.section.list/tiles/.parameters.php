<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arViewModeList = array(
	'LIST' => GetMessage('RS_SLINE.VIEW_MODE_LIST'),
	'LINE' => GetMessage('RS_SLINE.VIEW_MODE_LINE'),
	'TEXT' => GetMessage('RS_SLINE.VIEW_MODE_TEXT'),
	'TILE' => GetMessage('RS_SLINE.VIEW_MODE_TILE')
);

$arTemplateParameters = array(
/*
	'VIEW_MODE' => array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('RS_SLINE.VIEW_MODE'),
		'TYPE' => 'LIST',
		'VALUES' => $arViewModeList,
		'MULTIPLE' => 'N',
		'DEFAULT' => 'LINE',
		'REFRESH' => 'Y'
	),
*/
	'SHOW_PARENT_NAME' => array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('RS_SLINE.SHOW_PARENT_NAME'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y'
	),
    'LINE_ELEMENT_COUNT' => array(
        'PARENT' => 'VISUAL',
        'NAME' => GetMessage('RS_SLINE.LINE_ELEMENT_COUNT'),
        'TYPE' => 'STRING',
        //'HIDDEN' => isset($templateProperties['PRODUCT_ROW_VARIANTS']) ? 'Y' : 'N',
        'DEFAULT' => '5'
    ),
    'SET_TITLE' => array(),
);

if (isset($arCurrentValues['VIEW_MODE']) && 'TILE' == $arCurrentValues['VIEW_MODE'])
{
	$arTemplateParameters['HIDE_SECTION_NAME'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('RS_SLINE.HIDE_SECTION_NAME'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N'
	);
}
?>