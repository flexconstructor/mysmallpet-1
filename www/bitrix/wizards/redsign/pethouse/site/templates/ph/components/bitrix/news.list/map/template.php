<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;

?>

<?php if (is_array($arResult["ITEMS"]) && count($arResult["ITEMS"]) > 0): ?>
    
    <div class="shops">
        <div class="shops-panel">
            <div class="row">
                <div class="col col-md-3 js-search_city">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="<?=Loc::getMessage('SHOP_SEARCH_PLACEHOLDER');?>">
                    </div>
                </div>
                <?php if (is_array($arResult['FILTER_TYPES']) && count($arResult['FILTER_TYPES']) > 1): ?>
                <div class="col col-md-9 js-shops">
                    <div class="shops-panel__filters js-filter">
                        <?php foreach ($arResult['FILTER_TYPES'] as $arFilterType): ?>
                            <div class="btn btn-grey js-btn" data-filter="<?=htmlspecialcharsbx($arFilterType['XML_ID'])?>"><?=$arFilterType['VALUE']?></div>
                        <?php endforeach; ?>
                        <div class="btn btn-grey active js-btn"  data-filter=""><?=Loc::getMessage('SHOP_FILTER_ALL');?></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col col-md-3">
                <div class="shops__list js-shops_list">
                    <?php foreach ($arResult["ITEMS"] as $arItem): ?>
                        <div
                            class="shops-item js-item"
                            data-coords="<?=$arItem['COORDINATES']?>"
                            data-id="<?=$arItem['ID']?>"
                            data-type="<?=$arItem['TYPE']?>"
                        >
                        <div class="shops-item__name js-item__name"><?=$arItem['NAME']?></div>
                            <div class="shops-item__descr js-item__descr"><?=$arItem['PREVIEW_TEXT']?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col col-md-9">
                <div class="map"><div class="shops__map" id="rsMapShops"></div></div>
            </div>
        </div>
    </div>
<?php endif; ?>
