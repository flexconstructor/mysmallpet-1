<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Loader;
use \Bitrix\Iblock;

if (
    !Loader::includeModule('iblock')
    || !Loader::includeModule('catalog')
) {
    return;
}

global $USER_FIELD_MANAGER;

$arIBlock = array();
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), array('ACTIVE' => 'Y'));
while ($arr = $rsIBlock->Fetch()){
    $arIBlock[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
}

$arProperty_UF = array();
$arUserFields = $USER_FIELD_MANAGER->GetUserFields('IBLOCK_'.$arCurrentValues['IBLOCK_ID'].'_SECTION', 0, LANGUAGE_ID);
foreach($arUserFields as $FIELD_NAME=>$arUserField)
{
	$arUserField['LIST_COLUMN_LABEL'] = (string)$arUserField['LIST_COLUMN_LABEL'];
	$arProperty_UF[$FIELD_NAME] = $arUserField['LIST_COLUMN_LABEL'] ? '['.$FIELD_NAME.']'.$arUserField['LIST_COLUMN_LABEL'] : $FIELD_NAME;
}

$arPopupDetailVariable = array(
    'ON_IMAGE' => getMessage('RS_SLINE.POPUP_DETAIL_VARIABLE_IMAGE'),
    'ON_LUPA' => getMessage('RS_SLINE.POPUP_DETAIL_VARIABLE_LUPA'),
    'ON_NONE' => getMessage('RS_SLINE.POPUP_DETAIL_VARIABLE_NONE'),
);
$arSectionDescrValues = array(
    '-' => getMessage('RS_SLINE.UNDEFINED'),
    'top' => getMessage('RS_SLINE.SHOW_SECTION_DESCRIPTION_TOP'),
    'bottom' => getMessage('RS_SLINE.SHOW_SECTION_DESCRIPTION_BOTTOM'),
);

// $arPriceFor = array(
    // '-' => getMessage('RS_SLINE.UNDEFINED'),
    // 'products' => getMessage('RS_SLINE.FILTER_PRICES_GROUPED_FOR_PRIDUCTS'),
    // 'sku' => getMessage('RS_SLINE.FILTER_PRICES_GROUPED_FOR_SKU'),
// );

$arCatalogViewMode = array(
    'VIEW_SECTIONS' => getMessage('RS_SLINE.CATALOG_VIEW.SECTIONS'),
    'VIEW_ELEMENTS' => getMessage('RS_SLINE.CATALOG_VIEW.ELEMENTS')
);

$arViewModeList = array(
    'LIST' => GetMessage('RS_SLINE.SECTIONS_VIEW_MODE_LIST'),
    'LINE' => GetMessage('RS_SLINE.SECTIONS_VIEW_MODE_LINE'),
    'TEXT' => GetMessage('RS_SLINE.SECTIONS_VIEW_MODE_TEXT'),
    'TILE' => GetMessage('RS_SLINE.SECTIONS_VIEW_MODE_TILE')
);

$defaultValue = array('-' => GetMessage('CP_BC_TPL_PROP_EMPTY'));

$IBLOCK_ID = intval($arCurrentValues['IBLOCK_ID']);

$arAllPropList = array();
$arListPropList = array();
$arHighloadPropList = array();
$arFilePropList = $defaultValue;

if (isset($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID'] > 0)
{
	$rsProps = CIBlockProperty::GetList(
		array('SORT' => 'ASC', 'ID' => 'ASC'),
		array('IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'], 'ACTIVE' => 'Y')
	);
	while ($arProp = $rsProps->Fetch())
	{
		$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
		if ('' == $arProp['CODE'])
		{
			$arProp['CODE'] = $arProp['ID'];
		}

		$arAllPropList[$arProp['CODE']] = $strPropName;

		if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_FILE)
		{
			$arFilePropList[$arProp['CODE']] = $strPropName;
		}

		if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST)
		{
			$arListPropList[$arProp['CODE']] = $strPropName;
		}

		if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_STRING && 'directory' == $arProp['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arProp))
		{
			$arHighloadPropList[$arProp['CODE']] = $strPropName;
		}
	}
}

$arPrice = array();
$rsPrice = CCatalogGroup::GetList($v1='sort', $v2='asc');
while($arr = $rsPrice->Fetch()){
    $arPrice[$arr['NAME']] = '['.$arr['NAME'].'] '.$arr['NAME_LANG'];
}

$arTemplateParameters = array(
    'CATALOG_VIEW_MODE' => array(
        'PARENT' => 'SECTIONS_SETTINGS',
        'NAME' => getMessage('RS_SLINE.CATALOG_VIEW'),
        'TYPE' => 'LIST',
        'VALUES' => $arCatalogViewMode,
        'MULTIPLE' => 'N',
        'DEFAULT' => 'VIEW_ELEMENTS',
    ),
/*
	'SECTIONS_VIEW_MODE' => array(
		'PARENT' => 'SECTIONS_SETTINGS',
		'NAME' => GetMessage('RS_SLINE.SECTIONS_VIEW_MODE'),
		'TYPE' => 'LIST',
		'VALUES' => $arViewModeList,
		'MULTIPLE' => 'N',
		'DEFAULT' => 'LIST',
		'REFRESH' => 'Y'
	),
*/
    'SECTION_USER_FIELDS' => array(
        'PARENT' => 'SECTIONS_SETTINGS',
        'NAME' => GetMessage('RS_SLINE.SECTION_USER_FIELDS'),
        'TYPE' => 'LIST',
        'MULTIPLE' => 'Y',
        'ADDITIONAL_VALUES' => 'Y',
        'VALUES' => $defaultValue + $arProperty_UF,
        'DEFAULT' => '-',
    ),
	'SECTIONS_SHOW_PARENT_NAME' => array(
		'PARENT' => 'SECTIONS_SETTINGS',
		'NAME' => GetMessage('RS_SLINE.SECTIONS_SHOW_PARENT_NAME'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y'
	),
    //PAGER_SETTINGS
    'USE_AJAXPAGES' => array(
        'PARENT' => 'PAGER_SETTINGS',
        'NAME' => getMessage('RS_SLINE.USE_AJAXPAGES'),
        'TYPE' => 'CHECKBOX',
        'VALUE' => 'Y',
        'DEFAULT' => 'Y',
    ),
    //LIST_SETTINGS
    'SHOW_SECTION_PICTURE' => array(
        'PARENT' => 'LIST_SETTINGS',
        'NAME' => getMessage('RS_SLINE.SHOW_SECTION_PICTURE'),
        'TYPE' => 'CHECKBOX',
        'VALUE' => 'Y',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ),
    'POPUP_DETAIL_VARIABLE' => array(
        'PARENT' => 'LIST_SETTINGS',
        'NAME' => getMessage('RS_SLINE.POPUP_DETAIL_VARIABLE'),
        'TYPE' => 'LIST',
        'MULTIPLE' => 'N',
        'VALUES' => $arPopupDetailVariable,
        'REFRESH' => 'N',
    ),
    'ERROR_EMPTY_ITEMS' => array(
        'PARENT' => 'LIST_SETTINGS',
        'NAME' => getMessage('RS_SLINE.ERROR_EMPTY_ITEMS'),
        'TYPE' => 'CHECKBOX',
        'VALUE' => 'Y',
        'DEFAULT' => 'N',
    ),
    'SHOW_SECTION_DESCRIPTION' => array(
        'PARENT' => 'LIST_SETTINGS',
        'NAME' => getMessage('RS_SLINE.SHOW_SECTION_DESCRIPTION'),
        'TYPE' => 'LIST',
        'VALUES' => $arSectionDescrValues,
        'DEFAULT' => '-',
    ),
    'LIST_DISPLAY_PREVIEW_TEXT' => Array(
        'PARENT' => 'LIST_SETTINGS',
        'NAME' => GetMessage('RS_SLINE.PREVIEW_TEXT'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y',
    ),
    'PREVIEW_TRUNCATE_LEN' => array(
        'PARENT' => 'LIST_SETTINGS',
        'NAME' => GetMessage('RS_SLINE.PREVIEW_TRUNCATE_LEN'),
        'TYPE' => 'STRING',
        'DEFAULT' => '',
    ),
    //DETAIL_SETTINGS
    'LINKED_ITEMS_PROPS' => array(
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => getMessage('RS_SLINE.LINKED_ITEMS_PROPS'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
        'MULTIPLE' => 'Y',
    ),
    'TAB_IBLOCK_PROPS' => array(
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => getMessage('RS_SLINE.TAB_IBLOCK_PROPS'),
        'TYPE' => 'LIST',
        'MULTIPLE' => 'Y',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '',
        'ADDITIONAL_VALUES' => 'Y',
    ),
    'SIZE_TABLE_USER_FIELD_CODE' => array(
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => getMessage('RS_SLINE.SIZE_TABLE_USER_FIELD_CODE'),
        'TYPE' => 'STRING',
        'DEFAULT' => 'UF_SIZE_TABLE',
    ),
    'USE_KREDIT' => array(
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => getMessage('RS_SLINE.USE_KREDIT'),
        'TYPE' => 'CHECKBOX',
        'VALUE' => 'Y',
        'DEFAULT' => 'Y',
    ),
    'KREDIT_URL' => array(
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => getMessage('RS_SLINE.KREDIT_URL'),
        'TYPE' => 'STRING',
        'DEFAULT' => '',
    ),
    'USE_PICTURE_ZOOM' => array(
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => getMessage('RS_SLINE.USE_PICTURE_ZOOM'),
        'TYPE' => 'CHECKBOX',
        'VALUE' => 'Y',
        'DEFAULT' => 'Y',
    ),
    'USE_PICTURE_GALLERY' => array(
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => getMessage('RS_SLINE.USE_PICTURE_GALLERY'),
        'TYPE' => 'CHECKBOX',
        'VALUE' => 'Y',
        'DEFAULT' => 'Y',
    ),
    'USE_BUY1CLICK' => array(
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => getMessage('RS_SLINE.USE_BUY1CLICK'),
        'TYPE' => 'CHECKBOX',
        'VALUE' => 'Y',
        'DEFAULT' => 'Y',
    ),

    // OTHER
    'ICON_MEN_PROP' => array(
        'NAME' => getMessage('RS_SLINE.ICON_MEN_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
    ),
    'ICON_WOMEN_PROP' => array(
        'NAME' => getMessage('RS_SLINE.ICON_WOMEN_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
    ),
    'ICON_NOVELTY_PROP' => array(
        'NAME' => getMessage('RS_SLINE.ICON_NOVELTY_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
    ),
    'NOVELTY_TIME' => array(
        'NAME' => getMessage('RS_SLINE.NOVELTY_TIME'),
        'TYPE' => 'STRING',
        'DEFAULT' => '720',
    ),
    'ICON_DISCOUNT_PROP' => array(
        'NAME' => getMessage('RS_SLINE.ICON_DISCOUNT_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
    ),
    'ICON_DEALS_PROP' => array(
        'NAME' => getMessage('RS_SLINE.ICON_DEALS_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
    ),
    'ADDITIONAL_PICT_PROP' => array(
        'NAME' => getMessage('RS_SLINE.ADDITIONAL_PICT_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
    ),
    'ARTICLE_PROP' => array(
        'NAME' => getMessage('RS_SLINE.ARTICLE_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
    ),
    'DELIVERY_PROP' => array(
        'NAME' => getMessage('RS_SLINE.DELIVERY_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
    ),
    'BRAND_PROP' => array(
        'NAME' => getMessage('RS_SLINE.BRAND_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
        'REFRESH' => 'Y'
    ),
    'BRAND_LOGO_PROP' => array(
        'NAME' => getMessage('RS_SLINE.BRAND_LOGO_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
    ),
    'ACCESSORIES_PROP' => array(
        'NAME' => getMessage('RS_SLINE.ACCESSORIES_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
    ),
    'USE_QUANTITY_AND_STORES' => array(
        'NAME' => getMessage('RS_SLINE.USE_QUANTITY_AND_STORES'),
        'TYPE' => 'CHECKBOX',
        'VALUE' => 'Y',
        'DEFAULT' => 'Y',
    ),
    /*
    'USE_DELETE' => array(
        'NAME' => getMessage('RS_SLINE.USE_DELETE'),
        'TYPE' => 'CHECKBOX',
        'VALUE' => 'Y',
        'DEFAULT' => '',
    ),
    */
    'COUNT_RESULT_NOT_CATALOG' => array(
        'NAME' => getMessage('RS_SLINE.COUNT_RESULT_NOT_CATALOG'),
        'TYPE' => 'STRING',
        'DEFAULT' => '10',
    ),
    'SHOW_OLD_PRICE' => array(
        'PARENT' => 'PRICES',
        'NAME' => getMessage('RS_SLINE.SHOW_OLD_PRICE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y',
    ),
    'SHOW_DISCOUNT_PERCENT' => array(
        'PARENT' => 'PRICES',
        'NAME' => getMessage('RS_SLINE.SHOW_DISCOUNT_PERCENT'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    ),
);

if ($arCurrentValues['LIST_DISPLAY_PREVIEW_TEXT'] == 'Y') {
    $arTemplateParameters['PREVIEW_TRUNCATE_LEN'] = array(
        'PARENT' => 'ADDITIONAL_SETTINGS',
        'NAME' => GetMessage('RS_SLINE.PREVIEW_TRUNCATE_LEN'),
        'TYPE' => 'STRING',
        'DEFAULT' => '',
    );
}

$arTemplateParameters['DETAIL_DISPLAY_PREVIEW_TEXT_MODE'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_PREVIEW_TEXT_MODE'),
	'TYPE' => 'LIST',
	'VALUES' => array(
		'H' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_PREVIEW_TEXT_MODE_HIDE'),
		'E' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_PREVIEW_TEXT_MODE_EMPTY_DETAIL'),
		'S' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_PREVIEW_TEXT_MODE_SHOW')
	),
	'DEFAULT' => 'E'
);

if ($arCurrentValues['USE_AJAXPAGES'] == 'Y') {
    $arTemplateParameters['TEMPLATE_AJAXID'] = array(
        'PARENT' => 'PAGER_SETTINGS',
        'NAME' => getMessage('RS_SLINE.TEMPLATE_AJAXID'),
        'TYPE' => 'STRING',
        'DEFAULT' => 'ajaxpages_catalog_identifier',
    );
}

if ($arCurrentValues['USE_AJAXPAGES'] == 'Y') {
    $arTemplateParameters['USE_AUTO_AJAXPAGES'] = array(
        'PARENT' => 'PAGER_SETTINGS',
        'NAME' => getMessage('RS_SLINE.USE_AUTO_AJAXPAGES'),
        'TYPE' => 'CHECKBOX',
        'VALUE' => 'Y',
        'DEFAULT' => 'N',
    );
}

$arTemplateParameters['USE_LIKES'] = array(
    'NAME' => getMessage('RS_SLINE.USE_LIKES'),
    'TYPE' => 'CHECKBOX',
    'MULTIPLE' => 'N',
    'VALUE' => 'Y',
    'DEFAULT' =>'N',
    'REFRESH'=> 'Y',
);

if ($arCurrentValues['USE_FILTER'] == 'Y')
{
    $arTemplateParameters['INSTANT_RELOAD'] = array(
        'PARENT' => 'FILTER_SETTINGS',
        'NAME' => GetMessage('RS_SLINE.INSTANT_RELOAD'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
    );

    $arTemplateParameters['FILTER_COLLAPSE_ACTIVE'] = array(
        'PARENT' => 'FILTER_SETTINGS',
        'NAME' => GetMessage('RS_SLINE.FILTER_COLLAPSE_ACTIVE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y',
    );

    $arTemplateParameters['FILTER_SCROLL_PROPS'] = array(
        'PARENT' => 'FILTER_SETTINGS',
        'NAME' => getMessage('RS_SLINE.FILTER_SCROLL_PROPS'),
        'TYPE' => 'LIST',
        'MULTIPLE' => 'Y',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
    );

    // $arTemplateParameters['FILTER_PRICES_GROUPED'] = array(
        // 'PARENT' => 'FILTER_SETTINGS',
        // 'NAME' => getMessage('RS_SLINE.FILTER_PRICES_GROUPED'),
        // 'TYPE' => 'LIST',
        // 'MULTIPLE' => 'Y',
        // 'VALUES' => $arPrice,
    // );

    // $arTemplateParameters['FILTER_PRICES_GROUPED_FOR'] = array(
        // 'PARENT' => 'FILTER_SETTINGS',
        // 'NAME' => getMessage('RS_SLINE.FILTER_PRICES_GROUPED_FOR'),
        // 'TYPE' => 'LIST',
        // 'MULTIPLE' => 'N',
        // 'DEFAULT' => 'products',
        // 'VALUES' => $arPriceFor,
    // );

    $arTemplateParameters['FILTER_SEARCH_PROPS'] = array(
        'PARENT' => 'FILTER_SETTINGS',
        'NAME' => getMessage('RS_SLINE.FILTER_SEARCH_PROPS'),
        'TYPE' => 'LIST',
        'MULTIPLE' => 'Y',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
    );

    $arTemplateParameters['FEATURE_FILTER_USER_FIELDS'] = array(
        'PARENT' => 'FILTER_SETTINGS',
        'NAME' => GetMessage('RS_SLINE.FEATURE_FILTER_USER_FIELDS'),
        'TYPE' => 'LIST',
        'ADDITIONAL_VALUES' => 'Y',
        'VALUES' => $defaultValue + $arProperty_UF,
        'DEFAULT' => '-',
    );

}

if ($arCurrentValues['USE_LIKES'] == 'Y') {
    $arTemplateParameters['LIKES_COUNT_PROP'] = array(
        'NAME' => getMessage('RS_SLINE.LIKES_COUNT_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
    );
}


if ($arCurrentValues['BRAND_PROP'] != '-') {
    $arTemplateParameters['BRAND_IBLOCK_ID'] = array(
        'NAME' => getMessage('RS_SLINE.BRAND_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlock,
        'DEFAULT' => '',
        'REFRESH' => 'Y',
    );
    $BRAND_IBLOCK_ID = intval($arCurrentValues['BRAND_IBLOCK_ID']);
    if($BRAND_IBLOCK_ID){
        $arBrandProperty = array();
        if(0 < intval($IBLOCK_ID)){
            $rsBrandProp = CIBlockProperty::GetList(Array('sort' => 'asc', 'name' => 'asc'), Array('IBLOCK_ID' => $BRAND_IBLOCK_ID, 'ACTIVE' => 'Y'));
            while($arr = $rsBrandProp->Fetch()){
                $arBrandProperty[$arr['CODE']] = '['.$arr['CODE'].'] '.$arr['NAME'];
            }
        }
        $arTemplateParameters['BRAND_IBLOCK_BRAND_PROP'] = array(
            'NAME' => getMessage('RS_SLINE.BRAND_IBLOCK_BRAND_PROP'),
            'TYPE' => 'LIST',
            'VALUES' => $defaultValue + $arBrandProperty,
            'DEFAULT' => '-',
        );
    }
}

if ($arCurrentValues['SHOW_SECTION_PICTURE'] == 'Y'){
    $arTemplateParameters['SECTION_PICTURE_WIDTH'] = array(
        'PARENT' => 'LIST_SETTINGS',
        'NAME' => getMessage('RS_SLINE.SECTION_PICTURE_WIDTH'),
        'TYPE' => 'STRING',
        'DEFAULT' => '',
    );
    $arTemplateParameters['SECTION_PICTURE_HEIGHT'] = array(
        'PARENT' => 'LIST_SETTINGS',
        'NAME' => getMessage('RS_SLINE.SECTION_PICTURE_HEIGHT'),
        'TYPE' => 'STRING',
        'DEFAULT' => '',
    );
}

$arCurrentValues['DETAIL_PROPERTY_CODE'] = isset($arCurrentValues['DETAIL_PROPERTY_CODE']) ? $arCurrentValues['DETAIL_PROPERTY_CODE'] : array();
if (!empty($arCurrentValues['DETAIL_PROPERTY_CODE']))
{
	$selected = array();

	foreach ($arCurrentValues['DETAIL_PROPERTY_CODE'] as $code)
	{
		if (isset($arAllPropList[$code]))
		{
			$selected[$code] = $arAllPropList[$code];
		}
	}

	$arTemplateParameters['DETAIL_MAIN_BLOCK_PROPERTY_CODE'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_MAIN_BLOCK_PROPERTY_CODE'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'Y',
		'SIZE' => (count($selected) > 5 ? 8 : 3),
		'VALUES' => $selected
	);
}

$arOffers = CIBlockPriceTools::GetOffersIBlock($IBLOCK_ID);
$OFFERS_IBLOCK_ID = is_array($arOffers) ? $arOffers['OFFERS_IBLOCK_ID']: 0;

if ($OFFERS_IBLOCK_ID)
{
    $arProperty_Offers = array();
    $rsProp = CIBlockProperty::GetList(array('sort'=>'asc', 'name'=>'asc'), array('IBLOCK_ID'=>$OFFERS_IBLOCK_ID, 'ACTIVE'=>'Y'));
    while($arr=$rsProp->Fetch()){
        $arr['ID'] = intval($arr['ID']);
        if ($arOffers['OFFERS_PROPERTY_ID'] == $arr['ID'])
            continue;
        $strPropName = '['.$arr['ID'].']'.('' != $arr['CODE'] ? '['.$arr['CODE'].']' : '').' '.$arr['NAME'];
        if ('' == $arr['CODE'])
            $arr['CODE'] = $arr['ID'];
        $arProperty_Offers[$arr['CODE']] = $strPropName;
    }

    $arTemplateParameters['OFFER_ADDITIONAL_PICT_PROP'] = array(
        'PARENT' => 'OFFERS_SETTINGS',
        'NAME' => getMessage('RS_SLINE.OFFER_ADDITIONAL_PICT_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arProperty_Offers,
        'DEFAULT' => '-',
    );
    $arTemplateParameters['OFFER_ARTICLE_PROP'] = array(
        'PARENT' => 'OFFERS_SETTINGS',
        'NAME' => getMessage('RS_SLINE.OFFER_ARTICLE_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arProperty_Offers,
        'DEFAULT' => '-',
    );
    $arTemplateParameters['OFFER_TREE_PROPS'] = array(
        'PARENT' => 'OFFERS_SETTINGS',
        'NAME' => getMessage('RS_SLINE.OFFER_TREE_PROPS'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arProperty_Offers,
        'MULTIPLE' => 'Y',
        'DEFAULT' => '-',
    );
    $arTemplateParameters['OFFER_TREE_COLOR_PROPS'] = array(
        'PARENT' => 'OFFERS_SETTINGS',
        'NAME' => getMessage('RS_SLINE.OFFER_TREE_COLOR_PROPS'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arProperty_Offers,
        'MULTIPLE' => 'Y',
        'DEFAULT' => '-',
    );
    $arTemplateParameters['OFFER_TREE_BTN_PROPS'] = array(
        'PARENT' => 'OFFERS_SETTINGS',
        'NAME' => getMessage('RS_SLINE.OFFER_TREE_BTN_PROPS'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arProperty_Offers,
        'MULTIPLE' => 'Y',
        'DEFAULT' => '-',
    );

	if ($arCurrentValues['USE_FILTER'] == 'Y')
	{
		$arTemplateParameters['OFFER_FILTER_SCROLL_PROPS'] = array(
			'PARENT' => 'FILTER_SETTINGS',
			'NAME' => getMessage('RS_SLINE.OFFER_FILTER_SCROLL_PROPS'),
			'TYPE' => 'LIST',
			'VALUES' => $defaultValue + $arProperty_Offers,
			'MULTIPLE' => 'Y',
			'DEFAULT' => '-',
		);
		$arTemplateParameters['OFFER_FILTER_SEARCH_PROPS'] = array(
			'PARENT' => 'FILTER_SETTINGS',
			'NAME' => getMessage('RS_SLINE.OFFER_FILTER_SEARCH_PROPS'),
			'TYPE' => 'LIST',
			'VALUES' => $defaultValue + $arProperty_Offers,
			'MULTIPLE' => 'Y',
			'DEFAULT' => '-',
		);
	}
}

if (Bitrix\Main\ModuleManager::isModuleInstalled('sale')){
    /*$arTemplateParameters['USE_SALE_BESTSELLERS'] = array(
        'NAME' => getMessage('RS_SLINE.USE_SALE_BESTSELLERS'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    );*/

    $arTemplateParameters['USE_BIG_DATA'] = array(
        'PARENT' => 'BIG_DATA_SETTINGS',
        'NAME' => getMessage('RS_SLINE.USE_BIG_DATA'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y',
        'REFRESH' => 'Y'
    );
    if (!isset($arCurrentValues['USE_BIG_DATA']) || $arCurrentValues['USE_BIG_DATA'] == 'Y'){
        $rcmTypeList = array(
            'bestsell' => getMessage('RS_SLINE.RCM_BESTSELLERS'),
            'personal' => getMessage('RS_SLINE.RCM_PERSONAL'),
            'similar_sell' => getMessage('RS_SLINE.RCM_SOLD_WITH'),
            'similar_view' => getMessage('RS_SLINE.RCM_VIEWED_WITH'),
            'similar' => getMessage('RS_SLINE.RCM_SIMILAR'),
            'any_similar' => getMessage('RS_SLINE.RCM_SIMILAR_ANY'),
            'any_personal' => getMessage('RS_SLINE.RCM_PERSONAL_WBEST'),
            'any' => getMessage('RS_SLINE.RCM_RAND')
        );
        $arTemplateParameters['BIG_DATA_RCM_TYPE'] = array(
            'PARENT' => 'BIG_DATA_SETTINGS',
            'NAME' => getMessage('RS_SLINE.BIG_DATA_RCM_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $rcmTypeList
        );
        unset($rcmTypeList);
    }
}

$arTemplateParameters['USE_SHARE'] = array(
    'NAME' => getMessage('RS_SLINE.USE_SHARE'),
    'TYPE' => 'CHECKBOX',
    'MULTIPLE' => 'N',
    'VALUE' => 'Y',
    'DEFAULT' =>'N',
    'REFRESH'=> 'Y',
);

if ($arCurrentValues['USE_SHARE'] == 'Y') {

    $arSocialServices = array(
        'blogger' => getMessage('RS_SLINE.SOCIAL_SERVICES.BLOGGER'),
        'delicious' => getMessage('RS_SLINE.SOCIAL_SERVICES.DELICIOUS'),
        'digg' => getMessage('RS_SLINE.SOCIAL_SERVICES.DIGG'),
        'evernote' => getMessage('RS_SLINE.SOCIAL_SERVICES.EVERNOTE'),
        'facebook' => getMessage('RS_SLINE.SOCIAL_SERVICES.FACEBOOK'),
        'gplus' => getMessage('RS_SLINE.SOCIAL_SERVICES.GPLUS'),
        'linkedin' => getMessage('RS_SLINE.SOCIAL_SERVICES.LINKEDIN'),
        'lj' => getMessage('RS_SLINE.SOCIAL_SERVICES.LJ'),
        'moimir' => getMessage('RS_SLINE.SOCIAL_SERVICES.MOIMIR'),
        'odnoklassniki' => getMessage('RS_SLINE.SOCIAL_SERVICES.ODNOKLASSNIKI'),
        'pinterest' => getMessage('RS_SLINE.SOCIAL_SERVICES.PINTEREST'),
        'pocket' => getMessage('RS_SLINE.SOCIAL_SERVICES.POCKET'),
        'qzone' => getMessage('RS_SLINE.SOCIAL_SERVICES.QZONE'),
        'reddit' => getMessage('RS_SLINE.SOCIAL_SERVICES.REDDIT'),
        'renren' => getMessage('RS_SLINE.SOCIAL_SERVICES.RENREN'),
        'sinaWeibo ' => getMessage('RS_SLINE.SOCIAL_SERVICES.SINA_WEIBO'),
        'surfingbird' => getMessage('RS_SLINE.SOCIAL_SERVICES.SURFINGBIRD'),
        'telegram' => getMessage('RS_SLINE.SOCIAL_SERVICES.TELEGRAM'),
        'tencentWeibo' => getMessage('RS_SLINE.SOCIAL_SERVICES.TENCENT_WEIBO'),
        'tumblr' => getMessage('RS_SLINE.SOCIAL_SERVICES.TUMBLR'),
        'twitter' => getMessage('RS_SLINE.SOCIAL_SERVICES.TWITTER'),
        'viber' => getMessage('RS_SLINE.SOCIAL_SERVICES.VIBER'),
        'vkontakte' => getMessage('RS_SLINE.SOCIAL_SERVICES.VKONTAKTE'),
        'whatsapp' => getMessage('RS_SLINE.SOCIAL_SERVICES.WHATSAPP'),
    );

    $arSocialCopy = array(
        'first' => getMessage('RS_SLINE.SOCIAL_COPY.FIRST'),
        'last' => getMessage('RS_SLINE.SOCIAL_COPY.LAST'),
        'hidden' => getMessage('RS_SLINE.SOCIAL_COPY.HIDDEN'),
    );
    $arSocialSize = array(
        'm' => getMessage('RS_SLINE.SOCIAL_SIZE.M'),
        's' => getMessage('RS_SLINE.SOCIAL_SIZE.S'),
    );
    $arTemplateParameters['LIST_SOCIAL_SERVICES'] = array(
        'PARENT' => 'LIST_SETTINGS',
        'NAME' => getMessage('RS_SLINE.SOCIAL_SERVICES'),
        'TYPE' => 'LIST',
        'VALUES' => $arSocialServices,
        'MULTIPLE' => 'Y',
        'DEFAULT' => '',
        'ADDITIONAL_VALUES' => 'Y',
    );
    $arTemplateParameters['DETAIL_SOCIAL_SERVICES'] = array(
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => getMessage('RS_SLINE.SOCIAL_SERVICES'),
        'TYPE' => 'LIST',
        'VALUES' => $arSocialServices,
        'MULTIPLE' => 'Y',
        'DEFAULT' => '',
        'ADDITIONAL_VALUES' => 'Y',
    );
    $arTemplateParameters['SOCIAL_COUNTER'] = array(
        'NAME' => getMessage('RS_SLINE.SOCIAL_COUNTER'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
    );
    $arTemplateParameters['SOCIAL_COPY'] = array(
        'NAME' => getMessage('RS_SLINE.SOCIAL_COPY'),
        'TYPE' => 'LIST',
        'VALUES' => $arSocialCopy
    );
    $arTemplateParameters['SOCIAL_LIMIT'] = array(
        'NAME' => getMessage('RS_SLINE.SOCIAL_LIMIT'),
        'TYPE' => 'STRING',
        'DEFAULT' => '',
    );
    $arTemplateParameters['SOCIAL_SIZE'] = array(
        'NAME' => getMessage('RS_SLINE.SOCIAL_SIZE'),
        'TYPE' => 'LIST',
        'VALUES' => $arSocialSize
    );
}