<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

use \Bitrix\Iblock;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;
use \Bitrix\Iblock\PropertyTable;

if (
	!Loader::includeModule('redsign.pethouse') ||
	!Loader::includeModule('redsign.devfunc')
) {
	return;
}

$arParams['PREVIEW_TRUNCATE_LEN'] = intval($arParams['PREVIEW_TRUNCATE_LEN']);

if ($arParams['LIKES_COUNT_PROP'] == '') {
	$arParams['LIKES_COUNT_PROP'] = Option::get('redsign.pethouse', 'propcode_likes', 'LIKES_COUNT');
}

if ($arParams['POPUP_DETAIL_VARIABLE'] != 'ON_IMAGE' && $arParams['POPUP_DETAIL_VARIABLE'] != 'ON_LUPA') {
	$arParams['POPUP_DETAIL_VARIABLE' ] = 'ON_NONE';
}

if ($arParams['USE_AJAXPAGES'] == 'Y') {
	$arResult['AJAXPAGE_URL'] = $APPLICATION->GetCurPageParam('', array('ajaxpages', 'ajaxpagesid', 'PAGEN_'.($arResult['NAV_RESULT']->NavNum)));
}

if ($arParams['TEMPLATE_AJAXID'] == '') {
	$arParams['TEMPLATE_AJAXID'] = $this->getEditAreaId('catalog_ajax');
}

if (empty($arParams['CATALOG_FILTER_NAME']) || !preg_match('/^[A-Za-z_][A-Za-z01-9_]*$/', $arParams['CATALOG_FILTER_NAME'])) {
	$arParams['CATALOG_FILTER_NAME'] = 'arrFilter';
}

$bNeedColors = false;
$params = array(
	'PREVIEW_PICTURE' => true,
	'DETAIL_PICTURE' => true,
	'ADDITIONAL_PICT_PROP' => $arParams['ADDITIONAL_PICT_PROP'],
	'RESIZE' => array(
		0 => array(
			'MAX_WIDTH' => 210,
			'MAX_HEIGHT' => 210,
		)
	)
);

if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0)
{
	// new catalog components fix
	if (!isset($arResult['PRICES']))
	{
		$arResult['PRICES'] = CIBlockPriceTools::GetCatalogPrices($arParams['IBLOCK_ID'], $arParams['PRICE_CODE']);
	}

	foreach ($arResult['ITEMS'] as $iItemKey => $item)
	{
		if (is_array($item['OFFERS']) && count($item['OFFERS']) > 0)
		{
			if (!isset($item['OFFERS_SELECTED']))
			{
				$intSelected = -1;

				foreach ($item['OFFERS'] as $offerKey => $offer)
				{
					if ($item['OFFER_ID_SELECTED'] > 0)
					{
						$foundOffer = ($item['OFFER_ID_SELECTED'] == $offer['ID']);
					}
					else
					{
						$foundOffer = $offer['CAN_BUY'];
					}

					if ($foundOffer && $intSelected == -1)
					{
						$intSelected = $offerKey;
						break;
					}

					unset($foundOffer);
				}

				if ($intSelected == -1)
				{
					$intSelected = 0;
				}

				$arResult['ITEMS'][$iItemKey]['OFFERS_SELECTED'] = $intSelected;
			}
		}
	}

	if ($arParams['USE_PRICE_COUNT'])
	{
		$arPriceTypeID = array();
		foreach ($arResult['PRICES'] as $value)
		{
			$arPriceTypeID[] = $value['ID'];
		}

		$arElementsIDs = array();
		if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0)
		{
			foreach ($arResult['ITEMS'] as $iItemKey => $item)
			{
				$arElementsIDs[$item['ID']] = $iItemKey;
				if (is_array($item['OFFERS']) && count($item['OFFERS']) > 0)
				{
					foreach ($item['OFFERS'] as $iOfferKey => $arOffer)
					{
						// USE_PRICE_COUNT fix
						//if (!in_array($arOffer['ID'], $arElementsIDs)) {
						if (!isset($arElementsIDs[$arOffer['ID']]))
						{
							$arElementsIDs[$arOffer['ID']] = $iOfferKey;
						}
						else
						{
							unset($arResult['ITEMS'][$iItemKey]['OFFERS'][$iOfferKey]);
							if (isset($item['OFFERS_SELECTED']) && $item['OFFERS_SELECTED'] == $iOfferKey)
							{
								$arResult['ITEMS'][$iItemKey]['OFFERS_SELECTED'] = $arElementsIDs[$arOffer['ID']];
							}
						}
					}
				}
			}
		}

		$params['USE_PRICE_COUNT'] = $arParams['USE_PRICE_COUNT'];
		$params['FILTER_PRICE_TYPES'] = $arPriceTypeID;
		$params['CURRENCY_PARAMS'] = $arResult['CONVERT_CURRENCY'];
	}

	RSDevFunc::GetDataForProductItem($arResult['ITEMS'], $params);

	$obParser = new CTextParser;

	$arResult['BRANDS'] = array();
	$sBrandPropType = false;

	foreach ($arResult['ITEMS'] as $iItemKey => $item)
	{
		$sBrandPropCode = $arParams['BRAND_PROP'][$item['IBLOCK_ID']];

		if ($sBrandPropCode != '' && isset($item['PROPERTIES'][$sBrandPropCode]))
		{
			if (is_array($item['PROPERTIES'][$sBrandPropCode]['VALUE']))
			{
				foreach ($item['PROPERTIES'][$sBrandPropCode]['VALUE'] as $iPropValue => $sPropValue)
				{
					$arResult['BRANDS'][$sPropValue] = array();
				}
				unset($iPropValue, $sPropValue);
				
				$sBrandPropType = $item['PROPERTIES'][$sBrandPropCode]['PROPERTY_TYPE'];
			}
			elseif (strlen($item['PROPERTIES'][$sBrandPropCode]['VALUE']) > 0)
			{
				$arResult['BRANDS'][$item['PROPERTIES'][$sBrandPropCode]['VALUE']] = array();
				
				$sBrandPropType = $item['PROPERTIES'][$sBrandPropCode]['PROPERTY_TYPE'];
			}
		}

		if (is_array($item['OFFERS']) && count($item['OFFERS']) > 0)
		{
			if (!empty($arParams['OFFER_TREE_COLOR_PROPS'][$item['OFFERS'][$item['OFFERS_SELECTED']]['IBLOCK_ID']]))
			{
				foreach ($arParams['OFFER_TREE_COLOR_PROPS'][$item['OFFERS'][$item['OFFERS_SELECTED']]['IBLOCK_ID']] as $sPropCode)
				{
					if (
						isset($item['OFFERS'][$item['OFFERS_SELECTED']]['PROPERTIES'][$sPropCode]) &&
						(
							$item['OFFERS'][$item['OFFERS_SELECTED']]['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] != 'S' ||
							$item['OFFERS'][$item['OFFERS_SELECTED']]['PROPERTIES'][$sPropCode]['USER_TYPE'] != 'directory'
						)
					) {
						$bNeedColors = true;
						break;
					}
				}
				unset($sPropCode);
			}

			if (!empty($arParams['OFFER_TREE_PROPS'][$item['OFFERS'][$item['OFFERS_SELECTED']]['IBLOCK_ID']]))
			{
				$arResult['ITEMS'][$iItemKey]['OFFERS_EXT'] = RSDevFuncOffersExtension::GetSortedProperties(
					$item['OFFERS'],
					$arParams['OFFER_TREE_PROPS'][$item['OFFERS'][$item['OFFERS_SELECTED']]['IBLOCK_ID']],
					array('OFFERS_SELECTED' => $item['OFFERS_SELECTED'])
				);
			}
		}
		else
		{
			if ($arParams['ADD_PROPERTIES_TO_BASKET'] == 'Y'  && !empty($arParams["PRODUCT_PROPERTIES"]))
			{
				$arPropList = array();
				$arNeedValues = array();

				$arPropList = array();
				$arPropGroupList = array();

				foreach ($item['PRODUCT_PROPERTIES'] as $propID => $propInfo)
				{
					if (
						$item['PROPERTIES'][$propID]['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_STRING
						&& $item['PROPERTIES'][$propID]['USER_TYPE'] == 'directory'
					)
					{
						$arPropList[$propID] = $item['PROPERTIES'][$propID];

						RSDevFunc::getHighloadBlockValues($arPropList[$propID]);

						foreach ($propInfo['VALUES'] as $sPropCode => $spropVal)
						{
							if (is_array($arPropList[$propID]['VALUES']) && count($arPropList[$propID]['VALUES']) > 0)
							{
								foreach ($arPropList[$propID]['VALUES'] as $sPropId => $arPropVal)
								{
									if ($sPropCode == $arPropVal['XML_ID'])
									{
										$arResult['ITEMS'][$iItemKey]['PRODUCT_PROPERTIES'][$propID]['VALUES'][$sPropCode] = $arPropVal;

										if (!isset($arResult['ITEMS'][$iItemKey]['PRODUCT_PROPERTIES'][$propID]['GROUPS']))
										{
											$arResult['ITEMS'][$iItemKey]['PRODUCT_PROPERTIES'][$propID]['GROUPS'] = array(
												'DEFAULT' => array()
											);
										}

										if (strlen($arPropVal['DESCRIPTION']) <= 0)
										{
											$arResult['ITEMS'][$iItemKey]['PRODUCT_PROPERTIES'][$propID]['GROUPS']['DEFAULT'][] = $sPropCode;
										}
										else
										{
											$arResult['ITEMS'][$iItemKey]['PRODUCT_PROPERTIES'][$propID]['GROUPS'][$arPropVal['DESCRIPTION']][] = $sPropCode;
										}

										break;
									}
								}
								unset($sPropId, $arPropVal);
							}
						}
						unset($sPropCode, $spropVal);
					}
				}
				unset($propID, $propInfo);
			}
		}

		if ($arParams['DISPLAY_PREVIEW_TEXT'] == 'Y' && $arParams['PREVIEW_TRUNCATE_LEN'] > 0)
		{
			$arResult['ITEMS'][$iItemKey]['PREVIEW_TEXT'] = $obParser->html_cut($item['PREVIEW_TEXT'], $arParams['PREVIEW_TRUNCATE_LEN']);
		}

		$arResult['ITEMS'][$iItemKey]['FIRST_PIC'] = RSDevFunc::getElementPictures($arResult['ITEMS'][$iItemKey], $params, 1);
	}
	unset($iItemKey, $item);

	
	
	if (
		intval($arParams['BRAND_IBLOCK_ID']) > 0
		&& is_array($arResult['BRANDS']) && count($arResult['BRANDS']) > 0
	)
	{
		if ($sBrandPropType == PropertyTable::TYPE_ELEMENT)
		{
			$dbBrands = CIBlockElement::GetList(
				array(),
				$arFilter = array(
					'IBLOCK_ID' => $arParams['BRAND_IBLOCK_ID'],
					'=ID' => array_keys($arResult['BRANDS']),
				),
				false,
				false,
				array(
					'ID',
					'IBLOCK_ID',
					'NAME',
					'DETAIL_PAGE_URL',
					'PREVIEW_PICTURE',
				)
			);

			while ($arBrand = $dbBrands->GetNext())
			{
				if (intval($arBrand['PREVIEW_PICTURE']) > 0)
				{
					$arBrand['PREVIEW_PICTURE'] = CFile::GetFileArray($arBrand['PREVIEW_PICTURE']);
				}
				$arResult['BRANDS'][$arBrand['ID']] = $arBrand;
			}
		}
		elseif (
			$sBrandPropType == PropertyTable::TYPE_STRING
			&& strlen($arParams['BRAND_IBLOCK_BRAND_PROP']) > 0
		)
		{
			$dbBrands = CIBlockElement::GetList(
				array(),
				$arFilter = array(
					'IBLOCK_ID' => $arParams['BRAND_IBLOCK_ID'],
					'PROPERTY_'.$arParams['BRAND_IBLOCK_BRAND_PROP'] => array_keys($arResult['BRANDS']),
				),
				false,
				false,
				array(
					'ID',
					'IBLOCK_ID',
					'NAME',
					'DETAIL_PAGE_URL',
					'PREVIEW_PICTURE',
					'PROPERTY_'.$arParams['BRAND_IBLOCK_BRAND_PROP'],
				)
			);

			while ($arBrand = $dbBrands->GetNext())
			{
				if (intval($arBrand['PREVIEW_PICTURE']) > 0)
				{
					$arBrand['PREVIEW_PICTURE'] = CFile::GetFileArray($arBrand['PREVIEW_PICTURE']);
				}
				$arResult['BRANDS'][$arBrand['PROPERTY_'.$arParams['BRAND_IBLOCK_BRAND_PROP'].'_VALUE']] = $arBrand;
			}
		}
	}
	else
	{
		$arFilterProps = array(
			$arParams['BRAND_PROP'][$item['IBLOCK_ID']]
		);

		if (!isset($arResult['LIST_PAGE_URL']))
		{
			if (!empty($arParams['SECTIONS_URL'])) {
				$arResult['LIST_PAGE_URL'] = $arParams['SECTIONS_URL'];
			}
			else if (!empty($arParams['SECTION_URL']))
			{
				$arResult['LIST_PAGE_URL'] = $arParams['SECTION_URL'];
			}
		}

		foreach ($arFilterProps as $sPropCode)
		{
			if ($sPropCode != '' && isset($item['PROPERTIES'][$sPropCode]))
			{
				if (is_array($item['PROPERTIES'][$sPropCode]['VALUE']))
				{
					foreach ($item['PROPERTIES'][$sPropCode]['VALUE'] as $iPropValue => $sPropValue)
					{
						$arResult['ITEMS'][$iItemKey]['PROPERTIES'][$sPropCode]['FILTER_URL'][] = $arResult['LIST_PAGE_URL']
							.(strpos($arResult['LIST_PAGE_URL'], '?') === false ? '?' : '').$arParams['CATALOG_FILTER_NAME'].'_'
							.$item['PROPERTIES'][$sPropCode]['ID'].'_'
							.abs(crc32($item['PROPERTIES'][$sPropCode]['VALUE_ENUM_ID'][$iPropValue]
								? $item['PROPERTIES'][$sPropCode]['VALUE_ENUM_ID'][$iPropValue]
								: htmlspecialcharsbx($sPropValue)))
							.'=Y&set_filter=Y';
					}
				}
				else
				{
					$arResult['ITEMS'][$iItemKey]['PROPERTIES'][$sPropCode]['FILTER_URL'] = $arResult['LIST_PAGE_URL']
						.(strpos($arResult['LIST_PAGE_URL'], '?') === false ? '?' : '').$arParams['CATALOG_FILTER_NAME'].'_'
						.$item['PROPERTIES'][$sPropCode]['ID'].'_'
						.abs(crc32($item['PROPERTIES'][$sPropCode]['VALUE_ENUM_ID']
							? $item['PROPERTIES'][$sPropCode]['VALUE_ENUM_ID']
							: htmlspecialcharsbx($item['PROPERTIES'][$sPropCode]['VALUE'])))
						.'=Y&set_filter=Y';
				}
			}
		}

	}

	if ($bNeedColors)
	{
		$c = Option::get('redsign.pethouse', 'color_table_count', 0);
		$arResult['COLORS_TABLE']  = array();
		for ($i = 0; $i < $c; $i++)
		{
			$name = Option::get('redsign.pethouse', 'color_table_name_'.$i, '');
			$rgb = Option::get('redsign.pethouse', 'color_table_rgb_'.$i, '');
			if ($name != '' && $rgb != '')
			{
				$arResult['COLORS_TABLE'][ToUpper($name)] = array(
					'NAME' => $name,
					'RGB' => $rgb,
				);
			}
		}
	}
}
