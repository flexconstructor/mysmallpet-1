<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Localization\Loc;


if(!empty($arResult["CATEGORIES"]))
{
	?>
	<div class="search_popup">
		<div class="title_search_result-catalog">
			<?php
			foreach ($arResult["CATEGORIES"] as $nCategoryId => $arCategory)
			{
				?>
				<div class="title_search_result__cat">

					<?php
					if (strlen($arCategory['TITLE']) > 0)
					{
						?>
						<div class="title_search_result-catalog-item_inner">
							<b><?=$arCategory['TITLE']?></b>
						</div>
						<?php
					}

					foreach ($arCategory["ITEMS"] as  $arItem)
					{
						if ($arItem['TYPE'] == 'all')
							continue;

						if (isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]]))
						{
							$arCatalogItem = $arResult["ELEMENTS"][$arItem["ITEM_ID"]];
							?>
							<div class="title_search_result-catalog-item">
								<div class="title_search_result-catalog-item_inner">
									<a href="<?=$arItem['URL']?>" class="title_search_result-catalog-item-img">
									<?php if (is_array($arCatalogItem['PICTURE'])): ?>
										<img src="<?=$arCatalogItem["PICTURE"]["src"]?>">
									<?php else: ?>
										<img src="<?=$templateFolder?>/images/no_photo.png">
									<?php endif; ?>
									</a>
						
									<div class="title_search_result-catalog-item-name">
										<a href="<?=$arItem['URL']?>"><?=$arItem["NAME"]?></a>
									</div>
					
									<?php if (!empty($arCatalogItem['MIN_PRICE'])): ?>
										<div class="title_search_result-catalog-item-price clearfix">
											<span class="price"><?=$arCatalogItem["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?></span>
											<?php if (isset($arCatalogItem["MIN_PRICE"]['DISCOUNT_VALUE']) && $arCatalogItem["MIN_PRICE"]["DISCOUNT_VALUE"] < $arCatalogItem["MIN_PRICE"]["VALUE"]): ?>
												<span class="crossed_price"><?=$arCatalogItem["MIN_PRICE"]["PRINT_VALUE"]?></span>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<?php
						}
						else
						{
							?>
							<div class="title_search_result-other-item"><a href="<?=$arItem["URL"]?>"><?=$arItem["NAME"]?></a></div>
							<?php
						}
						?>
						<div class="search_popup__sep"></div>
						<?php
					}
					?>
				</div>
				<?php 
			}
			?>
		</div>
	</div>
	<?php
}