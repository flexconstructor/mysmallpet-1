<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if (empty($arResult['OFFERS']))
{
	$HAVE_OFFERS = false;
	$PRODUCT = &$arResult;
}
else
{
	$HAVE_OFFERS = true;
	$PRODUCT = &$arResult['OFFERS'][0];
}

$bHaveOffer = false;
if (empty($arResult['OFFERS']))
{
	$arItemShow = &$arResult;
}
else
{
	$bHaveOffer = true;
	if (!$arResult['OFFERS_SELECTED'])
	{
		$arResult['OFFERS_SELECTED'] = 0;
	}
	$arItemShow = &$arResult['OFFERS'][$arResult['OFFERS_SELECTED']];
}

$sItemName = (0 < strlen($arItemShow['NAME']) ? $arItemShow['NAME'] : $arResult['NAME']);
$arPhotoChecked = false;
?><div class="catalog-element-head"><?
	?><h1><?=$sItemName?></h1><?
?></div><?
?><div class="rs_gallery"><?
	?><div class="rs_gallery-text"><?=$arResult['PREVIEW_TEXT']?></div><?
	?><div class="rs_gallery-thumbs"><?
		if ($bHaveOffer)
		{
			if (is_array($arItemShow['PRODUCT_PHOTO']) && 0 < count($arItemShow['PRODUCT_PHOTO']))
			{
				foreach ($arItemShow['PRODUCT_PHOTO'] as $arPhoto)
				{
					?><a class="rs_gallery-thumb rs_preview-wrap<?if(!$arPhotoChecked):?> checked<? $arPhotoChecked = $arPhoto; endif?>" href="<?=$arPhoto['SRC']?>"><?
						?><img class="rs_gallery-preview rs_preview" src="<?=$arPhoto['RESIZE']['preview']['src']?>" alt="<?=(0 < strlen($arPhoto['ALT']) ? $arPhoto['ALT'] : $sItemName)?>" /><?
						?><div class="overlay"></div><?
					?></a><?
				}
			}
			
		}

		if (is_array($arResult['PRODUCT_PHOTO']) && 0 < count($arResult['PRODUCT_PHOTO']))
		{
			foreach ($arResult['PRODUCT_PHOTO'] as $arPhoto)
			{
				?><a class="rs_gallery-thumb rs_preview-wrap<?if(!$arPhotoChecked):?> checked<? $arPhotoChecked = $arPhoto; endif?>" href="<?=$arPhoto['SRC']?>"><?
					?><img class="rs_gallery-preview rs_preview" src="<?=$arPhoto['RESIZE']['preview']['src']?>" alt="<?=(0 < strlen($arPhoto['ALT']) ? $arPhoto['ALT'] : $arResult['NAME'])?>" /><?
					?><div class="overlay"></div><?
				?></a><?
			}
		}
	?></div><?
	?><div class="rs_gallery-pic"><?
		if ($arPhotoChecked)
		{
			?><img class="rs_gallery-detal" src="<?=$arPhotoChecked['SRC']?>" alt="<?=(0 < strlen($arPhotoChecked['ALT']) ? $arPhotoChecked['ALT'] : $sItemName)?>" /><?
		}
		if ($arParams['DISPLAY_DATE'] != 'N' && $arResult['DISPLAY_ACTIVE_FROM'])
		{
			?><span class="date"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></span><?
		}
		?><div class="rs_gallery-prev multimage_icons"></div><?
		?><div class="rs_gallery-next multimage_icons"></div><?
	?></div><?
?></div>
<?