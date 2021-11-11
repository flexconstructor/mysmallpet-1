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

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0)
{
    $iPicrureHeightMax = max(array_map(
        function($arItem){
            return intval($arItem['PREVIEW_PICTURE']['RESIZE']['height']);
        },
        array_filter($arResult['ITEMS'], function($arItem) {
            return (
                is_array($arItem['PREVIEW_PICTURE'])
            );
        })
    ));
}

global $bRSHomeTabShow;
$tabId = $this->getEditAreaId('tab');

if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0)
{
	?>
    <div id="<?=$tabId?>" class="tab-pane<?php if (!$bRSHomeTabShow && !empty($arResult['ITEMS'])): ?> active<?php endif; ?>">
        <div class="carousel js-carousel_brands owl-shift">
            <?php
            foreach($arResult['ITEMS'] as $arItem)
			{
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
				<a class="carousel__item"  id="<?=$this->GetEditAreaId($arItem['ID']);?>" href="<?=$arItem['DETAIL_PAGE_URL']?>">
					<?php
					if ('N' != $arParams['DISPLAY_PICTURE'] && is_array($arItem['PREVIEW_PICTURE']))
					{
						?>
						<div class="carousel__pic"<?php if ($iPicrureHeightMax > 0): ?> style="height:<?=$iPicrureHeightMax?>px"<?php endif; ?>>
							<img class="carousel__img" src="<?=$arItem['PREVIEW_PICTURE']['RESIZE']['src']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>">
						</div>
						<?php
					}
					else
					{
						?><div class="carousel__name"><?=$arItem['NAME'];?></div><?php
					}
					?>
				</a>
				<?php
			}
			?>
        </div>
    </div>
	<?php
}

$this->SetViewTarget('main_brands_tab');


	if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0)
	{
		?>

		<li<?php if(!$bRSHomeTabShow): ?> class="active"<?php endif; ?>>
			<a href="#<?=$tabId?>" data-toggle="tab"><?echo $arResult['NAME'];?></a>
		</li>
	<?php
	}

$this->EndViewTarget();
