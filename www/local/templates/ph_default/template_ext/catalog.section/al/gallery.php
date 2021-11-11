<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

use \Bitrix\Main\Localization\Loc;

global ${$arParams['FILTER_NAME']};

$component = $this->getComponent();

$protocol = \Bitrix\Main\Context::getCurrent()->getRequest()->isHttps() ? "https://" : "http://";
?>
<?php foreach ($arResult['ITEMS'] as $iItemKey => $arItem): ?>
	<?php
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strEdit);
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strDelete, $arDeleteParams);
	$strMainID = $this->GetEditAreaId($arItem['ID']);

	$bHaveOffer = false;
	if (empty($arItem['OFFERS'])) {
		$arItemShow = $arItem;
	} else  {
		$bHaveOffer = true;
		$arItemShow = $arItem['OFFERS'][$arItem['OFFERS_SELECTED']];
	}

	$arItemShowPrice = array();
	if ($arParams['USE_PRICE_COUNT'] && is_array($arItemShow['PRICE_MATRIX']['COLS']))
	{
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

	} else {
		foreach ($arItemShow['PRICES'] as $arPrice) {
			if ($arPrice['MIN_PRICE'] != 'Y') {
				continue;
			}

			$arItemShowPrice = $arPrice;
			break;
		}
	}
	$sItemClass = 'catalog_item product js-product col';
	if (isset($arItem['DAYSARTICLE2']) || isset($arItemShow['DAYSARTICLE2'])) {
		$sItemClass .= ' da';
	}
	if (isset($arItem['QUICKBUY']) || isset($arItemShow['QUICKBUY'])) {
		$sItemClass .= ' qb';
	}
	if ($arParams['LINE_ELEMENT_COUNT'] == '5') {
		$sItemClass .= ' col-xs-6 col-sm-4 col-md-3 col-lg-2d4';
	} else {
		$sItemClass .= ' col-xs-6  col-sm-4 col-md-4 col-lg-3';
	}
	?>
	<article
		class="<?=$sItemClass?>"
		id="<?=$strMainID?>"
		data-product-id="<?=$arItem['ID']?>"
		<?php if ($bHaveOffer): ?>data-offer-id="<?=$arItemShow['ID']?>"<?php endif; ?>
	>
		<div class="catalog_item__inner">

				<div class="catalog__corner corner"><div class="corner__in"><div class="corner__text">
				<?php
				if (isset($arItem['DAYSARTICLE2']) || isset($arItemShow['DAYSARTICLE2'])) {
					echo Loc::getMessage('RS_SLINE.BCS_AL.DAYSARTICLE');
				} elseif (isset($arItem['QUICKBUY']) || isset($arItemShow['QUICKBUY'])) {
					echo Loc::getMessage('RS_SLINE.BCS_AL.QUICKBUY');
				}
				?>
				</div></div></div>

			<a class="catalog_item__pic
				<?php if ('ON_IMAGE' == $arParams['POPUP_DETAIL_VARIABLE']): ?>
					js-ajax_link" data-fancybox="product<?=$arItem['ID']?>" data-type="ajax" title="<?=Loc::getMessage('RS_SLINE.BCS_AL.CLICK_FOR_DETAIL_POPUP')?>
				<?php endif; ?>"
				href="<?=$arItem['DETAIL_PAGE_URL']?>" itemscope itemtype="https://schema.org/ImageObject"
			><?php
				if (isset($arItem['FIRST_PIC'][0]))
				{
					?><img class="catalog_item__img" src="<?=$arItem['FIRST_PIC'][0]['RESIZE'][0]['src']?>" alt="<?=$arItem['FIRST_PIC'][0]['ALT']?>" title="<?=$arItem['FIRST_PIC'][0]['TITLE']?>" itemprop="contentUrl"><?php
					?><meta itemprop="width" content="<?=$arItem['FIRST_PIC'][0]['RESIZE'][0]['width']?>"><?php
					?><meta itemprop="height" content="<?=$arItem['FIRST_PIC'][0]['RESIZE'][0]['height']?>"><?php
				}
				else
				{
					?><img class="catalog_item__img" src="<?=SITE_TEMPLATE_PATH?>/assets/img/noimg.png" title="<?=$arItem['NAME']?>" alt="<?=$arItem['NAME']?>" itemprop="contentUrl"><?php
				}

				if ($arParams['POPUP_DETAIL_VARIABLE'] == 'ON_LUPA')
				{
					?>
					<i class="catalog_item__zoom js-ajax_link" data-fancybox="product<?=$arItem['ID']?>" data-type="ajax" data-src="<?=$arItem['DETAIL_PAGE_URL']?>" title="<?=Loc::getMessage('RS_SLINE.BCS_AL.CLICK_FOR_DETAIL_POPUP')?>" rel="nofollow">
						<svg class="icon-glass icon-svg"><use xlink:href="#svg-glass"></use></svg>
					</i>
					<?php
				}

				if (
					isset($arItem['PROPERTIES'][$arParams['ICON_MEN_PROP'][$arItem['IBLOCK_ID']]]) && $arItem['PROPERTIES'][$arParams['ICON_MEN_PROP'][$arItem['IBLOCK_ID']]]['VALUE'] != '' ||
					isset($arItem['PROPERTIES'][$arParams['ICON_WOMEN_PROP'][$arItem['IBLOCK_ID']]]) && $arItem['PROPERTIES'][$arParams['ICON_WOMEN_PROP'][$arItem['IBLOCK_ID']]]['VALUE'] != ''
				): ?>
					<span class="catalog_item__gender gender">
					<?php if ($arItem['PROPERTIES'][$arParams['ICON_MEN_PROP'][$arItem['IBLOCK_ID']]]['VALUE'] != ''): ?>
						<svg class="icon-men icon-svg"><use xlink:href="#svg-men"></use></svg>
					<?php endif; ?>
					<?php if ($arItem['PROPERTIES'][$arParams['ICON_WOMEN_PROP'][$arItem['IBLOCK_ID']]]['VALUE'] != ''): ?>
						<svg class="icon-women icon-svg"><use xlink:href="#svg-women"></use></svg>
					<?php endif; ?>
					</span>
				<?php endif; ?>

				<span class="catalog_item__stickers js_swap_hide"
					<?php if (isset($arItem['DAYSARTICLE2']) || isset($arItem['QUICKBUY'])): ?>
						style="display:none;"
					<?php endif; ?>
				>
					<?php
					if (
						$arParams['ICON_NOVELTY_PROP'][$arItem['IBLOCK_ID']]
						&& (
							$arItem['PROPERTIES'][$arParams['ICON_NOVELTY_PROP'][$arItem['IBLOCK_ID']]]['VALUE'] == 'Y'
							|| $arItem['PROPERTIES'][$arParams['ICON_NOVELTY_PROP'][$arItem['IBLOCK_ID']]]['VALUE_XML_ID'] == 'yes'
							|| $arParams['NOVELTY_TIME'] && $arParams['NOVELTY_TIME'] >= (floor($_SERVER['REQUEST_TIME'] - MakeTimeStamp($arItem['DATE_ACTIVE_FROM']))/3600)
						)
					):
					?>
						<span class="sticker new">
							<span class="sticker__text">
								<?=$arItem['PROPERTIES'][$arParams['ICON_NOVELTY_PROP'][$arItem['IBLOCK_ID']]]['NAME']?>
							</span>
						</span>
					<?php endif; ?>
					<!--Закоментировал вывод стикеров, так как изначальный механизм расчитан на выбор торгового предложения через селектор. Начало-->
					<?//php if ($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y' && $arItemShowPrice['DISCOUNT_DIFF_PERCENT'] > 0): ?>
					<!--	<span class="sticker discount">
							<span class="sticker__text">
								<span class="price_pdp js-price_pdp-<?//=$arItemShowPrice['PRICE_ID']?>"><?//=$arItemShowPrice['DISCOUNT_DIFF_PERCENT'].'%'?></span>
							</span>
						</span>
					<//?php elseif (
						$arParams['ICON_DISCOUNT_PROP'][$arItem['IBLOCK_ID']]
						&& (
							$arItem['PROPERTIES'][$arParams['ICON_DISCOUNT_PROP'][$arItem['IBLOCK_ID']]]['VALUE'] == 'Y'
							|| $arItem['PROPERTIES'][$arParams['ICON_DISCOUNT_PROP'][$arItem['IBLOCK_ID']]]['VALUE_XML_ID'] == 'yes'
						)
					): ?>
						<span class="sticker discount">
							<span class="sticker__text">
								<?//=$arItem['PROPERTIES'][$arParams['ICON_DISCOUNT_PROP'][$arItem['IBLOCK_ID']]]['NAME']?>
							</span>
						</span>
					<?//php endif; ?>-->
		<!--Закоментировал вывод стикеров, так как изначальный механизм расчитан на выбор торгового предложения через селектор. Конец-->
					<?php
					if (
						$arParams['ICON_DEALS_PROP'][$arItem['IBLOCK_ID']]
						&& (
							$arItem['PROPERTIES'][$arParams['ICON_DEALS_PROP'][$arItem['IBLOCK_ID']]]['VALUE'] == 'Y'
							|| $arItem['PROPERTIES'][$arParams['ICON_DEALS_PROP'][$arItem['IBLOCK_ID']]]['VALUE_XML_ID'] == 'yes'
						)
					):
					?>
						<span class="sticker action">
							<span class="sticker__text">
								<?=$arItem['PROPERTIES'][$arParams['ICON_DEALS_PROP'][$arItem['IBLOCK_ID']]]['NAME']?>
							</span>
						</span>
					<?php endif; ?>
				</span>

				<?php
				// TIMERS
				$arTimers = array();
				if (isset($arItem['DAYSARTICLE2'])) {
					$arTimers[] = $arItem['DAYSARTICLE2'];
				}
				if (isset($arItem['QUICKBUY'])) {
					$arTimers[] = $arItem['QUICKBUY'];
				}

				if (is_array($arItem['OFFERS']))
				{
					foreach ($arItem['OFFERS'] as $arOffer)
					{
						if (isset($arOffer['DAYSARTICLE2']))
						{
							$arTimers[] = $arOffer['DAYSARTICLE2'];
						}
						if (isset($arOffer['QUICKBUY']))
						{
							$arTimers[] = $arOffer['QUICKBUY'];
						}
					}
				}
				$have_vis = false;
				?>

				<?php if (is_array($arTimers) && 0 < count($arTimers)): ?>

					<?php foreach ($arTimers as $arTimer): ?>
						<?php
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
						<div class="catalog_item-timer js_timer timer_bg" style="display:
							<?=((($arItem['ID'] == $arTimer['ELEMENT_ID'] || $arItemShow['ID'] == $arTimer['ELEMENT_ID']) && !$have_vis) ? 'block' : 'none')?>
						" data-offer-id="<?=$arTimer['ELEMENT_ID']?>" data-timer="<?=htmlspecialcharsbx(\Bitrix\Main\Web\Json::encode($jsTimer))?>">
							<div class="catalog_item-timer-val">
								<span class="value js_timer-d">00</span>
								<span class="podpis"><?=Loc::getMessage('RS_SLINE.BCS_AL.QB_DAY')?></span>
							</div>
							<div class="catalog_item-timer-val">
								<span class="value js_timer-H">00</span>
								<span class="podpis"><?=Loc::getMessage('RS_SLINE.BCS_AL.QB_HOUR')?></span>
							</div>
							<div class="catalog_item-timer-val">
								<span class="value js_timer-i">00</span>
								<span class="podpis"><?=Loc::getMessage('RS_SLINE.BCS_AL.QB_MIN')?></span>
							</div>
							<div class="catalog_item-timer-val">
								<span class="value js_timer-s">00</span>
								<span class="podpis"><?=Loc::getMessage('RS_SLINE.BCS_AL.QB_SEC')?></span>
							</div>
							<div class="catalog_item-timer-separator"></div>
							<div class="catalog_item-timer-val">
								<?php if (isset($arTimer['TIMER'])): ?>
									<span class="value"><?=($arTimer['QUANTITY'] > 99 ? $arTimer['QUANTITY'] : sprintf('%02d', $arTimer['QUANTITY']))?></span>
									<span class="podpis"><?=Loc::getMessage('RS_SLINE.BCS_AL.QB_QUANTITY')?></span>
								<?php elseif (isset($arTimer['DINAMICA_EX'])): ?>
									<span class="value js_timer-progress">0%</span>
									<span class="podpis"><?=Loc::getMessage('RS_SLINE.BCS_AL.DA_PRODANO')?></span>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach?>
				<?php endif; ?>
			</a>
			<div class="catalog_item__head clearfix">
				<a class="catalog_item__name text_fade js-product__name" href="<?=$arItem['DETAIL_PAGE_URL']?>">
					<?php
					echo (isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arItem["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != '')
						? $arItem["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
						: $arItem["NAME"];
					?>
				</a>
				<?php
				$sBrandPropCode = $arParams['BRAND_PROP'][$arItem['IBLOCK_ID']];
				if (isset($arItem['PROPERTIES'][$sBrandPropCode]['VALUE'])):
				?>
					<div class="catalog_item__brand b-text_fade">
					<?php if (is_array($arItem['PROPERTIES'][$sBrandPropCode]['VALUE'])): ?>
						<?php
						echo implode(' / ', array_map(
							function($sName, $sLink) {
								if (isset($arResult['BRANDS'][$sName])) {
									$sBrandUrl = $arResult['BRANDS'][$sName]['DETAIL_PAGE_URL'];
								} else {
									$sBrandUrl = $sLink;
								}
								return '<a href="' . $sBrandUrl . '">' . $sName . '</a>';
							},
							$arItem['PROPERTIES'][$sBrandPropCode]['VALUE'],
							$arItem['PROPERTIES'][$sBrandPropCode]['FILTER_URL']
						));
						?>
					<?php else: ?>
						<?php if (isset($arResult['BRANDS'][$arItem['PROPERTIES'][$sBrandPropCode]['VALUE']])): ?>
							<a href="<?=$arResult['BRANDS'][$arItem['PROPERTIES'][$sBrandPropCode]['VALUE']]['DETAIL_PAGE_URL']?>">
								<?=$arResult['BRANDS'][$arItem['PROPERTIES'][$sBrandPropCode]['VALUE']]['NAME']?>
							</a>
						<?php else: ?>
							<a href="<?=$arItem['PROPERTIES'][$sBrandPropCode]['FILTER_URL']?>">
								<?php
								if (isset($arItem['DISPLAY_PROPERTIES'][$sBrandPropCode]['DISPLAY_VALUE'])) {
									echo $arItem['DISPLAY_PROPERTIES'][$sBrandPropCode]['DISPLAY_VALUE'];
								} else {
									echo $arItem['PROPERTIES'][$sBrandPropCode]['VALUE'];
								}
								?>
							</a>
						<?php endif;?>
					<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
			<!--Выводить крупную цену, только для товаров без характеристик-->
			<?php if (!empty($arItemShowPrice) && !$bHaveOffer): ?>
					<table class="table offers_custom_table">
						<tbody>
								<tr>
										<td class="offer_gallery">
											<div class="catalog_item__price price<?php if ($arItemShowPrice['DISCOUNT_DIFF']): ?> price-disc<?php endif; ?> clearfix itdelta-price-disc">
											<div class="price__pdv js-price_pdv-<?=$arItemShowPrice['PRICE_ID']?> blackPrice">
												<?=$arItemShowPrice['PRINT_DISCOUNT_VALUE']?>
											</div>
											<?php if ($arParams['SHOW_OLD_PRICE'] == 'Y'): ?>
												<div class="price__pv js-price_pv-<?=$arItemShowPrice['PRICE_ID']?> itdelta-FullPrice">
													<?php
													if ($arItemShowPrice['DISCOUNT_DIFF']) {
														echo $arItemShowPrice['PRINT_VALUE'];
													}
													?>
												</div>

												<div class="price__pdd js-price_pdd-<?=$arItemShowPrice['PRICE_ID']?>"<?php if ($arItemShowPrice['DISCOUNT_DIFF'] <= 0): ?> style="display:none"<?php endif; ?>>
													- <?=$arItemShowPrice['PRINT_DISCOUNT_DIFF']?>
												</div>
											<?php endif; ?>
											</div>
										</td>
										<td class="offer_gallery">
											<form class="clearfix" name="buy_form">
												<input type="hidden" name="<?=$arParams['ACTION_VARIABLE']?>" value="ADD2BASKET">
												<input type="hidden" name="<?=$arParams['PRODUCT_ID_VARIABLE']?>" class="js-product_id" value="<?= $arItem['ID']?>">
												<input type="hidden" name="<?=$offer['CATALOG_QUANTITY']?>" class="itdelta-max" value="<?= $arItem['CATALOG_QUANTITY']?>">
												<button class="detail__btn btn itdelta__small-basket">
														<svg class="icon icon-cart icon-svg icon-cart-list">
																<use xlink:href="#svg-cart"></use>
														</svg>
												</button>
													</form>
										</td>
								</tr>
							</tbody>
							</table>
			<?php endif; ?>

			<? if ($bHaveOffer): ?>
			<!--Выводит таблицу с предложениями в карточку  товаров. Начало-->
			<table class="table offers_custom_table">
					<tbody>
					<? foreach ($arItem['OFFERS'] as $k => $offer):
						if ($offer['CATALOG_QUANTITY'] > 0) {
					?>
					<?
							$offer_weight_val = $offer['DISPLAY_PROPERTIES']['WEIGHT']['DISPLAY_VALUE'];
							$offer_weight = preg_replace("/[^0-9]/", '', $offer_weight_val);
							if(str_replace(array('гр', 'гр.'), '', $offer_weight_val) != $offer_weight_val){
									$offer_weight = $offer_weight / 1000;
							};
					?>
							<tr>
									<td class="offer_gallery itdelta_offer_gallery">
											<div class="offer_rows">
													<div class="offer_weight"><?= $offer['DISPLAY_PROPERTIES']['WEIGHT']['DISPLAY_VALUE'] ?></div>
									</td>

									<td class="offer_price offer_gallery offer__price_gallery">

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
								<td>
								<form class="clearfix" name="buy_form">
									<input type="hidden" name="<?=$arParams['ACTION_VARIABLE']?>" value="ADD2BASKET">
									<input type="hidden" name="<?=$arParams['PRODUCT_ID_VARIABLE']?>" class="js-product_id" value="<?= $offer['ID']?>">

									<button class="detail__btn btn itdelta__small-basket">
											<svg class="icon icon-cart icon-svg icon-cart-list">
													<use xlink:href="#svg-cart"></use>
											</svg>
									</button>
								</form>
									</td>
							</tr>

					<?
				};
				endforeach; ?>
					</tbody>
			</table>
			<!--Выводит таблицу с предложениями в карточку  товаров. Конец-->


				<?php if (is_array($arItem['OFFERS_EXT']['PROPERTIES']) && 0 < count($arItem['OFFERS_EXT']['PROPERTIES'])): ?>

					<div class="catalog_item__offer_props js-product__offer_props clearfix">

						<?php foreach ($arItem['OFFERS_EXT']['PROPERTIES'] as $sPropCode => $arProperty): ?>
							<?php
							$bIsColor = $bIsBtn = false;
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
							?>
							<?php if ($bIsColor || $bIsBtn): ?>
								<div class="<?=$sOfferPropClass.' prop_'.$sPropCode?> js-offer_prop" data-code="<?=$sPropCode?>">
									<span class="offer_prop__name"><?=$arItem['OFFERS_EXT']['PROPS'][$sPropCode]['NAME']?>:</span>
									<ul class="offer_prop__values clearfix">
										<?php foreach ($arProperty as $value => $arValue): ?>
											<?php
											$sOfferPropValueClass = 'offer_prop__value';
											if ($arValue['FIRST_OFFER'] == 'Y') {
												$sOfferPropValueClass .= ' checked';
											} elseif ($arValue['DISABLED_FOR_FIRST'] == 'Y') {
												$sOfferPropValueClass .= ' disabled';
											}
											?>
											<li class="<?=$sOfferPropValueClass?>" data-value="<?=htmlspecialcharsbx($arValue['VALUE'])?>">
												<?php if ($bIsColor): ?>
													<?php
													$sOfferPropIcon = is_array($arValue['PICT'])
														? 'background-image:url('.$arValue['PICT']['SRC'].')'
														: 'background-color:'.$arResult['COLORS_TABLE'][ToUpper($arValue['VALUE'])]['RGB'];
													?>
													<span class="offer_prop__icon">
														<span class="offer_prop__img" title="<?=$arValue['VALUE']?>" style="<?=$sOfferPropIcon?>"></span>
													</span>
												<?php else: ?>
													<?=$arValue['VALUE']?>
												<?php endif; ?>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php else: ?>
								<?php $dropdownId = $this->getEditAreaId('offer_prop_'.$arItem['ID'].'_'.$arItem['OFFERS_EXT']['PROPS'][$sPropCode]['ID']) ?>
								<div class="offer_prop prop_<?=$sPropCode?> js-offer_prop" data-code="<?=$sPropCode?>">
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php else: ?>

				<?php endif; ?>

			<?php else: ?>
				<?php $emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']) ?>
				<?php if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties): ?>
					<div class="product_props" style="display: none;">
						<?php
						if (!empty($arItem['PRODUCT_PROPERTIES_FILL'])) {
							foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo) {
								?>
								<input class="js-product_prop" type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
								<?
								if (isset($arItem['PRODUCT_PROPERTIES'][$propID])) {
									unset($arItem['PRODUCT_PROPERTIES'][$propID]);
								}
							}
						}
						$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
						if (!$emptyProductProperties)
						{
							foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo)
							{
								$bIsColor = $bIsBtn = false;
								if (
									$arItem['PROPERTIES'][$propID]['PROPERTY_TYPE'] == 'S' &&
									$arItem['PROPERTIES'][$propID]['USER_TYPE'] == 'directory'
								) {
									$bIsColor = true;
								}
								?>
								<div id="<?=$strMainID?>_prop_<?=$arItem['PROPERTIES'][$propID]['ID']?>" data-prop-id="<?=$arItem['PROPERTIES'][$propID]['ID']?>">

								<?php
								if (
									'L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE']
									&& in_array($arItem['PROPERTIES'][$propID]['LIST_TYPE'], array('L', 'C'))
									|| $bIsColor
								)
								{
									foreach($propInfo['VALUES'] as $valueID => $value)
									{
										if ($valueID == $propInfo['SELECTED']) {
											?>
												<input
													class="js-product_prop"
													type="hidden"
													name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
													value="<? echo $valueID; ?>"
												>
											<?php
											break;
										}
									}
								}
								else
								{
									foreach($propInfo['VALUES'] as $valueID => $value)
									{
										if ($valueID == $propInfo['SELECTED']) {
											?>
												<input
													class="js-product_prop"
													type="hidden"
													name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
													value="<? echo $valueID; ?>"
												>
											<?php
											break;
										}
									}
								}
								?>
								</div>
								<?php
							}
						}
						?>
					</div>
				<?php endif ?>

			<?php endif; ?>

			<?php if ($component->getName() == 'bitrix:catalog.product.subscribe.list'): ?>
				<a class="catalog_item__detail btn" href="<?=$arItem['DETAIL_PAGE_URL']?>">
					<?=Loc::getMessage('RS_SLINE.BCS_AL.MESS_BTN_DETAIL')?>
				</a>
				<?php
				$arUnsubscribeData = array();
				if ($bHaveOffer)
				{
					if (is_array($arItem['OFFERS']))
					{
						foreach ($arItem['OFFERS'] as $arOffer)
						{
							if (isset($arParams['LIST_SUBSCRIPTIONS'][$arOffer['ID']]))
							{
								$arUnsubscribeData[$arOffer['ID']] = $arParams['LIST_SUBSCRIPTIONS'][$arOffer['ID']];
							}
						}
					}
				}
				else
				{
					$arUnsubscribeData = array(
						$arItem['ID'] => $arParams['LIST_SUBSCRIPTIONS'][$arItem['ID']]
					);
				}
				?>
				<a class="catalog_item__unsubscribe js-product__unsubscribe" data-subscribe-id="<?=htmlspecialcharsbx(\Bitrix\Main\Web\Json::encode($arUnsubscribeData))?>"><?=GetMessage('RS_SLINE.BCS_AL.MESS_BTN_UNSUBSCRIBE');?></a>
			<?php endif; ?>

			<div class="catalog_item__popup clearfix">

				<div class="catalog_item__buy">

					<!--noindex-->
						<?php
						if ($component->getName() !== 'bitrix:catalog.product.subscribe.list')
						{
							if ($bHaveOffer)
							{
								$APPLICATION->includeComponent(
									'bitrix:catalog.product.subscribe',
									'al',
									array(
										'PRODUCT_ID' => $arItem['ID'],
										'OFFER_ID' => $arItemShow['ID'],
										'BUTTON_ID' => $strMainID.'_subscribe',
										'BUTTON_CLASS' => 'btn catalog_item__subscr js-subscribe',
										'DEFAULT_DISPLAY' => !$arItemShow['CAN_BUY'],
									),
									$component,
									array('HIDE_ICONS' => 'Y')
								);

							}
							else
							{
								if ($arItem['CATALOG_SUBSCRIBE'] == 'Y')
								{
									if (!$arItem['CAN_BUY'])
									{
										$APPLICATION->includeComponent(
											'bitrix:catalog.product.subscribe',
											'al',
											array(
												'PRODUCT_ID' => $arItem['ID'],
												'BUTTON_ID' => $strMainID.'_subscribe',
												'BUTTON_CLASS' => 'btn catalog_item__subscr js-subscribe',
												'DEFAULT_DISPLAY' => true,
											),
											$component,
											array('HIDE_ICONS' => 'Y')
										);
									}
								}
							}
						}
						?>
						<form class="clearfix" name="buy_form">
							<input type="hidden" name="<?=$arParams['ACTION_VARIABLE']?>" value="ADD2BASKET">
							<input type="hidden" name="<?=$arParams['PRODUCT_ID_VARIABLE']?>" class="js-product_id" value="<?=$arItemShow['ID']?>">

							<!--Закоментировал, так как кнопка добавить в корзину есть напротив каждого торгового предложения. Начало-->
								<!--<a class="catalog_item__btn btn added2cart" href="<?//=$arParams['BASKET_URL']?>" title="<?//=Loc::getMessage('RS_SLINE.BCS_AL.MESS_BTN_IN_BASKET_TITLE')?>" rel="nofollow">
								<?//=Loc::getMessage('RS_SLINE.BCS_AL.MESS_BTN_IN_BASKET')?>
							</a>
							<button class="catalog_item__btn btn add2cart<?//php if (!$arItemShow['CAN_BUY']): ?> disabled<?//php endif; ?> js-add2cart" type="submit"<?//php if (!$arItemShow['CAN_BUY']): ?> disabled<?//php endif; ?>>
								<?//=($arParams['MESS_BTN_ADD_TO_BASKET'] != '' ? $arParams['MESS_BTN_ADD_TO_BASKET'] : Loc::getMessage('RS_SLINE.BCS_AL.MESS_BTN_ADD_TO_BASKET'))?>
							</button>-->
							<!--Закоментировал, так как кнопка добавить в корзину есть напротив каждого торгового предложения. Конец-->
							<!--Закоментировал, так как не определено расположение кнопки для сравнения в товарах с различными торговыми предложениями. Начало-->
								<!--	<?//php if ($arParams['DISPLAY_COMPARE'] == 'Y'): ?>
										<a class="catalog_item__cmp cmp__link js-compare" href="<//?=str_replace('#ID#', $arItemShow['ID'], $arResult['COMPARE_URL_TEMPLATE'])?>" rel="nofollow">
											<svg class="cmp__icon icon-cmp icon-svg"><use xlink:href="#svg-cmp"></use></svg><?//=($arParams['MESS_BTN_COMPARE'] != '' ? $arParams['MESS_BTN_COMPARE'] : Loc::getMessage('RS_SLINE.BCS_AL.MESS_BTN_COMPARE'))?>
										</a>
									<//?php endif; ?> -->
										<!--Закоментировал, так как не определено расположение кнопки для сравнения в товарах с различными торговыми предложениями. Конец-->

								<!--Закоментировал, так как при выводе списка предложений, эта кнопка не актуальна. Начало-->
							<?//php if ($arParams['USE_PRODUCT_QUANTITY']): ?>
								<!--<div class="catalog_item__quantity">
									<div class="quantity quantity-small<//?php if (!$arItemShow['CAN_BUY']): ?> disabled<?//php endif ?>">
										<i class="quantity__minus js-basket-minus"></i><input
											type="number"
											class="quantity__input js-quantity <?//php if ($arParams['USE_PRICE_COUNT']): ?> js-use_count<//?php endif; ?>"
											name="<?//=$arParams['PRODUCT_QUANTITY_VARIABLE']?>"
											value="<?//=$arItemShow['CATALOG_MEASURE_RATIO']?>"
											step="<?//=$arItemShow['CATALOG_MEASURE_RATIO']?>"
											min="<?//=$arItemShow['CATALOG_MEASURE_RATIO']?>"
											<?/* max="<?=$arItemShow['CATALOG_QUANTITY']?>"*/?>
										><i class="quantity__plus js-basket-plus"></i>
									</div>
									<span class="catalog_item__measure js-measure"><?//=$arItem['CATALOG_MEASURE_NAME']?></span>
								</div>
							<?//php endif; ?>-->
							<!--Закоментировал, так как при выводе списка предложений, эта кнопка не актуальна. Конец-->
						</form>

					<!--/noindex-->
				</div>

				<?php if ($arParams['DISPLAY_PREVIEW_TEXT'] == 'Y' && $arItem['PREVIEW_TEXT'] != ''): ?>
					<div class="catalog_item__preview"><?=$arItem['PREVIEW_TEXT']?></div>
				<?php endif; ?>

				<?php if ($arParams['USE_LIKES'] == 'Y' || $arParams['USE_SHARE'] == 'Y'): ?>
					<div class="clearfix">
						<?php if ($arParams['USE_LIKES'] == 'Y'): ?>
							<span class="catalog_item__favorite favorite js-favorite">
								<svg class="favorite__icon icon-heart icon-svg"><use xlink:href="#svg-heart"></use></svg>
								<span class="favorite__cnt">
									<?php
									if (intval($arItem['PROPERTIES'][$arParams['LIKES_COUNT_PROP']]['VALUE']) > 0) {
										echo $arItem['PROPERTIES'][$arParams['LIKES_COUNT_PROP']]['VALUE'];
									}
									?>
								</span>
							</span>
						<?php endif; ?>
					<?php if ($arParams['USE_SHARE'] == 'Y'): ?>
						<div class="l-context">
							<?/*
							<a class="catalog_item__mail js_email2friend js-ajax_link" data-type="ajax" href="#email2friend" title="<?=Loc::getMessage('RS_SLINE.BCS_AL.MESS_BTN_EMAIL2FRIEND')?>" data-url="<?=$arItem['DETAIL_PAGE_URL']?>">
								<svg class="icon-mail icon-svg"><use xlink:href="#svg-mail"></use></svg>
							</a>
							*/?>
							<div class="l-context ya-share2"
								data-url="<?=$protocol.SITE_SERVER_NAME.$arItem['DETAIL_PAGE_URL']?>"
								<?php if ($arParams['SOCIAL_COUNTER'] == 'Y'): ?>
									data-counter
								<?php endif; ?>
								<?php if ($arParams['SOCIAL_COPY'] != 'last'): ?>
									data-copy="<?=$arParams['SOCIAL_COPY']?>"
								<?php endif; ?>
								<?php if (intval($arParams['SOCIAL_LIMIT']) > 0): ?>
									data-limit="<?=$arParams['SOCIAL_LIMIT']?>"
								<?php endif; ?>
								<?php if (is_array($arParams['SOCIAL_SERVICES'])): ?>
									data-services="<?=implode(',', $arParams['SOCIAL_SERVICES']);?>"
								<?php endif; ?>
								data-size="s"
								data-lang="<?=LANGUAGE_ID?>"
							<?/*data-bare=""*/?>></div>
						</div>
					<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</article>
<?php endforeach; ?>
