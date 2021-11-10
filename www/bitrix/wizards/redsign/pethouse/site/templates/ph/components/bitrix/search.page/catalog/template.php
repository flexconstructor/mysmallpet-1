<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

use \Bitrix\Main\Localization\Loc;
?>

<?php $this->SetViewTarget('catalog_section_description'); ?>
<form action="" method="get" class="search__form">
    <div class="form-group form-inline">
        <input type="hidden" name="tags" value="<?=$arResult['REQUEST']['TAGS']?>">
        
        <div class="input-group">
            <button class="search__btn" for="#<?=$menuId?>" type="submit" value="">
                <svg class="icon-glass icon-svg"><use xlink:href="#svg-glass"></use></svg>
            </button>
            
            <?if($arParams["USE_SUGGEST"] === "Y"):
                if(strlen($arResult["REQUEST"]["~QUERY"]) && is_object($arResult["NAV_RESULT"]))
                {
                    $arResult["FILTER_MD5"] = $arResult["NAV_RESULT"]->GetFilterMD5();
                    $obSearchSuggest = new CSearchSuggest($arResult["FILTER_MD5"], $arResult["REQUEST"]["~QUERY"]);
                    $obSearchSuggest->SetResultCount($arResult["NAV_RESULT"]->NavRecordCount);
                }
                ?>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:search.suggest.input",
                    "al",
                    array(
                        "NAME" => "q",
                        "VALUE" => $arResult["REQUEST"]["~QUERY"],
                        "INPUT_SIZE" => 40,
                        "DROPDOWN_SIZE" => 10,
                        "FILTER_MD5" => $arResult["FILTER_MD5"],
                    ),
                    $component, array("HIDE_ICONS" => "Y")
                );?>
            <?else:?>
                <input class="search__input form-control" type="text" name="q" value="<?=$arResult["REQUEST"]["QUERY"]?>" size="40" />
            <?endif;?>
        </div>
    </div>
</form>
<?php $this->EndViewTarget(); ?>

<?php $this->SetViewTarget('catalog_search_other'); ?>
<div class="catalog_search_other context-wrap"><?
	$index = 0;
	foreach($arResult['SEARCH'] as $key => $arItem){
		if($arItem['PARAM1']!=$arParams['CATALOG_IBLOCK_TYPE']){
			if($index>=$arParams['COUNT_RESULT_NOT_CATALOG']){
				break;
			}
			?><div class="catalog_search_other-item"><?
				?><div class="catalog_search_other-item_inner"><?
					?><div class="catalog_search_other-item-pic"><?
						if(is_array($arItem['IMAGES']) && $arItem['IMAGES'][0]['src'] != '')
						?><a href="<?=$arItem['URL']?>"><?
							?><img src="<?=$arItem['IMAGES'][0]['src']?>" alt="" title="<?=$arItem['NAME']?>"><?
						?></a><?
					?></div><?
					?><div class="catalog_search_other-item-iblock_name"><a href="<?=$arItem['IBLOCK_LINK']?>"><?=$arResult['IBLOCKS'][$arItem['PARAM2']]['NAME']?></a></div><?
					?><div class="catalog_search_other-item-name"><a href="<?=$arItem['URL']?>" title="<?=$arItem['TITLE']?>"><?=$arItem['TITLE']?></a></div><?
				?></div><?
			?></div><?
			$index++;
		}
	}
?></div>
<?php $this->EndViewTarget(); ?>
