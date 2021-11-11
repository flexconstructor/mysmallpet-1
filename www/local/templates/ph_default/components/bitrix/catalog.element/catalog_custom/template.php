<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Web\Json;

$this->setFrameMode(true);

$request = Application::getInstance()->getContext()->getRequest();

if (!function_exists('getStringCatalogStoreAmountEx')) {
    function getStringCatalogStoreAmountEx($amount, $minAmount, $arReturn)
    {
        $amount = (float)$amount;
        $minAmount = (float)$minAmount;
        $message = $arReturn[1];

        if ($amount <= 0)
            $message = $arReturn[0];
        elseif ($amount >= $minAmount)
            $message = $arReturn[2];

        return $message;
    }
}

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
    'ID' => $mainId,
    'DISCOUNT_PERCENT_ID' => $mainId . '_dsc_pict',
    'STICKER_ID' => $mainId . '_sticker',
    'BIG_SLIDER_ID' => $mainId . '_big_slider',
    'BIG_IMG_CONT_ID' => $mainId . '_bigimg_cont',
    'SLIDER_CONT_ID' => $mainId . '_slider_cont',
    'OLD_PRICE_ID' => $mainId . '_old_price',
    'PRICE_ID' => $mainId . '_price',
    'DISCOUNT_PRICE_ID' => $mainId . '_price_discount',
    'PRICE_TOTAL' => $mainId . '_price_total',
    'SLIDER_CONT_OF_ID' => $mainId . '_slider_cont_',
    'QUANTITY_ID' => $mainId . '_quantity',
    'QUANTITY_DOWN_ID' => $mainId . '_quant_down',
    'QUANTITY_UP_ID' => $mainId . '_quant_up',
    'QUANTITY_MEASURE' => $mainId . '_quant_measure',
    'QUANTITY_LIMIT' => $mainId . '_quant_limit',
    'BUY_LINK' => $mainId . '_buy_link',
    'ADD_BASKET_LINK' => $mainId . '_add_basket_link',
    'BASKET_ACTIONS_ID' => $mainId . '_basket_actions',
    'NOT_AVAILABLE_MESS' => $mainId . '_not_avail',
    'COMPARE_LINK' => $mainId . '_compare_link',
    'TREE_ID' => $mainId . '_skudiv',
    'DISPLAY_PROP_DIV' => $mainId . '_sku_prop',
    'DISPLAY_MAIN_PROP_DIV' => $mainId . '_main_sku_prop',
    'OFFER_GROUP' => $mainId . '_set_group_',
    'BASKET_PROP_DIV' => $mainId . '_basket_prop',
    'SUBSCRIBE_LINK' => $mainId . '_subscribe',
    'TABS_ID' => $mainId . '_tabs',
    'TAB_CONTAINERS_ID' => $mainId . '_tab_containers',
    'SMALL_CARD_PANEL_ID' => $mainId . '_small_card_panel',
    'TABS_PANEL_ID' => $mainId . '_tabs_panel'
);

if ($arResult['PRODUCT_PHOTO']) {
    $templateData['PRODUCT_PHOTO'] = reset($arResult['PRODUCT_PHOTO']);
}

$bHaveOffer = false;
if (empty($arResult['OFFERS'])) {
    $arItemShow = &$arResult;
} else {
    $bHaveOffer = true;
    if (!$arResult['OFFERS_SELECTED']) {
        $arResult['OFFERS_SELECTED'] = 0;
    }
    $arItemShow = &$arResult['OFFERS'][$arResult['OFFERS_SELECTED']];
}


$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);

$arItemShowPrice = array();
if ($arParams['USE_PRICE_COUNT'] && is_array($arItemShow['PRICE_MATRIX']['COLS'])) {

    foreach ($arItemShow['PRICE_MATRIX']['COLS'] as $typeID => $arType) {

        $arRange = reset($arItemShow['PRICE_MATRIX']['MATRIX'][$typeID]);
        if ($arRange['MIN_PRICE'] != 'Y') {
            continue;
        }

        $arItemShowPrice = $arRange;
        $arItemShowPrice['PRICE_ID'] = $arType['ID'];
        $arItemShowPrice['DISCOUNT_VALUE'] = $arItemShowPrice['DISCOUNT_PRICE'];
        break;
    }
    unset($typeID, $arType, $arRange);

} else {
    foreach ($arItemShow['PRICES'] as $arPrice) {
        if ($arPrice['MIN_PRICE'] != 'Y') {
            continue;
        }
        $arItemShowPrice = $arPrice;
        break;
    }
}

$arSKU = array();

$arDisplayProperties = array();
if (is_array($arResult['DISPLAY_PROPERTIES']) && count($arResult['DISPLAY_PROPERTIES']) > 0) {

    $arDisplayProperties = array_diff_key(
        $arResult['DISPLAY_PROPERTIES'],
        is_array($arParams['TAB_IBLOCK_PROPS']) ? array_fill_keys($arParams['TAB_IBLOCK_PROPS'], 0) : array()
    // is_array($arParams['BLOCK_LINES_PROPERTIES']) ? array_fill_keys($arParams['BLOCK_LINES_PROPERTIES'], 0) : array()
    );
}

$iMainBlockProperiesCount = 0;
$showDisplayProperties = count($arDisplayProperties) > 0;

ob_start();
?>
    <div class="detail" itemscope itemtype="http://schema.org/Product">
<?php
$strEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$strDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$arDeleteParams = array('CONFIRM' => Loc::getMessage('RS_SLINE.BCE_CATALOG.ELEMENT_DELETE_CONFIRM'));

$this->AddEditAction($arResult['ID'], $arResult['EDIT_LINK'], $strEdit);
$this->AddDeleteAction($arResult['ID'], $arResult['DELETE_LINK'], $strDelete, $arDeleteParams);
$strMainID = $this->GetEditAreaId($arResult['ID']);
$sItemClass = 'detail__product product js-product row clearfix';
if (isset($arResult['DAYSARTICLE2']) || isset($arItemShow['DAYSARTICLE2'])) {
    $sItemClass .= ' da';
}
if (isset($arResult['QUICKBUY']) || isset($arItemShow['QUICKBUY'])) {
    $sItemClass .= ' qb';
}
?>
    <div class="<?= $sItemClass ?>"
         id="<?= $strMainID ?>"
         data-product-id="<?= $arResult['ID'] ?>"
         <?php if ($bHaveOffer): ?>data-offer-id="<?= $arItemShow['ID'] ?>"<?php endif; ?>
         data-detail="<?= $arResult['DETAIL_PAGE_URL'] ?>"
    >
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 pull-right">

            <?php
            $sBrandPropCode = $arParams['BRAND_PROP'][$arResult['IBLOCK_ID']];

            if (!empty($arResult['PROPERTIES'][$sBrandPropCode]['VALUE'])):
                ?>
                <div class="detail__brand">

                    <?php if (is_array($arResult['PROPERTIES'][$sBrandPropCode]['VALUE'])): ?>
                        <?php
                        echo implode(' / ', array_map(
                            function ($sName, $sLink) {
                                if (isset($arResult['BRANDS'][$sName])) {
                                    $sBrandUrl = $arResult['BRANDS'][$sName]['DETAIL_PAGE_URL'];
                                } else {
                                    $sBrandUrl = $sLink;
                                }
                                return '<a href="' . $sBrandUrl . '">' . $sName . '</a>';
                            },
                            $arResult['PROPERTIES'][$sBrandPropCode]['VALUE'],
                            $arResult['PROPERTIES'][$sBrandPropCode]['FILTER_URL']
                        ));
                        ?>
                    <?php else: ?>
                        <?php if (isset($arResult['BRANDS'][$arResult['PROPERTIES'][$sBrandPropCode]['VALUE']])): ?>

                            <?php $arBrand = $arResult['BRANDS'][$arResult['PROPERTIES'][$sBrandPropCode]['VALUE']]; ?>
                            <a href="<?= $arBrand['DETAIL_PAGE_URL'] ?>">
                                <?php if (is_array($arBrand['PREVIEW_PICTURE'])): ?>
                                    <img src="<?= $arBrand['PREVIEW_PICTURE']['SRC'] ?>" alt="<?= $arBrand['NAME'] ?>">
                                <?php
                                else:
                                    if (isset($arResult['DISPLAY_PROPERTIES'][$sBrandPropCode]['DISPLAY_VALUE'])) {
                                        echo $arResult['DISPLAY_PROPERTIES'][$sBrandPropCode]['DISPLAY_VALUE'];
                                    } else {
                                        echo $arResult['PROPERTIES'][$sBrandPropCode]['VALUE'];
                                    }
                                endif;
                                ?>
                            </a>
                            <?php unset($arBrand); ?>

                        <?php else: ?>
                            <a href="<?= $arResult['PROPERTIES'][$sBrandPropCode]['FILTER_URL'] ?>">
                                <?php if (isset($arResult['PROPERTIES'][$arParams['BRAND_LOGO_PROP'][$arResult['IBLOCK_ID']]]['PICT'])): ?>
                                    <img src="<?= $arResult['PROPERTIES'][$arParams['BRAND_LOGO_PROP'][$arResult['IBLOCK_ID']]]['PICT']['SRC'] ?>"
                                         alt="<?= $arResult['PROPERTIES'][$sBrandPropCode]['VALUE'] ?>">
                                <?php
                                else:
                                    if (isset($arResult['DISPLAY_PROPERTIES'][$sBrandPropCode]['DISPLAY_VALUE'])) {
                                        echo $arResult['DISPLAY_PROPERTIES'][$sBrandPropCode]['DISPLAY_VALUE'];
                                    } else {
                                        echo $arResult['PROPERTIES'][$sBrandPropCode]['VALUE'];
                                    }
                                endif;
                                ?>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="catalog__head">
                <h1 class="detail__name webpage__title js-product__name" itemprop="name">
                    <?php
                    echo(isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ''
                        ? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
                        : $arResult["NAME"]);
                    ?>
                </h1>
                <?php if ($arParams['USE_LIKES'] == 'Y'): ?>
                    <span class="detail__favorite favorite js-favorite">
					<svg class="favorite__icon icon icon-heart icon-svg"><use xlink:href="#svg-heart"></use></svg>
					<span class="favorite__cnt">
						<?php
                        if (intval($arResult['PROPERTIES'][$arParams['LIKES_COUNT_PROP']]['VALUE']) > 0) {
                            echo $arResult['PROPERTIES'][$arParams['LIKES_COUNT_PROP']]['VALUE'];
                        }
                        ?>
					</span>
				</span>
                <?php endif; ?>

                <div class="product__article">
                    <?php
                    if (
                        isset($arItemShow['PROPERTIES'][$arParams['ARTICLE_PROP'][$arItemShow['IBLOCK_ID']]]) &&
                        $arItemShow['PROPERTIES'][$arParams['ARTICLE_PROP'][$arItemShow['IBLOCK_ID']]]['VALUE'] != ''
                    ):
                        ?>
                        <span class="sku_prop__name"><?= Loc::getMessage('RS_SLINE.BCE_CATALOG.ITEM_ARTICLE') ?>:</span>
                        <span class="sku_prop__val_<?= $arItemShow['PROPERTIES'][$arParams['ARTICLE_PROP'][$arItemShow['IBLOCK_ID']]]['ID'] ?>"><?= $arItemShow['PROPERTIES'][$arParams['ARTICLE_PROP'][$arItemShow['IBLOCK_ID']]]['VALUE'] ?></span>
                    <?php
                    elseif (
                        isset($arResult['PROPERTIES'][$arParams['ARTICLE_PROP'][$arResult['IBLOCK_ID']]]) &&
                        $arResult['PROPERTIES'][$arParams['ARTICLE_PROP'][$arResult['IBLOCK_ID']]]['VALUE'] != ''
                    ):
                        ?>
                        <?= Loc::getMessage('RS_SLINE.BCE_CATALOG.ITEM_ARTICLE') ?>:
                        <span class="js_product-article"><?= $arResult['PROPERTIES'][$arParams['ARTICLE_PROP'][$arResult['IBLOCK_ID']]]['VALUE'] ?></span>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
            <div class="detail__picbox picbox">
                <div class="picbox__pic">
                    <div class="picbox__frame js-glass">
                        <div class="picbox__carousel">
                            <?php
                            $sDetailPictureClass = 'picbox__canvas';
                            if ($arParams['USE_PICTURE_ZOOM'] == 'Y') {
                                $sDetailPictureClass .= ' js-glass__canvas';
                            }

                            $bCarouselImgIsset = false;
                            $sCarouselDefaultHTML = '';
                            ?>
                            <?php if ($bHaveOffer): ?>
                                <?php foreach ($arResult['OFFERS'] as $iOfferKey => $arOffer): ?>
                                    <?php
                                    if (
                                        ($iOfferKey == $arResult['OFFERS_SELECTED'] || !$bCarouselImgIsset && $sCarouselDefaultHTML == '') &&
                                        is_array($arOffer['PRODUCT_PHOTO']) && count($arOffer['PRODUCT_PHOTO']) > 0
                                    ):
                                        ?>
                                        <?php
                                        if ($iOfferKey == $arResult['OFFERS_SELECTED']) {
                                            $bCarouselImgIsset = true;
                                        } else if ($sCarouselDefaultHTML == '') {
                                            ob_start();
                                        }

                                        if (!$templateData['PRODUCT_PHOTO']) {
                                            $templateData['PRODUCT_PHOTO'] = reset($arOffer['PRODUCT_PHOTO']);
                                        }
                                        ?>

                                        <?php foreach ($arOffer['PRODUCT_PHOTO'] as $arPhoto): ?>
                                        <?
                                        $strAlt = '';
                                        if (isset($arPhoto['ALT']) && $arPhoto['ALT'] != '') {
                                            $strAlt = $arPhoto['ALT'];
                                        } elseif (isset($arPhoto['DESCRIPTION']) && $arPhoto['DESCRIPTION'] != '') {
                                            $strAlt = $arPhoto['DESCRIPTION'];
                                        } elseif (
                                            isset($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']) &&
                                            $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] != ''
                                        ) {
                                            $strAlt = $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'];
                                        } else {
                                            $strAlt = $arResult['NAME'];
                                        }

                                        $strTitle = '';
                                        if (isset($arPhoto['TITLE']) && $arPhoto['TITLE'] != '') {
                                            $strTitle = $arPhoto['TITLE'];
                                        } elseif (isset($arPhoto['DESCRIPTION']) && $arPhoto['DESCRIPTION'] != '') {
                                            $strTitle = $arPhoto['DESCRIPTION'];
                                        } elseif (
                                            isset($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']) &&
                                            $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] != ''
                                        ) {
                                            $strTitle = $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'];
                                        } else {
                                            $strTitle = $arResult['NAME'];
                                        }
                                        ?>
                                        <a
                                                class="<?= $sDetailPictureClass ?>"
                                            <?php if ($arParams['USE_PICTURE_GALLERY'] == 'Y'): ?>
                                                data-fancybox="gallery" data-caption="<?= $strTitle ?>"
                                            <?php endif ?>
                                            <?php if ($arParams['USE_PICTURE_GALLERY'] == 'Y' || $arParams['USE_PICTURE_ZOOM'] == 'Y'): ?>
                                                href="<?= $arPhoto['SRC'] ?>"
                                            <?php endif ?>
                                                data-dot="<img class='owl-preview' src='<?= $arPhoto['RESIZE']['small']['src'] ?>'>"
                                                data-offer-id="<?= $arOffer['ID'] ?>"
                                        >
                                            <img class="picbox__img" src="<?= $arPhoto['RESIZE']['big']['src'] ?>"
                                                 alt="<?= $strAlt ?>" itemprop="image">
                                        </a>
                                    <?php endforeach; ?>

                                        <?php
                                        if (!$bCarouselImgIsset && $sCarouselDefaultHTML == '') {
                                            $sCarouselDefaultHTML = ob_get_clean();
                                        }
                                        ?>

                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <?php if (!empty($arResult['PRODUCT_PHOTO'])): ?>
                                <?php $bCarouselImgIsset = true; ?>
                                <?php foreach ($arResult['PRODUCT_PHOTO'] as $arPhoto): ?>
                                    <?
                                    $strAlt = '';
                                    if (isset($arPhoto['ALT']) && $arPhoto['ALT'] != '') {
                                        $strAlt = $arPhoto['ALT'];
                                    } elseif (isset($arPhoto['DESCRIPTION']) && $arPhoto['DESCRIPTION'] != '') {
                                        $strAlt = $arPhoto['DESCRIPTION'];
                                    } elseif (
                                        isset($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']) &&
                                        $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] != ''
                                    ) {
                                        $strAlt = $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'];
                                    } else {
                                        $strAlt = $arResult['NAME'];
                                    }

                                    $strTitle = '';
                                    if (isset($arPhoto['TITLE']) && $arPhoto['TITLE'] != '') {
                                        $strTitle = $arPhoto['TITLE'];
                                    } elseif (isset($arPhoto['DESCRIPTION']) && $arPhoto['DESCRIPTION'] != '') {
                                        $strTitle = $arPhoto['DESCRIPTION'];
                                    } elseif (
                                        isset($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']) &&
                                        $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] != ''
                                    ) {
                                        $strTitle = $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'];
                                    } else {
                                        $strTitle = $arResult['NAME'];
                                    }
                                    ?>
                                    <a class="<?= $sDetailPictureClass ?>"<?php if ($arParams['USE_PICTURE_GALLERY'] == 'Y' || $arParams['USE_PICTURE_ZOOM'] == 'Y'): ?> data-fancybox="gallery" data-caption="<?= $strTitle ?>" href="<?= $arPhoto['SRC'] ?>" <? endif ?>
                                       data-dot="<img class='owl-preview' src='<?= $arPhoto['RESIZE']['small']['src'] ?>'>">
                                        <img class="picbox__img" src="<?= $arPhoto['RESIZE']['big']['src'] ?>"
                                             data-large="<?= $arPhoto['SRC'] ?>" alt="<?= $strAlt ?>"
                                             title="<?= $strTitle ?>" itemprop="image">
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <?php if (!$bCarouselImgIsset): ?>
                                <?php if ($sCarouselDefaultHTML != ''): ?>
                                    <?= $sCarouselDefaultHTML ?>
                                <?php else: ?>
                                    <span class="<?= $sDetailPictureClass ?>"
                                          data-dot="<img class='owl-preview' src='<?= SITE_TEMPLATE_PATH ?>/assets/img/noimg.png'>">
								<img class="picbox__img" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/noimg.png">
							</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <span class="catalog__corner corner"><span class="corner__in"><span class="corner__text">
					<?php
                    if (isset($arResult['DAYSARTICLE2']) || isset($arItemShow['DAYSARTICLE2'])) {
                        echo Loc::getMessage('RS_SLINE.BCE_CATALOG.DAYSARTICLE');
                    } elseif (isset($arResult['QUICKBUY']) || isset($arItemShow['QUICKBUY'])) {
                        echo Loc::getMessage('RS_SLINE.BCE_CATALOG.QUICKBUY');
                    }
                    ?>
					</span></span></span>

                        <?php if (
                            isset($arResult['PROPERTIES'][$arParams['ICON_MEN_PROP'][$arResult['IBLOCK_ID']]]) &&
                            $arResult['PROPERTIES'][$arParams['ICON_MEN_PROP'][$arResult['IBLOCK_ID']]]['VALUE'] != '' ||
                            isset($arResult['PROPERTIES'][$arParams['ICON_WOMEN_PROP'][$arResult['IBLOCK_ID']]]) &&
                            $arResult['PROPERTIES'][$arParams['ICON_WOMEN_PROP'][$arResult['IBLOCK_ID']]]['VALUE'] != ''
                        ): ?>
                            <span class="detail__gender gender">

						<?php
                        if (
                            isset($arResult['PROPERTIES'][$arParams['ICON_MEN_PROP'][$arResult['IBLOCK_ID']]]) &&
                            $arResult['PROPERTIES'][$arParams['ICON_MEN_PROP'][$arResult['IBLOCK_ID']]]['VALUE'] != ''
                        ):
                            ?>
                            <svg class="icon icon-men icon-svg"><use xlink:href="#svg-men"></use></svg>
                        <?php endif; ?>

                                <?php
                                if (
                                    isset($arResult['PROPERTIES'][$arParams['ICON_WOMEN_PROP'][$arResult['IBLOCK_ID']]]) &&
                                    $arResult['PROPERTIES'][$arParams['ICON_WOMEN_PROP'][$arResult['IBLOCK_ID']]]['VALUE'] != ''
                                ):
                                    ?>
                                    <svg class="icon icon-women icon-svg"><use xlink:href="#svg-women"></use></svg>
                                <?php endif; ?>

						</span>
                        <?php endif; ?>

                        <span class="detail__stickers js_swap_hide"
						<?php if (isset($arResult['DAYSARTICLE2']) || isset($arResult['QUICKBUY'])): ?>
                            style="display:none;"
                        <?php endif; ?>
					>
						<?php
                        if (
                            $arParams['ICON_NOVELTY_PROP'][$arResult['IBLOCK_ID']]
                            && (
                                $arResult['PROPERTIES'][$arParams['ICON_NOVELTY_PROP'][$arResult['IBLOCK_ID']]]['VALUE'] == 'Y'
                                || $arResult['PROPERTIES'][$arParams['ICON_NOVELTY_PROP'][$arResult['IBLOCK_ID']]]['VALUE_XML_ID'] == 'yes'
                                || $arParams['NOVELTY_TIME'] && $arParams['NOVELTY_TIME'] >= (floor($_SERVER['REQUEST_TIME'] - MakeTimeStamp($arResult['DATE_ACTIVE_FROM'])) / 3600)
                            )
                        ):
                            ?>
                            <span class="sticker new">
								<span class="sticker__text">
									<?= $arResult['PROPERTIES'][$arParams['ICON_NOVELTY_PROP'][$arResult['IBLOCK_ID']]]['NAME'] ?>
								</span>
							</span>
                        <?php endif; ?>

                            <?php if ($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y' && $arItemShowPrice['DISCOUNT_DIFF_PERCENT'] > 0): ?>
                                <span class="sticker discount">
								<span class="sticker__text">
									<span class="price_pdp js-price_pdp-<?= $arItemShowPrice['PRICE_ID'] ?>"><?= $arItemShowPrice['DISCOUNT_DIFF_PERCENT'] . '%' ?></span>
								</span>
							</span>
                            <?php elseif (
                                $arParams['ICON_DISCOUNT_PROP'][$arResult['IBLOCK_ID']]
                                && (
                                    $arResult['PROPERTIES'][$arParams['ICON_DISCOUNT_PROP'][$arResult['IBLOCK_ID']]]['VALUE'] == 'Y'
                                    || $arResult['PROPERTIES'][$arParams['ICON_DISCOUNT_PROP'][$arResult['IBLOCK_ID']]]['VALUE_XML_ID'] == 'yes'
                                )
                            ): ?>
                                <span class="sticker discount">
								<span class="sticker__text">
									<?= $arResult['PROPERTIES'][$arParams['ICON_DISCOUNT_PROP'][$arResult['IBLOCK_ID']]]['NAME'] ?>
								</span>
							</span>
                            <?php endif; ?>

                            <?php
                            if (
                                $arParams['ICON_DEALS_PROP'][$arResult['IBLOCK_ID']]
                                && (
                                    $arResult['PROPERTIES'][$arParams['ICON_DEALS_PROP'][$arResult['IBLOCK_ID']]]['VALUE'] == 'Y'
                                    || $arResult['PROPERTIES'][$arParams['ICON_DEALS_PROP'][$arResult['IBLOCK_ID']]]['VALUE_XML_ID'] == 'yes'
                                )
                            ):
                                ?>
                                <span class="sticker action">
								<span class="sticker__text">
									<?= $arResult['PROPERTIES'][$arParams['ICON_DEALS_PROP'][$arResult['IBLOCK_ID']]]['NAME'] ?>
								</span>
							</span>
                            <?php endif; ?>
					</span>
                        <div class="js-glass__lupa"></div>
                    </div>

                    <div class="picbox__mini">
                        <div class="picbox__scroll">
                            <div class="picbox__dots"></div>
                        </div>
                        <div class="scroll-element picbox__bar">
                            <div class="scroll-arrow scroll-arrow_less">
                                <svg class="icon icon-left icon-svg">
                                    <use xlink:href="#svg-left"></use>
                                </svg>
                            </div>
                            <div class="scroll-arrow scroll-arrow_more">
                                <svg class="icon icon-right icon-svg">
                                    <use xlink:href="#svg-right"></use>
                                </svg>
                            </div>
                            <div class="scroll-element_outer">
                                <div class="scroll-element_size"></div>
                                <div class="scroll-element_track"></div>
                                <div class="scroll-bar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 pull-right">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 <?= $bHaveOffer ? 'col-lg-12' : 'col-lg-7' ?>">
                    <?php if ($bHaveOffer): ?>
                        <?php if (is_array($arResult['OFFERS_EXT']['PROPERTIES']) && 0 < count($arResult['OFFERS_EXT']['PROPERTIES'])): ?>
                            <div class="detail__offer_props js-product__offer_props clearfix">
                                <?php /*foreach ($arResult['OFFERS_EXT']['PROPERTIES'] as $sPropCode => $arProperty): */ ?><!--
								<?php
                                /*								$bIsColor = $bIsBtn = false;
                                                                $sOfferPropClass= 'offer_prop';
                                                                if (
                                                                    is_array($arParams['OFFER_TREE_COLOR_PROPS'][$arItemShow['IBLOCK_ID']]) &&
                                                                    in_array($sPropCode, $arParams['OFFER_TREE_COLOR_PROPS'][$arItemShow['IBLOCK_ID']])
                                                                ) {
                                                                    $bIsColor = true;
                                                                    $sOfferPropClass .= ' offer_prop-color';
                                                                } elseif (
                                                                    is_array($arParams['OFFER_TREE_BTN_PROPS'][$arItemShow['IBLOCK_ID']]) &&
                                                                    in_array($sPropCode, $arParams['OFFER_TREE_BTN_PROPS'][$arItemShow['IBLOCK_ID']])
                                                                ) {
                                                                    $bIsBtn = true;
                                                                    $sOfferPropClass .= ' offer_prop-btn';
                                                                }
                                                                */ ?>
								<?php /*if ($bIsColor || $bIsBtn): */ ?>
									<div class="<? /*=$sOfferPropClass*/ ?> js-offer_prop" data-code="<? /*=$sPropCode*/ ?>">
										<div class="offer_prop__name"><? /*=$arResult['OFFERS_EXT']['PROPS'][$sPropCode]['NAME']*/ ?>:</div>
										<ul class="offer_prop__values clearfix">
											<?php /*foreach ($arProperty as $value => $arValue): */ ?>
												<?php
                                /*												$sOfferPropValueClass = 'offer_prop__value';
                                                                                if ($arValue['FIRST_OFFER'] == 'Y') {
                                                                                    $sOfferPropValueClass .= ' checked';
                                                                                } elseif ($arValue['DISABLED_FOR_FIRST'] == 'Y') {
                                                                                    $sOfferPropValueClass .= ' disabled';
                                                                                }
                                                                                */ ?>
												<li class="<? /*=$sOfferPropValueClass*/ ?>" data-value="<? /*=htmlspecialcharsbx($arValue['VALUE'])*/ ?>">
													<?php /*if ($bIsColor): */ ?>
														<?php
                                /*														$sOfferPropIcon = is_array($arValue['PICT'])
                                                                                            ? 'background-image:url('.$arValue['PICT']['SRC'].')'
                                                                                            : 'background-color:'.$arResult['COLORS_TABLE'][ToUpper($arValue['VALUE'])]['RGB'];
                                                                                        */ ?>
															<span class="offer_prop__icon">
																<span class="offer_prop__img" title="<? /*=$arValue['VALUE']*/ ?>" style="<? /*=$sOfferPropIcon*/ ?>"></span>
															</span>
													<?php /*else: */ ?>
														<? /*=$arValue['VALUE']*/ ?>
													<?php /*endif; */ ?>
												</li>
											<?php /*endforeach; */ ?>
										</ul>
									</div>
								<?php /*else: */ ?>
									<?php /*$dropdownId = $this->getEditAreaId('offer_prop_'.$arResult['ID'].'_'.$arResult['OFFERS_EXT']['PROPS'][$sPropCode]['ID']) */ ?>
									<div class="offer_prop prop_<? /*=$sPropCode*/ ?> js-offer_prop" data-code="<? /*=$sPropCode*/ ?>">
										<div class="offer_prop__name"><? /*=$arResult['OFFERS_EXT']['PROPS'][$sPropCode]['NAME']*/ ?>:</div>
										<div class="dropdown select">
											<ul class="offer_prop__values dropdown-menu" aria-labelledby="<? /*=$dropdownId*/ ?>">
											<?php /*foreach ($arProperty as $value => $arValue): */ ?>
												<?php /*if($arValue['FIRST_OFFER'] == 'Y'): */ ?>
													<li class="offer_prop__value checked" data-value="<? /*=htmlspecialcharsbx($arValue['VALUE'])*/ ?>">
														<label><? /*=$arValue['VALUE']*/ ?></label>
														<?php /*ob_start(); */ ?>
															<span class="offer_prop__checked"><? /*=$arValue['VALUE']*/ ?></span>
														 <?php /*$sOfferPropChecked = ob_get_clean();*/ ?>
													</li>
												<?php /*else: */ ?>
													<li class="offer_prop__value<?php /*if ($arValue['DISABLED_FOR_FIRST'] == 'Y'): */ ?> disabled<?php /*endif; */ ?>" data-value="<? /*=htmlspecialcharsbx($arValue['VALUE'])*/ ?>">
														<label><? /*=$arValue['VALUE']*/ ?></label>
													</li>
												<?php /*endif; */ ?>
											<?php /*endforeach; */ ?>
											</ul>
											<label class="dropdown-toggle select__btn" id="<? /*=$dropdownId*/ ?>" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true" role="button">
												<svg class="select__icon icon icon-svg"><use xlink:href="#svg-down-round"></use></svg><? /*=$sOfferPropChecked*/ ?>
											</label>
										</div>
									</div>
								<?php /*endif; */ ?>
							--><?php /*endforeach; */ ?>

								<?//print_r($arResult['OFFERS']['PROPERTIES']);?>
                                    <table class="table offers_custom_table">
                                        <tbody>
                                        <?
                                        $offer_units = 'кг'; // default
                                        ?>
                                        <? foreach ($arResult['OFFERS'] as $k => $offer): ?>
                                        <?
                                            $offer_weight_val = $offer['DISPLAY_PROPERTIES']['WEIGHT']['DISPLAY_VALUE'];

                                            //$offer_weight = preg_replace("/[^0-9]/", '', $offer_weight_val);
                                            $offer_units_match = preg_match("/[а-яА-ЯёЁ]+/", $offer_weight_val, $offer_units);
                                            $offer_units = $offer_units[0];

											if( ! preg_match('/\*/', $offer_weight_val) ){
                                            	$offer_weight_match = preg_match("/^\d+[\.|\,]?\d*/", $offer_weight_val, $offer_weight);
                                            	$offer_weight = str_replace(',', '.', $offer_weight[0]);
											} else {
												$offer_weight_match = preg_match("/^\d+[\.|\,]?\d*/", $offer_weight_val, $offer_weight);
												$offer_weight_count = str_replace('кг.', '',substr(strrchr($offer_weight_val, "*"), 1) );
												$offer_weight = $offer_weight[0] * $offer_weight_count;
											}
												//$offer_weight = $offer['PROPERTIES']['MASSA_UPAKOVKI']['VALUE'];
                                            /*if(str_replace(array('гр', 'гр.'), '', $offer_weight_val) != $offer_weight_val){
                                                $offer_weight = $offer_weight / 1000;
                                            }*/
                                        ?>
                                            <tr>
                                                <td>
                                                    <div class="offer_rows">
                                                        <div class="offer_avail <?= $offer['CATALOG_QUANTITY'] > 0 ? 'available' : 'not_available' ?>"><?= $offer['CATALOG_QUANTITY'] > 0 ? 'В наличии' : 'Нет в наличии' ?></div>
                                                        <div class="offer_weight">
															<?//echo $offer_weight_count;?><?= $offer['DISPLAY_PROPERTIES']['WEIGHT']['DISPLAY_VALUE']; ?></div>
                                                        <div class="offer_article">
                                                            Арт: <?= $offer['DISPLAY_PROPERTIES']['ARTICLE_1']['VALUE'] ?></div>
                                                    </div>
                                                </td>
                                                <td class="offer_price">
                                                 <div class="catalog_item__price price<?php if ($arItemShowPrice['DISCOUNT_DIFF']): ?> price-disc<?php endif; ?> clearfix itdelta-price-disc">
                                                 <div class="price__pdv js-price_pdv-<?=$arItemShowPrice['PRICE_ID']?> blackPrice">
                                                   <?= $offer['PRICES']['BASE']['PRINT_DISCOUNT_VALUE_VAT'] ?>
                                                 </div>
                                                 <?php if ($arParams['SHOW_OLD_PRICE'] == 'Y'): ?>
                                                   <div class="price__pv js-price_pv-<?=$arItemShowPrice['PRICE_ID']?> itdelta-FullPrice">
                                                     <?php
                                                     if ($offer['PRICES']['BASE']['DISCOUNT_DIFF']) {
                                                       echo $offer['PRICES']['BASE']['PRINT_VALUE_VAT'] ;
                                                     }
                                                     ?>
                                                   </div>
                                                 <?php endif; ?>
                                                 </div>
                                               </td>
                                                <td class="offer_add_count"><? if (in_array($offer['ID'], $arResult['CURRENT_BASKET_ITEMS'])): ?>
                                                        <a class="btn" href="/personal/cart/">В корзине</a> <? else: ?>
                                                        <div class="add_to_cart-offer">
                                                            <button class="offer-minus">-</button>
                                                            <input value="0" class="offer_counter"
                                                                   product-weight="<?=$offer_weight?>"
                                                                   product-id="<?= $offer['ID'] ?>"
                                                                   product-price="<?= $offer['PRICES']['BASE']['VALUE'] ?>"
                                                                   readonly name="OFFER_COUNT" placeholder="0" min="0"
                                                                   max="<?= $offer['CATALOG_QUANTITY'] ?>"
                                                                   type="number">
                                                            <button class="offer-plus">+</button>
                                                        </div> <? endif; ?></td>
                                            </tr>
                                        <? endforeach; ?>
                                        <tr class="total_info">
                                            <td>
                                                <div class="total_div">Общий итог:
                                                    <div><span class="total_weight" id="total_weight">0</span><span
                                                                class="total_weight"> <?=$offer_units?>.</span></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="total_div">Сумма итого:
                                                    <div><span class="total_price" id="total_price">0</span><span
                                                                class="total_price"> руб.</span></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="total_div">Элементов в корзине:
                                                    <div><span class="total_count" id="total_count">0</span><span
                                                                class="total_count"> шт.</span></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <button class="detail__btn offers_to_cart_btn btn">
                                                    <svg class="icon icon-cart icon-svg">
                                                        <use xlink:href="#svg-cart"></use>
                                                    </svg>
                                                    В корзину
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                            </div>
                        <?php endif; ?>

                    <?php else: ?>

                        <?php $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']); ?>
                        <?php if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties): ?>

                            <div class="detail__product_props product_props">

                                <?php if (!empty($arResult['PRODUCT_PROPERTIES_FILL'])): ?>
                                    <?php foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo): ?>
                                        <input type="hidden"
                                               name="<?= $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<?= $propID; ?>]"
                                               value="<?= htmlspecialcharsbx($propInfo['ID']); ?>">
                                        <?php
                                        if (isset($arResult['PRODUCT_PROPERTIES'][$propID])) {
                                            unset($arResult['PRODUCT_PROPERTIES'][$propID]);
                                        }
                                        ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <?php $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']); ?>

                                <?php if (!$emptyProductProperties): ?>
                                    <?php foreach ($arResult['PRODUCT_PROPERTIES'] as $propID => $propInfo): ?>
                                        <?php
                                        $bIsColor = $bIsBtn = false;
                                        $sOfferPropClass = 'offer_prop';
                                        if (
                                            $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE'] == 'S' &&
                                            $arResult['PROPERTIES'][$propID]['USER_TYPE'] == 'directory'
                                        ) {
                                            $bIsColor = true;
                                            $sOfferPropClass .= ' offer_prop-color';
                                        }
                                        ?>
                                        <div class="<?= $sOfferPropClass ?>" data-code="<?= $propID ?>">
                                            <div class="offer_prop__name"><?= $arResult['PROPERTIES'][$propID]['NAME'] ?>
                                                :
                                            </div>
                                            <ul class="offer_prop__values clearfix">
                                                <?php
                                                if (
                                                    // 'L' == $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE']
                                                    // && in_array($arResult['PROPERTIES'][$propID]['LIST_TYPE'], array('L', 'C'))
                                                    // ||
                                                $bIsColor
                                                ):
                                                    ?>
                                                    <?php foreach ($propInfo['VALUES'] as $valueID => $arValue): ?>
                                                    <?php $sOfferPropValueClass = 'offer_prop__value'; ?>
                                                    <li class="<?= $sOfferPropValueClass ?>">
                                                        <label>
                                                            <input
                                                                    class="js-product_prop"
                                                                    type="radio"
                                                                    name="<?= $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<?= $propID; ?>]"
                                                                    value="<?= $valueID; ?>"
                                                                <?= ($valueID == $propInfo['SELECTED'] || !$propInfo['SELECTED'] && $bIsFirst ? 'checked="checked"' : ''); ?>
                                                            >
                                                            <?php if ($bIsColor): ?>
                                                                <?php
                                                                $sOfferPropIcon = is_array($arValue['PICT'])
                                                                    ? 'background-image:url(' . $arValue['PICT']['SRC'] . ')'
                                                                    : 'background-color:' . $arResult['COLORS_TABLE'][ToUpper($arValue['VALUE'])]['RGB'];
                                                                ?>
                                                                <span class="offer_prop__icon">
																	<span class="offer_prop__img"
                                                                          title="<?= $arValue['NAME'] ?>"
                                                                          style="<?= $sOfferPropIcon ?>"></span>
																</span>
                                                            <?php else: ?>
                                                                <?= $arValue ?>
                                                            <?php endif; ?>
                                                        </label>
                                                    </li>
                                                <?php endforeach; ?>

                                                <?php else: ?>
                                                    <?php $dropdownId = $this->getEditAreaId('offer_prop_' . $arResult['ID'] . '_' . $arResult['OFFERS_EXT']['PROPS'][$sPropCode]['ID']) ?>
                                                    <div class="dropdown select">
                                                        <ul class="offer_prop__values dropdown-menu"
                                                            aria-labelledby="<?= $dropdownId ?>">
                                                            <?php
                                                            $bIsFirst = true;
                                                            foreach ($propInfo['VALUES'] as $valueID => $value) {
                                                                if (
                                                                    $valueID == $propInfo['SELECTED']
                                                                    || !$propInfo['SELECTED'] && $bIsFirst
                                                                ) {
                                                                    ?>
                                                                    <li class="offer_prop__value checked">
                                                                        <label>
                                                                            <input
                                                                                    class="js-product_prop"
                                                                                    type="radio"
                                                                                    name="<?= $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<?= $propID; ?>]"
                                                                                    value="<?= $valueID; ?>"
                                                                                    checked="checked"
                                                                            >
                                                                            <?= $value; ?>
                                                                        </label>
                                                                        <?php ob_start(); ?>
                                                                        <span class="offer_prop__checked">
																<?= $value; ?>
																</span>
                                                                        <?php $sOfferPropChecked = ob_get_clean(); ?>
                                                                    </li>
                                                                    <?php
                                                                    $bIsFirst = false;
                                                                } else {
                                                                    ?>
                                                                    <li class="offer_prop__value">
                                                                        <label>
                                                                            <input
                                                                                    class="js-product_prop"
                                                                                    type="radio"
                                                                                    name="<?= $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<?= $propID; ?>]"
                                                                                    value="<?= $valueID; ?>"
                                                                            >
                                                                            <?= $value; ?>
                                                                        </label>
                                                                    </li>
                                                                    <?php
                                                                }
                                                            }
                                                            unset($bIsFirst);
                                                            ?>
                                                        </ul>
                                                        <label class="dropdown-toggle select__btn"
                                                               id="<?= $dropdownId ?>" data-toggle="dropdown"
                                                               aria-expanded="false" aria-haspopup="true" role="button">
                                                            <svg class="select__icon icon icon-svg">
                                                                <use xlink:href="#svg-down-round"></use>
                                                            </svg><?= $sOfferPropChecked ?>
                                                        </label>
                                                    </div>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    <?php endforeach; ?>

                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>

                    <?php
                    if (
                        $arResult['PREVIEW_TEXT'] != ''
                        && (
                            $arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'S'
                            || ($arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'E' && $arResult['DETAIL_TEXT'] == '')
                        )
                    ) {
                        ?>
                        <div>
                            <div class="detail__preview" itemprop="description">
                                <?= $arResult['PREVIEW_TEXT_TYPE'] === 'html' ? $arResult['PREVIEW_TEXT'] : $arResult['PREVIEW_TEXT'] ?>
                            </div>
                            <?php if ($arResult['DETAIL_TEXT'] != '') : ?>
                                <div>
                                    <a class="detail__preview_link anchor"
                                       href="#detail_info"><?= Loc::getMessage('RS_SLINE.BCE_CATALOG.MORE_LINK') ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?
                    }

                    if ($showDisplayProperties && count($arParams['MAIN_BLOCK_PROPERTY_CODE']) > 0 || $arResult['SHOW_OFFERS_PROPS']) {
                        ?>
                        <div class="product-item-detail-info-container">
                            <?
                            if ($showDisplayProperties) {

                                ?>
                                <div class="props_group">
                                    <table class="props_group__props">
                                        <tbody>
                                        <?
                                        foreach ($arDisplayProperties as $property) {
                                            if (in_array($property['CODE'], $arParams['MAIN_BLOCK_PROPERTY_CODE'])) {
                                                ?>
                                                <tr>
                                                    <th><?= $property['NAME'] ?>:</th>
                                                    <td><span><?= (is_array($property['DISPLAY_VALUE'])
                                                                ? implode(' / ', $property['DISPLAY_VALUE'])
                                                                : $property['DISPLAY_VALUE']) ?>
										</span></td>
                                                </tr>
                                                <?
                                                $iMainBlockProperiesCount++;
                                            }
                                        }
                                        unset($property);
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?
                            }
                            ?>

                            <?php if ($iMainBlockProperiesCount > 0 && $iMainBlockProperiesCount < count($arDisplayProperties)): ?>
                                <a class="anchor"
                                   href="#detail_props"><?= Loc::getMessage('RS_SLINE.BCE_CATALOG.MORE_LINK') ?></a>
                            <?php endif; ?>
                        </div>
                        <?
                    }
                    ?>
                </div>
                <?if(!$bHaveOffer):?>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-5">
                    <div class="detail__buy" itemprop="offers" itemscope
                         itemtype="http://schema.org/<?= ($bHaveOffer ? 'AggregateOffer' : 'Offer') ?>">

                        <?php if (!empty($arItemShowPrice)): ?>
                            <div class="detail__price price">
                                <?php if ($arParams['SHOW_OLD_PRICE'] == 'Y'): ?>
                                    <div class="price__pv js-price_pv-<?= $arItemShowPrice['PRICE_ID'] ?>">
                                        <?php
                                        if ($arItemShowPrice['DISCOUNT_DIFF']) {
                                            echo $arItemShowPrice['PRINT_VALUE'];
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                                <div class="price__pdv js-price_pdv-<?= $arItemShowPrice['PRICE_ID'] ?>"
                                     itemprop="<?= ($bHaveOffer ? 'lowPrice' : 'price') ?>"><?= $arItemShowPrice['PRINT_DISCOUNT_VALUE'] ?></div>
                                <? /*<meta itemprop="<?=($bHaveOffer ? 'lowPrice' : 'price')?>" content="<?=$arItemShowPrice['DISCOUNT_VALUE']?>">*/ ?>
                                <meta itemprop="priceCurrency" content="<?= $arItemShowPrice['CURRENCY'] ?>">
                            </div>
                        <?php endif; ?>


                        <?php if ($arParams['USE_STORE'] == 'Y'): ?>
                            <div class="detail__stocks">
                                <? $APPLICATION->IncludeComponent(
                                    "bitrix:catalog.store.amount",
                                    "al",
                                    array(
                                        "ELEMENT_ID" => $arResult['ID'],
                                        "STORE_PATH" => $arParams['STORE_PATH'],
                                        "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                                        "CACHE_TIME" => $arParams['CACHE_TIME'],
                                        "MAIN_TITLE" => $arParams['MAIN_TITLE'],
                                        "USE_MIN_AMOUNT" => 'N',
                                        "USE_MIN_AMOUNT_TMPL" => $arParams['USE_MIN_AMOUNT'],
                                        "MIN_AMOUNT" => $arParams['MIN_AMOUNT'],
                                        "STORES" => $arParams['STORES'],
                                        "SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
                                        "SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
                                        "USER_FIELDS" => $arParams['USER_FIELDS'],
                                        "FIELDS" => $arParams['FIELDS'],
                                        "OFFER_ID" => $arItemShow['ID']
                                    ),
                                    $component,
                                    array('HIDE_ICONS' => 'Y')
                                ); ?>
                            </div>
                        <?php elseif ($arParams['USE_QUANTITY_AND_STORES'] == 'Y'): ?>
                            <?php
                            $arMessage = array(getMessage('RS_SLINE.BCE_CATALOG.OUT_OF_STOCK'), getMessage('RS_SLINE.BCE_CATALOG.LIMITED_AVAILABILITY'), getMessage('RS_SLINE.BCE_CATALOG.IN_STOCK'));
                            $arClasses = array('is-outofstock', 'is-limited', 'is-instock');
                            $arSchemaAvailability = array('http://schema.org/OutOfStock', 'http://schema.org/LimitedAvailability', 'http://schema.org/InStock');
                            ?>
                            <div class="detail__stocks stocks tooltip">
                                <span><?= Loc::getMessage('RS_SLINE.BCE_CATALOG.QUANTITY') ?></span>
                                <span class="stocks__amount tooltip__link anchor"><?php
                                    echo ($arParams['USE_MIN_AMOUNT'] == 'Y')
                                        ? getStringCatalogStoreAmountEx($arItemShow['CATALOG_QUANTITY'], $arParams['MIN_AMOUNT'], $arMessage)
                                        : $arItemShow['CATALOG_QUANTITY'];
                                    ?></span><?php
                                ?>
                                <span class="stocks__sacle scale <?= getStringCatalogStoreAmountEx($arItemShow['CATALOG_QUANTITY'], $arParams['MIN_AMOUNT'], $arClasses) ?>">
								<svg class="scale__icon icon icon-scale icon-svg"><use
                                            xlink:href="#svg-scale"></use></svg>
								<span class="scale__over" <? if ($arItemShow['CATALOG_QUANTITY'] > 0) {
                                    echo ' style="width:100%"';
                                } ?>>
									<svg class="scale__icon icon icon-scale icon-svg"><use
                                                xlink:href="#svg-scale"></use></svg>
								</span>
							</span>
                                <link itemprop="availability"
                                      href="<?= getStringCatalogStoreAmountEx($arItemShow['CATALOG_QUANTITY'], $arParams['MIN_AMOUNT'], $arSchemaAvailability) ?>">
                            </div>
                        <?php endif; ?>

                        <script>
                            BX.message({
                                RS_SLINE_STOCK_IN_STOCK: '<?=GetMessageJS('RS_SLINE.BCE_CATALOG.IN_STOCK')?>',
                                RS_SLINE_STOCK_LIMITED_AVAILABILITY: '<?=GetMessageJS('RS_SLINE.BCE_CATALOG.LIMITED_AVAILABILITY')?>',
                                RS_SLINE_STOCK_OUT_OF_STOCK: '<?=GetMessageJS('RS_SLINE.BCE_CATALOG.OUT_OF_STOCK')?>',
                            });
                        </script>

                        <!--noindex-->
                        <form name="">
                            <input type="hidden" name="<?= $arParams['ACTION_VARIABLE'] ?>" value="ADD2BASKET">
                            <input type="hidden" name="<?= $arParams['PRODUCT_ID_VARIABLE'] ?>" class="js-product_id"
                                   value="<?= $arItemShow['ID'] ?>">

                            <?php if ($arParams['USE_PRODUCT_QUANTITY']): ?>
                                <div class="detail__quantity clearfix">
                                    <span><?= Loc::getMessage('RS_SLINE.BCE_CATALOG.PRODUCT_QUANTITY') ?></span>
                                    <div class="quantity<?php if (!$arItemShow['CAN_BUY']): ?> disabled<?php endif ?> clearfix">
                                        <i class="quantity__minus js-basket-minus"></i>
                                        <input
                                                type="number"
                                                class="quantity__val quantity__input js-quantity<?php if ($arParams['USE_PRICE_COUNT']): ?> js-use_count<?php endif; ?>"
                                                name="<?= $arParams['PRODUCT_QUANTITY_VARIABLE'] ?>"
                                                value="<?= $arItemShow['CATALOG_MEASURE_RATIO'] ?>"
                                                step="<?= $arItemShow['CATALOG_MEASURE_RATIO'] ?>"
                                                min="<?= $arItemShow['CATALOG_MEASURE_RATIO'] ?>"
                                            <? /* max="<?=$arItemShow['CATALOG_QUANTITY']?>"*/ ?>
                                        >
                                        <i class="quantity__plus js-basket-plus"></i>
                                    </div>
                                    <span class="detail__measure js-measure"><?= $arItemShow['CATALOG_MEASURE_NAME'] ?></span>
                                </div>
                            <?php endif; ?>

                            <div class="detail__btns">

                                <?php
                                if ($bHaveOffer) {
                                    $APPLICATION->includeComponent(
                                        'bitrix:catalog.product.subscribe',
                                        'al',
                                        array(
                                            'PRODUCT_ID' => $arResult['ID'],
                                            'OFFER_ID' => $arItemShow['ID'],
                                            'BUTTON_ID' => $strMainID . '_subscribe',
                                            'BUTTON_CLASS' => 'btn detail__subscr js-subscribe',
                                            'DEFAULT_DISPLAY' => !$arItemShow['CAN_BUY'],
                                        ),
                                        $component,
                                        array('HIDE_ICONS' => 'Y')
                                    );
                                } else {
                                    if ($arResult['CATALOG_SUBSCRIBE'] == 'Y') {
                                        if (!$arResult['CAN_BUY']) {
                                            $APPLICATION->includeComponent(
                                                'bitrix:catalog.product.subscribe',
                                                'al',
                                                array(
                                                    'PRODUCT_ID' => $arResult['ID'],
                                                    'BUTTON_ID' => $strMainID . '_subscribe',
                                                    'BUTTON_CLASS' => 'btn detail__subscr js-subscribe',
                                                    'DEFAULT_DISPLAY' => true,
                                                ),
                                                $component,
                                                array('HIDE_ICONS' => 'Y')
                                            );
                                        }
                                    }
                                }
                                ?>

                                <div id="<?= $itemIds['ADD_BASKET_LINK'] ?>">
                                    <a
                                            class="detail__btn added2cart btn"
                                            href="<?= $arParams['BASKET_URL'] ?>"
                                            title="<?= Loc::getMessage('RS_SLINE.BCE_CATALOG.MESS_BTN_IN_BASKET_TITLE') ?>"
                                            rel="nofollow"
                                    >
                                        <svg class="icon icon-incart icon-svg">
                                            <use xlink:href="#svg-incart"></use>
                                        </svg>
                                        <br/>
                                        <?= Loc::getMessage('RS_SLINE.BCE_CATALOG.MESS_BTN_IN_BASKET') ?>
                                    </a>

                                    <button
                                            class="detail__btn add2cart btn<?php if (!$arItemShow['CAN_BUY']): ?> disabled<?php endif; ?> js-add2cart"
                                            id="<?= $itemIds['ADD_BASKET_LINK'] ?>"
                                            type="submit"
                                        <?php if (!$arItemShow['CAN_BUY']): ?> disabled<?php endif; ?>
                                    >
                                        <svg class="icon icon-cart icon-svg">
                                            <use xlink:href="#svg-cart"></use>
                                        </svg><?= ($arParams['MESS_BTN_ADD_TO_BASKET'] != '' ? $arParams['MESS_BTN_ADD_TO_BASKET'] : Loc::getMessage('RS_SLINE.BCE_CATALOG.MESS_BTN_ADD_TO_BASKET')) ?>
                                    </button>
                                </div>

                                <?php
                                if ($arParams['USE_BUY1CLICK'] == 'Y'):
                                    $arBuy1click = array(
                                        'RS_EXT_FIELD_0' => '[' . $arItemShow['ID'] . '] ' . htmlspecialcharsbx($arItemShow['NAME']),
                                        'RS_ORDER_IDS' => $arItemShow['ID']
                                    );
                                    ?>
                                    <a class="detail__btn buy1click btn js-buy1click js-ajax_link <? if (!$arItemShow['CAN_BUY']) {
                                        ?> disabled<? } ?>"
                                       title="<?= Loc::getMessage('RS_SLINE.BCE_CATALOG.MESS_BTN_BUY1CLICK') ?>"
                                       data-fancybox="buy1click" data-type="ajax"
                                       data-insert_data="<?= htmlspecialcharsbx(\Bitrix\Main\Web\Json::encode($arBuy1click)) ?>"
                                       href="<?= SITE_DIR ?>buy1click/"
                                       data-name="<?= $arResult['NAME'] ?>"
                                       rel="nofollow"
                                    >
                                        <svg class="icon icon-phone icon-svg">
                                            <use xlink:href="#svg-phone-big"></use>
                                        </svg><?= Loc::getMessage('RS_SLINE.BCE_CATALOG.MESS_BTN_BUY1CLICK') ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </form>
                        <!--/noindex-->

                        <?php
                        if (isset($arResult['DAYSARTICLE2'])) {
                            $arTimers[] = $arResult['DAYSARTICLE2'];
                        }
                        if (isset($arResult['QUICKBUY'])) {
                            $arTimers[] = $arResult['QUICKBUY'];
                        }
                        if (is_array($arResult['OFFERS'])) {
                            foreach ($arResult['OFFERS'] as $arOffer) {
                                if (isset($arOffer['DAYSARTICLE2'])) {
                                    $arTimers[] = $arOffer['DAYSARTICLE2'];
                                }
                                if (isset($arOffer['QUICKBUY'])) {
                                    $arTimers[] = $arOffer['QUICKBUY'];
                                }
                            }
                        }

                        if (is_array($arTimers) && 0 < count($arTimers)):
                            $have_vis = false;

                            foreach ($arTimers as $arTimer):
                                $KY = 'TIMER';
                                if (isset($arTimer['DINAMICA_EX'])) {
                                    $KY = 'DINAMICA_EX';
                                }
                                $jsTimer = array(
                                    'DATE_FROM' => $arTimer[$KY]['DATE_FROM'],
                                    'DATE_TO' => $arTimer[$KY]['DATE_TO'],
                                    'AUTO_RENEWAL' => $arTimer['AUTO_RENEWAL'],
                                );
                                if (isset($arTimer['DINAMICA'])) {
                                    $jsTimer['DINAMICA_DATA'] = $arTimer['DINAMICA'] == 'custom' ? array_flip(unserialize($arTimer['DINAMICA_DATA'])) : $arTimer['DINAMICA'];
                                }
                                ?>
                                <div class="detail__timer timer js_timer timer_bg" style="display:<?
                                echo (($arResult['ID'] == $arTimer['ELEMENT_ID'] || $arItemShow['ID'] == $arTimer['ELEMENT_ID']) && !$have_vis) ? 'block' : 'none'
                                ?>;" data-offer-id="<?= $arTimer['ELEMENT_ID'] ?>"
                                     data-timer="<?= htmlspecialcharsbx(\Bitrix\Main\Web\Json::encode($jsTimer)) ?>">
                                    <div class="timer__data">
                                        <div class="timer__cell is-hidden">
                                            <div class="timer__val js_timer-d">00</div>
                                            <div class="timer__note"><?= Loc::getMessage('RS_SLINE.BCE_CATALOG.TIMER_DAY') ?></div>
                                        </div>
                                        <div class="timer__cell">
                                            <div class="timer__val js_timer-H">00</div>
                                            <div class="timer__note"><?= Loc::getMessage('RS_SLINE.BCE_CATALOG.TIMER_HOUR') ?></div>
                                        </div>
                                        <div class="timer__cell">
                                            <div class="timer__val js_timer-i">00</div>
                                            <div class="timer__note"><?= Loc::getMessage('RS_SLINE.BCE_CATALOG.TIMER_MIN') ?></div>
                                        </div>
                                        <div class="timer__cell">
                                            <div class="timer__val js_timer-s">00</div>
                                            <div class="timer__note"><?= Loc::getMessage('RS_SLINE.BCE_CATALOG.TIMER_SEC') ?></div>
                                        </div>
                                        <?php if (isset($arTimer['TIMER']) && $arQuickbuy['DATA']['QUANTITY'] > 0): ?>
                                            <div class="timer__cell timer__sep"></div>
                                            <div class="timer__cell">
                                                <div>
                                                    <div class="timer__val"><?= ($arTimer['QUANTITY'] > 99 ? $arTimer['QUANTITY'] : sprintf('%02d', $arTimer['QUANTITY'])) ?></div>
                                                    <div class="sq"><?= Loc::getMessage('RS_SLINE.BCE_CATALOG.TIMER_MEASURE') ?></div>
                                                </div>
                                                <div class="timer__note"><?= Loc::getMessage('RS_SLINE.BCE_CATALOG.TIMER_LEFT') ?></div>
                                            </div>
                                        <?php elseif (isset($arTimer['DINAMICA_EX'])): ?>
                                            <div class="timer__cell timer__sep"></div>
                                            <div class="timer__cell">
                                                <div class="timer__val js_timer-progress">0%</div>
                                                <div class="timer__note"><?= Loc::getMessage('RS_SLINE.BCE_CATALOG.TIMER_SOLD') ?></div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($arItemShowPrice['DISCOUNT_DIFF'] > 0): ?>
                                        <div class="timer__bottom">
                                            <?= Loc::getMessage('RS_SLINE.BCE_CATALOG.TIMER_PRICE_DIFF'); ?>
                                            <span class="discount"><?= $arItemShowPrice['PRINT_DISCOUNT_DIFF'] ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if ($arParams['USE_SHARE'] == 'Y'): ?>
                            <div class="detail__share ya-share2"
                                <?php if ($arParams['SOCIAL_COUNTER'] == 'Y'): ?>
                                    data-counter
                                <?php endif; ?>
                                <?php if ($arParams['SOCIAL_COPY'] != 'last'): ?>
                                    data-copy="<?= $arParams['SOCIAL_COPY'] ?>"
                                <?php endif; ?>
                                <?php if (intval($arParams['SOCIAL_LIMIT']) > 0): ?>
                                    data-limit="<?= $arParams['SOCIAL_LIMIT'] ?>"
                                <?php endif; ?>
                                <?php if (is_array($arParams['SOCIAL_SERVICES'])): ?>
                                    data-services="<?= implode(',', $arParams['SOCIAL_SERVICES']); ?>"
                                <?php endif; ?>
                                <?php if (intval($arParams['SOCIAL_SIZE']) > 0): ?>
                                    data-size="<?= $arParams['SOCIAL_SIZE'] ?>"
                                <?php endif; ?>
                                 data-lang="<?= LANGUAGE_ID ?>"
                                <? /*?> data-bare=""*/ ?>></div>
                        <?php endif; ?>

                        <?php if ($arParams['USE_KREDIT'] == 'Y' && $arParams['KREDIT_URL'] != ''): ?>
                            <a class="detail__credit" href="<?= $arParams['KREDIT_URL'] ?>">
                                <i class="icon-png"></i><?= Loc::getMessage('RS_SLINE.BCE_CATALOG.MESS_BTN_BUY_KREDIT') ?>
                            </a>
                        <?php endif; ?>

                        <?php
                        if (
                            isset($arResult['PROPERTIES'][$arParams['DELIVERY_PROP']]) &&
                            $arResult['DISPLAY_PROPERTIES'][$arParams['DELIVERY_PROP']]['DISPLAY_VALUE'] != ''
                        ):
                            ?>
                            <div class="detail__delivery" href="<?= $arParams['KREDIT_URL'] ?>">
                                <i class="icon-png"></i><?= $arResult['DISPLAY_PROPERTIES'][$arParams['DELIVERY_PROP']]['DISPLAY_VALUE'] ?>
                            </div>
                        <?php elseif ($arResult['PROPERTIES'][$arParams['DELIVERY_PROP']]['VALUE'] != ''): ?>
                            <div class="detail__delivery delivery">
                                <i class="icon-png"></i><?= $arResult['PROPERTIES'][$arParams['DELIVERY_PROP']]['VALUE'] ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($arParams['DISPLAY_COMPARE'] == 'Y'): ?>
                            <a class="cmp__link js-compare"
                               href="<?= str_replace('#ID#', $arItemShow['ID'], $arResult['COMPARE_URL_TEMPLATE']) ?>"
                               rel="nofollow">
                                <svg class="cmp__icon icon icon-cmp icon-svg">
                                    <use xlink:href="#svg-cmp"></use>
                                </svg><?= ($arParams['MESS_BTN_COMPARE'] != '' ? $arParams['MESS_BTN_COMPARE'] : Loc::getMessage('RS_SLINE.BCE_CATALOG.MESS_BTN_COMPARE')) ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?endif;?>
                <div class="col-xs-12">
                    <a class="detail__detail_link" href="<?= $arResult['DETAIL_PAGE_URL'] ?>">
                        <?= Loc::getMessage('GO2DETAIL_FROM_POPUP') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php $templateData['TEMPLATE_ELEMENT'] = ob_get_flush(); ?>

<?php
if ($arResult['CATALOG'] && $arItemShow['CAN_BUY'] && \Bitrix\Main\ModuleManager::isModuleInstalled('sale')) {
    // fix for ajax request for product preview
    if ($request->isAjaxRequest() && $request->get('fancybox') == 'true') {
        $server = Application::getInstance()->getContext()->getServer();
        $arServer = $arServerTmp = $server->toArray();
        unset($arServer['HTTP_BX_AJAX'], $arServer['HTTP_X_REQUESTED_WITH']);
        $server->set($arServer);
    }

    $APPLICATION->IncludeComponent(
        'bitrix:sale.prediction.product.detail',
        'al',
        array(
            'BUTTON_ID' => $itemIds['ADD_BASKET_LINK'],
            'POTENTIAL_PRODUCT_TO_BUY' => array(
                'ID' => isset($arResult['ID']) ? $arResult['ID'] : null,
                'MODULE' => isset($arResult['MODULE']) ? $arResult['MODULE'] : 'catalog',
                'PRODUCT_PROVIDER_CLASS' => isset($arResult['PRODUCT_PROVIDER_CLASS']) ? $arResult['PRODUCT_PROVIDER_CLASS'] : 'CCatalogProductProvider',
                'QUANTITY' => isset($arResult['QUANTITY']) ? $arResult['QUANTITY'] : null,
                'IBLOCK_ID' => isset($arResult['IBLOCK_ID']) ? $arResult['IBLOCK_ID'] : null,

                'PRIMARY_OFFER_ID' => isset($arResult['OFFERS'][0]['ID']) ? $arResult['OFFERS'][0]['ID'] : null,
                'SECTION' => array(
                    'ID' => isset($arResult['SECTION']['ID']) ? $arResult['SECTION']['ID'] : null,
                    'IBLOCK_ID' => isset($arResult['SECTION']['IBLOCK_ID']) ? $arResult['SECTION']['IBLOCK_ID'] : null,
                    'LEFT_MARGIN' => isset($arResult['SECTION']['LEFT_MARGIN']) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
                    'RIGHT_MARGIN' => isset($arResult['SECTION']['RIGHT_MARGIN']) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
                ),
            )
        ),
        $component,
        array('HIDE_ICONS' => 'Y')
    );

    // fix for ajax request for product preview
    if ($request->get('fancybox') == 'true') {
        $server->clear();
        $server->set($arServerTmp);
    }
}

if ($arResult['CATALOG'] && $arParams['USE_GIFTS_DETAIL'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled('sale')) {
    $APPLICATION->IncludeComponent(
        'bitrix:sale.gift.product',
        'al',
        array(
            'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
            'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
            'SUBSCRIBE_URL_TEMPLATE' => $arResult['~SUBSCRIBE_URL_TEMPLATE'],
            'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],

            'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
            'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
            'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
            'LINE_ELEMENT_COUNT' => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
            'HIDE_BLOCK_TITLE' => $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'],
            'BLOCK_TITLE' => $arParams['GIFTS_DETAIL_BLOCK_TITLE'],
            'TEXT_LABEL_GIFT' => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],
            'SHOW_NAME' => $arParams['GIFTS_SHOW_NAME'],
            'SHOW_IMAGE' => $arParams['GIFTS_SHOW_IMAGE'],
            'MESS_BTN_BUY' => $arParams['GIFTS_MESS_BTN_BUY'],

            'SHOW_PRODUCTS_' . $arParams['IBLOCK_ID'] => 'Y',
            'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
            'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
            'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
            'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
            'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
            'PRICE_CODE' => $arParams['PRICE_CODE'],
            'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
            'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
            'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
            'BASKET_URL' => $arParams['BASKET_URL'],
            'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
            'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
            'PARTIAL_PRODUCT_PROPERTIES' => $arParams['PARTIAL_PRODUCT_PROPERTIES'],
            'USE_PRODUCT_QUANTITY' => 'N',
            'OFFER_TREE_PROPS_' . $arResult['OFFERS_IBLOCK'] => $arParams['OFFER_TREE_PROPS'],
            'CART_PROPERTIES_' . $arResult['OFFERS_IBLOCK'] => $arParams['OFFERS_CART_PROPERTIES'],
            'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
            'POTENTIAL_PRODUCT_TO_BUY' => array(
                'ID' => isset($arResult['ID']) ? $arResult['ID'] : null,
                'MODULE' => isset($arResult['MODULE']) ? $arResult['MODULE'] : 'catalog',
                'PRODUCT_PROVIDER_CLASS' => isset($arResult['PRODUCT_PROVIDER_CLASS']) ? $arResult['PRODUCT_PROVIDER_CLASS'] : 'CCatalogProductProvider',
                'QUANTITY' => isset($arResult['QUANTITY']) ? $arResult['QUANTITY'] : null,
                'IBLOCK_ID' => isset($arResult['IBLOCK_ID']) ? $arResult['IBLOCK_ID'] : null,

                'PRIMARY_OFFER_ID' => isset($arResult['OFFERS'][0]['ID']) ? $arResult['OFFERS'][0]['ID'] : null,
                'SECTION' => array(
                    'ID' => isset($arResult['SECTION']['ID']) ? $arResult['SECTION']['ID'] : null,
                    'IBLOCK_ID' => isset($arResult['SECTION']['IBLOCK_ID']) ? $arResult['SECTION']['IBLOCK_ID'] : null,
                    'LEFT_MARGIN' => isset($arResult['SECTION']['LEFT_MARGIN']) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
                    'RIGHT_MARGIN' => isset($arResult['SECTION']['RIGHT_MARGIN']) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
                ),
            )
        ),
        $component,
        array('HIDE_ICONS' => 'Y')
    );
}

if ($arResult['CATALOG'] && $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled('sale')) {
    $APPLICATION->IncludeComponent(
        'bitrix:sale.gift.main.products',
        'al',
        array(
            'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
            'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
            'LINE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
            // 'HIDE_BLOCK_TITLE' => 'Y',
            'BLOCK_TITLE' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],

            'OFFERS_FIELD_CODE' => $arParams['OFFERS_FIELD_CODE'],
            'OFFERS_PROPERTY_CODE' => $arParams['OFFERS_PROPERTY_CODE'],

            'AJAX_MODE' => $arParams['AJAX_MODE'],
            'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
            'IBLOCK_ID' => $arParams['IBLOCK_ID'],

            'ELEMENT_SORT_FIELD' => 'ID',
            'ELEMENT_SORT_ORDER' => 'DESC',
            //'ELEMENT_SORT_FIELD2' => $arParams['ELEMENT_SORT_FIELD2'],
            //'ELEMENT_SORT_ORDER2' => $arParams['ELEMENT_SORT_ORDER2'],
            'FILTER_NAME' => 'searchFilter',
            'SECTION_URL' => $arParams['SECTION_URL'],
            'DETAIL_URL' => $arParams['DETAIL_URL'],
            'BASKET_URL' => $arParams['BASKET_URL'],
            'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
            'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
            'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],

            'CACHE_TYPE' => $arParams['CACHE_TYPE'],
            'CACHE_TIME' => $arParams['CACHE_TIME'],

            'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
            'SET_TITLE' => $arParams['SET_TITLE'],
            'PROPERTY_CODE' => $arParams['PROPERTY_CODE'],
            'PRICE_CODE' => $arParams['PRICE_CODE'],
            'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
            'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],

            'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
            'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
            'CURRENCY_ID' => $arParams['CURRENCY_ID'],
            'HIDE_NOT_AVAILABLE' => 'Y',
            'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
            'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
            'PRODUCT_BLOCKS_ORDER' => $arParams['GIFTS_PRODUCT_BLOCKS_ORDER'],

            'SHOW_SLIDER' => $arParams['GIFTS_SHOW_SLIDER'],
            'SLIDER_INTERVAL' => isset($arParams['GIFTS_SLIDER_INTERVAL']) ? $arParams['GIFTS_SLIDER_INTERVAL'] : '',
            'SLIDER_PROGRESS' => isset($arParams['GIFTS_SLIDER_PROGRESS']) ? $arParams['GIFTS_SLIDER_PROGRESS'] : '',

            'ADD_PICT_PROP' => (isset($arParams['ADD_PICT_PROP']) ? $arParams['ADD_PICT_PROP'] : ''),
            'LABEL_PROP' => (isset($arParams['LABEL_PROP']) ? $arParams['LABEL_PROP'] : ''),
            'LABEL_PROP_MOBILE' => (isset($arParams['LABEL_PROP_MOBILE']) ? $arParams['LABEL_PROP_MOBILE'] : ''),
            'LABEL_PROP_POSITION' => (isset($arParams['LABEL_PROP_POSITION']) ? $arParams['LABEL_PROP_POSITION'] : ''),
            'OFFER_ADD_PICT_PROP' => (isset($arParams['OFFER_ADD_PICT_PROP']) ? $arParams['OFFER_ADD_PICT_PROP'] : ''),
            'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS']) ? $arParams['OFFER_TREE_PROPS'] : ''),
            'SHOW_DISCOUNT_PERCENT' => (isset($arParams['SHOW_DISCOUNT_PERCENT']) ? $arParams['SHOW_DISCOUNT_PERCENT'] : ''),
            'DISCOUNT_PERCENT_POSITION' => (isset($arParams['DISCOUNT_PERCENT_POSITION']) ? $arParams['DISCOUNT_PERCENT_POSITION'] : ''),
            'SHOW_OLD_PRICE' => (isset($arParams['SHOW_OLD_PRICE']) ? $arParams['SHOW_OLD_PRICE'] : ''),
            'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
            'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
            'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
            'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
            'ADD_TO_BASKET_ACTION' => (isset($arParams['ADD_TO_BASKET_ACTION']) ? $arParams['ADD_TO_BASKET_ACTION'] : ''),
            'SHOW_CLOSE_POPUP' => (isset($arParams['SHOW_CLOSE_POPUP']) ? $arParams['SHOW_CLOSE_POPUP'] : ''),
            'DISPLAY_COMPARE' => (isset($arParams['DISPLAY_COMPARE']) ? $arParams['DISPLAY_COMPARE'] : ''),
            'COMPARE_PATH' => (isset($arParams['COMPARE_PATH']) ? $arParams['COMPARE_PATH'] : ''),
        )
        + array(
            'OFFER_ID' => empty($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'])
                ? $arResult['ID']
                : $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'],
            'SECTION_ID' => $arResult['SECTION']['ID'],
            'ELEMENT_ID' => $arResult['ID'],

            'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
            'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
            'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY']
        ),
        $component,
        array('HIDE_ICONS' => 'Y')
    );
}

$showDisplayProperties = $iMainBlockProperiesCount < count($arDisplayProperties);
?>

    <div class="detail__tabs col-xs-12">
    <ul class="nav-tabs" role="tablist">

        <?php if ($arResult['TABS']['DETAIL_TEXT']): ?>
            <li class="active">
                <a id="detail_info" rel="nofollow" href="#tab_detail"
                   data-toggle="tab"><?= Loc::getMessage('TAB_NAME_DESCRIPTION') ?></a>
            </li>
        <?php endif; ?>

        <?php if ($showDisplayProperties): ?>
            <li<?php if (!$arResult['TABS']['DETAIL_TEXT']): ?> class="active"<?php endif; ?>>
                <a id="detail_props" rel="nofollow" href="#tab_props"
                   data-toggle="tab"><?= Loc::getMessage('TAB_NAME_PROPERTIES') ?></a>
            </li>
        <?php endif; ?>

        <?php if ($arResult['TABS']['TAB_PROPERTIES']): ?>
            <?php foreach ($arParams['TAB_IBLOCK_PROPS'] as $iPropKey => $sPropCode): ?>
                <?php if ($sPropCode != '' && !empty($arResult['PROPERTIES'][$sPropCode]['VALUE'])): ?>
                    <li<?php if (!$arResult['TABS']['DETAIL_TEXT'] && !$showDisplayProperties && $iPropKey == 0): ?> class="active"<?php endif; ?>>
                        <a rel="nofollow" href="#tab_<?= strtolower($sPropCode) ?>"
                           data-toggle="tab"><?= $arResult['PROPERTIES'][$sPropCode]['NAME'] ?></a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($arResult['TABS']['REVIEW']): ?>
            <li<?php if (!$arResult['TABS']['DETAIL_TEXT'] && !$showDisplayProperties && !$arResult['TABS']['TAB_PROPERTIES']): ?> class="active"<?php endif; ?>>
                <a rel="nofollow" href="#tab_reviews" data-toggle="tab"><?= Loc::getMessage('TAB_NAME_REVIEWS') ?></a>
            </li>
        <?php endif; ?>
    </ul>

    <div class="tab-content">
<?php if ($arResult['TABS']['DETAIL_TEXT']): ?>
    <div id="tab_detail" class="tab-pane fade in active">
        <?= $arResult['DETAIL_TEXT'] ?>
    </div>
<?php endif; ?>

<?php if ($showDisplayProperties): ?>
    <div id="tab_props" class="tab-pane fade <?php if (!$arResult['TABS']['DETAIL_TEXT']): ?> in active<?php endif; ?>">
        <? $APPLICATION->IncludeComponent(
            'redsign:grupper.list',
            'al',
            array(
                'DISPLAY_PROPERTIES' => $arDisplayProperties,
                'CACHE_TYPE' => 'N',
            ),
            $component,
            array('HIDE_ICONS' => 'Y')
        ); ?>
    </div>
<?php endif; ?>

<?php if ($arResult['TABS']['TAB_PROPERTIES']): ?>
    <?php foreach ($arParams['TAB_IBLOCK_PROPS'] as $iPropKey => $sPropCode): ?>
        <?php if ($sPropCode != '' && !empty($arResult['PROPERTIES'][$sPropCode]['VALUE'])): ?>
            <div id="tab_<?= strtolower($sPropCode) ?>"
                 class="tab-pane fade <?php if (!$arResult['TABS']['DETAIL_TEXT'] && !$showDisplayProperties && $iPropKey == 0): ?> in active<?php endif; ?>">
                <?php if ($arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == 'F'): ?>
                    <div class="row">
                        <?php foreach ($arResult['PROPERTIES'][$sPropCode]['VALUE'] as $arDoc): ?>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="doc">
                                    <div class="doc__type">
                                        <i class="doc__icon <?= $arDoc['TYPE'] ?>"></i>
                                    </div>
                                    <div class="doc__inner">
                                        <a class="doc__name" href="<?= $arDoc['FULL_PATH'] ?>" target="_blank"
                                           rel="nofollow">
                                            <?= ($arDoc['DESCRIPTION'] == '' ? $arDoc['ORIGINAL_NAME'] : $arDoc['DESCRIPTION']) ?>
                                        </a>
                                        <div class="doc__size">(<? if ($arDoc['TYPE2'] != '') {
                                                echo $arDoc['TYPE2'] . ', ';
                                            }
                                            echo $arDoc['SIZE'] ?>)
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php
                elseif (
                    $arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == 'E' &&
                    count($arResult['PROPERTIES'][$sPropCode]['VALUE']) > 0
                ):
                    ?>

                    <?php
                    $IBLOCK_ID = $arResult['PROPERTIES'][$sPropCode]['IBLOCK_ID'];
                    if (!isset($arSKU[$IBLOCK_ID])) {
                        $arSKU[$IBLOCK_ID] = CCatalogSKU::GetInfoByProductIBlock($IBLOCK_ID);
                    }
                    ?>

                    <? $APPLICATION->IncludeComponent(
                    "bitrix:catalog.recommended.products",
                    "al",
                    array(
                        "LINE_ELEMENT_COUNT" => $arParams["ALSO_BUY_ELEMENT_COUNT"],
                        "ID" => $arResult['ID'],
                        "PROPERTY_LINK" => $sPropCode,
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "BASKET_URL" => $arParams["BASKET_URL"],
                        "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                        "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                        "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                        "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                        "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                        "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                        "PAGE_ELEMENT_COUNT" => $arParams["ALSO_BUY_ELEMENT_COUNT"],
                        "SHOW_OLD_PRICE" => "Y",//need
                        "SHOW_DISCOUNT_PERCENT" => "Y",//need
                        "PRICE_CODE" => $arParams["PRICE_CODE"],
                        "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                        "PRODUCT_SUBSCRIPTION" => 'N',
                        "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                        "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                        "SHOW_NAME" => "Y",
                        "SHOW_IMAGE" => "Y",
                        "MESS_BTN_BUY" => $arParams['MESS_BTN_BUY'],
                        "MESS_BTN_DETAIL" => $arParams["MESS_BTN_DETAIL"],
                        "MESS_NOT_AVAILABLE" => $arParams['MESS_NOT_AVAILABLE'],
                        "MESS_BTN_SUBSCRIBE" => $arParams['MESS_BTN_SUBSCRIBE'],
                        "SHOW_PRODUCTS_" . $arParams["IBLOCK_ID"] => "Y",
                        "HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
                        "OFFER_TREE_PROPS_" . $arSKU[$IBLOCK_ID]['IBLOCK_ID'] => $arParams["OFFER_TREE_PROPS"][$arSKU[$IBLOCK_ID]['IBLOCK_ID']],
                        "OFFER_TREE_COLOR_PROPS_" . $arSKU[$IBLOCK_ID]['IBLOCK_ID'] => $arParams["OFFER_TREE_COLOR_PROPS"][$arSKU[$IBLOCK_ID]['IBLOCK_ID']],
                        "ADDITIONAL_PICT_PROP_" . $IBLOCK_ID => $arParams['ADDITIONAL_PICT_PROP'][$arParams['IBLOCK_ID']],
                        "PROPERTY_CODE_" . $arParams['IBLOCK_ID'] => $arParams["LIST_PROPERTY_CODE"],
                        "BRAND_PROP_" . $arParams['IBLOCK_ID'] => $arParams['BRAND_PROP'][$arParams['IBLOCK_ID']],
                        "ICON_NOVELTY_PROP_" . $arParams['IBLOCK_ID'] => $arParams['ICON_NOVELTY_PROP'][$arParams['IBLOCK_ID']],
                        "ICON_DEALS_PROP_" . $arParams['IBLOCK_ID'] => $arParams['ICON_DEALS_PROP'][$arParams['IBLOCK_ID']],
                        "ICON_DISCOUNT_PROP_" . $arParams['IBLOCK_ID'] => $arParams['ICON_DISCOUNT_PROP'][$arParams['IBLOCK_ID']],
                        "ICON_MEN_PROP_" . $arParams['IBLOCK_ID'] => $arParams['ICON_MEN_PROP'][$arParams['IBLOCK_ID']],
                        "ICON_WOMEN_PROP_" . $arParams['IBLOCK_ID'] => $arParams['ICON_WOMEN_PROP'][$arParams['IBLOCK_ID']],
                        "ADDITIONAL_PICT_PROP_" . $arSKU[$IBLOCK_ID]['IBLOCK_ID'] => $arParams['OFFER_ADDITIONAL_PICT_PROP'],
                        "PROPERTY_CODE_" . $arSKU[$IBLOCK_ID]['IBLOCK_ID'] => $arParams["LIST_OFFERS_PROPERTY_CODE"],
                        "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
                        "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                        'USE_LIKES' => $arParams['USE_LIKES'],
                        'USE_SHARE' => $arParams['USE_SHARE'],
                        'SOCIAL_SERVICES' => $arParams['LIST_SOCIAL_SERVICES'],
                        'SOCIAL_COUNTER' => $arParams['SOCIAL_COUNTER'],
                        'SOCIAL_COPY' => $arParams['SOCIAL_COPY'],
                        'SOCIAL_LIMIT' => $arParams['SOCIAL_LIMIT'],
                        'SOCIAL_SIZE' => $arParams['SOCIAL_SIZE'],
                        'POPUP_DETAIL_VARIABLE' => $arParams['POPUP_DETAIL_VARIABLE'],
                        'BRAND_IBLOCK_ID' => $arParams['BRAND_IBLOCK_ID'],
                        'BRAND_IBLOCK_BRAND_PROP' => $arParams['BRAND_IBLOCK_BRAND_PROP'],
                    ),
                    $component,
                    array('HIDE_ICONS' => 'Y')
                ); ?>

                <?php elseif ($arResult['PROPERTIES'][$sPropCode]['MULTIPLE'] == 'Y'): ?>
                    <div class="props_group">
                        <div class="props_group__name"><?= $arrValue['GROUP']['NAME'] ?></div>
                        <table class="props_group__props">
                            <tbody>
                            <?php foreach ($arResult['PROPERTIES'][$sPropCode]['VALUE'] as $iPropKey => $sProp): ?>
                                <tr>
                                    <th><?= $arResult['PROPERTIES'][$sPropCode]["DESCRIPTION"][$iPropKey] ?></th>
                                    <td><span><?= $sProp ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <?php
                    if ($arResult['PROPERTIES'][$sPropCode]['VALUE']['TYPE'] == 'text') {
                        echo $arResult['PROPERTIES'][$sPropCode]['VALUE']['TEXT'];
                    } else {
                        echo $arResult['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'];
                    }
                    ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php endforeach; ?>
<?php endif; ?>
<? // --> component_epilog.php continue ?>

<?php
$this->SetViewTarget('rs_detail-linked_items');
foreach ($arParams['LINKED_ITEMS_PROPS'] as $sPropCode) {
    if (!empty($arResult['PROPERTIES'][$sPropCode]['VALUE'])) {
        $IBLOCK_ID = $arResult['PROPERTIES'][$sPropCode]['IBLOCK_ID'];
        if (!isset($arSKU[$IBLOCK_ID])) {
            $arSKU[$IBLOCK_ID] = CCatalogSKU::GetInfoByProductIBlock($IBLOCK_ID);
        }

        $APPLICATION->IncludeComponent(
            "bitrix:catalog.recommended.products",
            "al",
            array(
                "LINE_ELEMENT_COUNT" => $arParams["ALSO_BUY_ELEMENT_COUNT"],
                "ID" => $arResult['ID'],
                "PROPERTY_LINK" => $sPropCode,
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "BASKET_URL" => $arParams["BASKET_URL"],
                "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                "PAGE_ELEMENT_COUNT" => $arParams["ALSO_BUY_ELEMENT_COUNT"],
                "SHOW_OLD_PRICE" => "Y",//need
                "SHOW_DISCOUNT_PERCENT" => "Y",//need
                "PRICE_CODE" => $arParams["PRICE_CODE"],
                "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                "PRODUCT_SUBSCRIPTION" => 'N',
                "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                "SHOW_NAME" => "Y",
                "SHOW_IMAGE" => "Y",
                "MESS_BTN_BUY" => $arParams['MESS_BTN_BUY'],
                "MESS_BTN_DETAIL" => $arParams["MESS_BTN_DETAIL"],
                "MESS_NOT_AVAILABLE" => $arParams['MESS_NOT_AVAILABLE'],
                "MESS_BTN_SUBSCRIBE" => $arParams['MESS_BTN_SUBSCRIBE'],
                "SHOW_PRODUCTS_" . $arParams["IBLOCK_ID"] => "Y",
                "HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
                "OFFER_TREE_PROPS_" . $arSKU[$IBLOCK_ID]['IBLOCK_ID'] => $arParams["OFFER_TREE_PROPS"][$arSKU[$IBLOCK_ID]['IBLOCK_ID']],
                "OFFER_TREE_COLOR_PROPS_" . $arSKU[$IBLOCK_ID]['IBLOCK_ID'] => $arParams["OFFER_TREE_COLOR_PROPS"][$arSKU[$IBLOCK_ID]['IBLOCK_ID']],
                "ADDITIONAL_PICT_PROP_" . $IBLOCK_ID => $arParams['ADDITIONAL_PICT_PROP'][$arParams['IBLOCK_ID']],
                "PROPERTY_CODE_" . $arParams['IBLOCK_ID'] => $arParams["LIST_PROPERTY_CODE"],
                "BRAND_PROP_" . $arParams['IBLOCK_ID'] => $arParams['BRAND_PROP'][$arParams['IBLOCK_ID']],
                "ICON_NOVELTY_PROP_" . $arParams['IBLOCK_ID'] => $arParams['ICON_NOVELTY_PROP'][$arParams['IBLOCK_ID']],
                "ICON_DEALS_PROP_" . $arParams['IBLOCK_ID'] => $arParams['ICON_DEALS_PROP'][$arParams['IBLOCK_ID']],
                "ICON_DISCOUNT_PROP_" . $arParams['IBLOCK_ID'] => $arParams['ICON_DISCOUNT_PROP'][$arParams['IBLOCK_ID']],
                "ICON_MEN_PROP_" . $arParams['IBLOCK_ID'] => $arParams['ICON_MEN_PROP'][$arParams['IBLOCK_ID']],
                "ICON_WOMEN_PROP_" . $arParams['IBLOCK_ID'] => $arParams['ICON_WOMEN_PROP'][$arParams['IBLOCK_ID']],
                "ADDITIONAL_PICT_PROP_" . $arSKU[$IBLOCK_ID]['IBLOCK_ID'] => $arParams['OFFER_ADDITIONAL_PICT_PROP'],
                "PROPERTY_CODE_" . $arSKU[$IBLOCK_ID]['IBLOCK_ID'] => $arParams["LIST_OFFERS_PROPERTY_CODE"],
                "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
                "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                'USE_LIKES' => $arParams['USE_LIKES'],
                'USE_SHARE' => $arParams['USE_SHARE'],
                'SOCIAL_SERVICES' => $arParams['LIST_SOCIAL_SERVICES'],
                'SOCIAL_COUNTER' => $arParams['SOCIAL_COUNTER'],
                'SOCIAL_COPY' => $arParams['SOCIAL_COPY'],
                'SOCIAL_LIMIT' => $arParams['SOCIAL_LIMIT'],
                'SOCIAL_SIZE' => $arParams['SOCIAL_SIZE'],
                'POPUP_DETAIL_VARIABLE' => $arParams['POPUP_DETAIL_VARIABLE'],
                "SECTION_TITLE" => $arResult['PROPERTIES'][$sPropCode]["NAME"],
                'BRAND_IBLOCK_ID' => $arParams['BRAND_IBLOCK_ID'],
                'BRAND_IBLOCK_BRAND_PROP' => $arParams['BRAND_IBLOCK_BRAND_PROP'],
            ),
            $component,
            array('HIDE_ICONS' => 'Y')
        );
    }
}
$this->EndViewTarget();
